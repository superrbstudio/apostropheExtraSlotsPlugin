<?php // We are just using the aBlogSlot edit view here because it works. ?>
<?php // No need to duplicate code. ?>

<?php use_helper('a') ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
  $id = isset($id) ? $sf_data->getRaw('id') : null;
?>

<?php include_partial('aBlogSlot/editView', array('form' => $form, 'popularTags' => $popularTags, 'allTags' => $allTags, 'id' => $id)) ?>