<?php use_helper('a') ?>
<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php if (isset($values['embed_code'])): ?>
  <?php echo str_replace('_WIDTH_', $width, $values['embed_code']) ?>
<?php endif ?>
