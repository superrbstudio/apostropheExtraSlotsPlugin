<style type="text/css">
  /* I am here to be cloned by js, not to be shown directly */
  .template
  {
    display: none;
  }
  th, td
  {
    padding: 10px;
  }
  .editor
  {
    margin-right: 10px;
  }
  .action
  {
    margin-left: 10px;
    margin-right: 10px;
  }
  .highlighted
  {
    background-color: #bfb;
  }
  .clickme
  {
    cursor: pointer;
  }
  .clickme:hover
  {
    text-decoration: underline;
  }
  .edit
  {
    display: none;
  }
</style>

<h2 data-spinner>Loading...</h2>

<table data-table style="display: none">
  <thead>
    <tr data-heading-row>
      <th>Tag</th>
      <th data-model-name-template class="template">
      </th>
    </tr>
  </thead>
  <tbody data-row-parent>
    <tr class="template" data-row data-row-template>
      <td class="clickme" data-tag-name data-normal></td>
      <td data-model-count-template data-model-count data-normal class="template">0</td>
      <?php // data-edit must be a single element ?>
      <td colspan="2" data-edit class="edit">
        <input type="text" name="name" class="editor" value="" data-tag-name-editor />
        <a class="action" href="#" data-tag-save>Rename Tag</a>
        <a class="action" href="#" data-tag-delete>Delete Tag</a>
        <?php if ($classInfos): ?>
          <?php // Only one of the select element and the sync button will appear ?>
          <?php // in any given case depending on whether the tag already corresponds ?>
          <?php // to an entity ?>
          <select class="action" name="class" data-tag-entity>
            <option value="">Create Entity</option>
            <?php foreach ($sf_data->getRaw('classInfos') as $class => $classInfo): ?>
              <option value="<?php echo $class ?>"><?php echo aHtml::entities($classInfo['singular']) ?></option>
            <?php endforeach ?>
          </select>
          <a class="action" href="#" data-tag-entity-sync>Sync to <span data-tag-entity-type-label>Person</span></a>
        <?php endif ?>
        <a class="action" href="#" data-tag-cancel>Cancel</a>
      </td>
    </tr>
  </tbody>
</table>

<script type="text/javascript">
  <?php if ($classInfos): ?>
    var classInfos = <?php echo json_encode($sf_data->getRaw('classInfos')) ?>;
  <?php endif ?>
  var models = <?php echo json_encode($sf_data->getRaw('models')) ?>;
  var tags = <?php echo json_encode($sf_data->getRaw('tagInfos')) ?>;
  var renameUrl = <?php echo json_encode(url_for('aBetterTagAdmin/rename')) ?>;
  var deleteUrl = <?php echo json_encode(url_for('aBetterTagAdmin/delete')) ?>;
  var createEntityUrl = <?php echo json_encode(url_for('aBetterTagAdmin/createEntity')) ?>;

  var tagAdmin = {
    rebuild: function() {
      var spinner = $('[data-spinner]');
      spinner.show();
      var table = $('[data-table]');
      table.hide();
      var headingRow = table.find('[data-heading-row]');
      var modelNameTemplate = table.find('[data-model-name-template]').clone();
      var dataRowTemplate = table.find('[data-row-template]').clone();
      var modelCountTemplate = dataRowTemplate.find('[data-model-count-template]').clone();
      
      // For DOM performance reasons we'll add the edit controls only as needed
      var editControlsTemplate = dataRowTemplate.find('[data-edit]').clone();
      dataRowTemplate.find('[data-edit]').remove();

      // Mop up sub-templates now that we've cloned them. This way we don't
      // keep cloning them when we make data rows
      dataRowTemplate.find('.template').remove();

      _.each(models, function(model) {
        var heading = modelNameTemplate.clone();
        heading.removeClass('template').text(model.label);
        headingRow.append(heading);
      });

      _.each(tags, function(tag) {
        var dataRow = dataRowTemplate.clone();
        dataRow.attr('data-tag-id', tag.id);
        if (tag.type) {
          dataRow.attr('data-tag-entity-type', tag.type);
        }
        dataRow.attr('data-tag-name', tag.name);
        dataRow.removeClass('template');
        if (tag.name === '')
        {
          dataRow.find('[data-tag-name]').text('[NO NAME]');
        }
        else
        {
          dataRow.find('[data-tag-name]').text(tag.name);
        }
        _.each(models, function(model) {
          var modelCount = modelCountTemplate.clone();
          modelCount.removeClass('template');
          if (_.has(tag, model.name))
          {
            modelCount.text(tag[model.name]);
          }
          dataRow.append(modelCount);
        });
        // We don't want this to get show()n later
        table.find('[data-row-parent]').append(dataRow);
      });

      spinner.hide();
      table.show();

      // EVENT HANDLERS AND PRIVATE FUNCTIONS FOLLOW

      var activeRow;

      // Toggle open the editor for a row
      table.on('click', '[data-row]', function() {
        // Don't interfere with a click on the thing we're already editing
        if (activeRow && ($(this).attr('data-tag-id') === activeRow.attr('data-tag-id')))
        {
          return true;
        }
        hideControls();
        activeRow = $(this);
        showControls();
      });

      function hideControls() {
        if (!activeRow) {
          return;
        }
        activeRow.find('[data-edit]').remove();
        activeRow.find('[data-normal]').show();
        activeRow = undefined;
      }

      function showControls() {
        unhighlight();
        if (!activeRow) {
          return;
        }
        activeRow.find('[data-normal]').hide();
        activeRow.append(editControlsTemplate.clone());
        activeRow.find('[data-tag-name-editor]').val(activeRow.find('[data-tag-name]').text());
        enableControls();
        activeRow.find('[data-edit]').show();
      }

      function enableControls() {
        // If it already has a matching entity present a sync button,
        // otherwise a select element to create one
        if (activeRow.attr('data-tag-entity-type')) {
          activeRow.find('[data-tag-entity-type-label]').text(classInfos[activeRow.attr('data-tag-entity-type')].singular);
          activeRow.find('[data-tag-entity]').hide();
          activeRow.find('[data-tag-entity-sync]').show();
        } else {
          activeRow.find('[data-tag-entity]').show();
          activeRow.find('[data-tag-entity-sync]').hide();
        }
        activeRow.find('[data-tag-save]').click(function() {
          var button = $(this);
          var id = activeRow.attr('data-tag-id');
          var editor = activeRow.find('[data-tag-name-editor]');
          var name = editor.val();
          $.post(renameUrl, { id: id, name: name }, function(data) {
            if (data.status === 'ok') {
              if (data.action === 'merged') {
                activeRow.fadeOut();
                mergeCounts(id, data.to);
                highlight(data.to);
              } else {     
                // Reinsert the renamed row alphabetically by performing a
                // simple insertion sort      
                activeRow.find('[data-tag-name]').text(name);
                activeRow.remove();
                var i;
                var found = false;
                for (i = 0; (i < tags.length); i++) {
                  if (tags[i].name.toLowerCase() > name.toLowerCase()) {
                    var row = findRow(tags[i].id);
                    row.before(activeRow);
                    found = true;
                    break;
                  }
                }
                if (!found) {
                  table.append(activeRow);
                }
                highlight(id);
              }
            }
            hideControls();
          }, 'json');
          return false;
        });

        activeRow.find('[data-tag-delete]').click(function() {
          if (!confirm('Are you sure you want to delete this tag?'))
          {
            return false;
          }
          var button = $(this);
          var id = activeRow.attr('data-tag-id');
          $.post(deleteUrl, { id: id }, function(data) {
            if (data.status === 'ok') {
              activeRow.remove();
            }
          }, 'json');
          return false;
        });

        activeRow.find('[data-tag-cancel]').click(function() {
          hideControls();
          return false;
        });

        activeRow.find('[data-tag-entity]').change(function() {
          var id = activeRow.attr('data-tag-id');
          var className = activeRow.find('[data-tag-entity]').val();
          $.post(createEntityUrl, { id: id, className: className }, function(data) {
            if (data.status === 'ok') {
              activeRow.attr('data-tag-entity-type', className);
              hideControls();
              highlight(id);
            }
          }, 'json');
        });

        activeRow.find('[data-tag-entity-sync]').click(function() {
          var id = activeRow.attr('data-tag-id');
          $.post(createEntityUrl, { id: id }, function(data) {
            hideControls();
            highlight(id);
          }, 'json');
          return false;
        });
      }

      function findRow(id) {
        return table.find('[data-tag-id="' + id + '"]');
      }

      function mergeCounts(from, to) {
        var fromRow = findRow(from);
        var toRow = findRow(to);

        var fromCounts = fromRow.find('[data-model-count]');
        var toCounts = toRow.find('[data-model-count]');
        var i;
        for (i = 0; (i < fromCounts.length); i++) {
          $(toCounts[i]).text(parseInt($(toCounts[i]).text()) + parseInt($(fromCounts[i]).text()));
        }
      }

      var lastHighlight;

      function highlight(id) {
        unhighlight();
        var row = findRow(id);
        row.addClass('highlighted');

        // jquery.scrollTo was failing, putting the row just above
        // the screen, not very useful. This works pretty well
        // and requires no plugins
        var top = Math.max(row.offset().top - 100, 0);
        $('body').scrollTop(top);

        lastHighlight = id;
      }

      function unhighlight() {
        if (lastHighlight) {
          var row = findRow(lastHighlight);
          row.removeClass('highlighted');
        }
      }
    }
  };

  tagAdmin.rebuild();
</script>

