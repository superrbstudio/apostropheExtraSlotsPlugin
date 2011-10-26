<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>
<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php if (isset($values['button_code'])): ?>
  <?php echo $values['button_code'] ?>
<?php endif ?>
