<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php $normalViewSelector = "#a-slot-content-container-$pageid-$name-$permid" ?>
<?php if (isset($values['wufoo_code'])): ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <script type="text/javascript" charset="utf-8">
      var apostropheSaveDocumentWrite = document.write;
      var apostropheDocumentWriteBuffer = '';
      document.write = function(markup) {
        apostropheDocumentWriteBuffer += markup;
      };
    </script>
  <?php endif ?>
  <?php echo $values['wufoo_code'] ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <script type="text/javascript" charset="utf-8">
      $(function() {
        $('<?php echo $normalViewSelector ?>').append(apostropheDocumentWriteBuffer);
      });
      document.write = apostropheSaveDocumentWrite;
    </script>
  <?php endif ?>
<?php endif ?>
