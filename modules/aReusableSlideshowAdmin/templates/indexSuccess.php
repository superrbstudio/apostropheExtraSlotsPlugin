<?php $reusables = $sf_data->getRaw('reusables') ?>

<?php slot('a-page-header') ?>
  <div class="a-ui a-admin-header">
    <h3 class="a-admin-title">Reusable Slideshows</h3>
  </div>
<?php end_slot() ?>

<div class="a-admin-content main a-reusable-slideshow-admin">

  <div class="a-admin-list">

    <table class="a-admin-list-table">
      <thead>
        <tr>
          <th class="name-heading">Name</th><th class="home-heading">Home Page</th><th class="reused-heading">Reused On</th>
        </tr>
      </thead>
      <?php foreach ($reusables as $reusable): ?>
        <tr class="a-admin-row">
          <td class="a-admin-text first"><?php echo link_to($reusable['label'], 'aReusableSlideshowAdmin/edit?id=' . $reusable['id']) ?></td>
          <td class="a-admin-text"><a href="<?php echo aTools::urlForPage($reusable['page_info']['slug']) ?>"><?php echo $reusable['page_info']['slug'] ?></a></td>
          <td class="a-admin-text">
            <ul>
              <?php foreach ($reusable['reuses'] as $reuse): ?>
                <li><a href="<?php echo aTools::urlForPage($reuse['page_info']['slug']) ?>"><?php echo $reuse['page_info']['slug'] ?></a></li>
              <?php endforeach ?>
            </ul>
          </td>
        </tr>
      <?php endforeach ?>
    </table>
  </div>
</div>