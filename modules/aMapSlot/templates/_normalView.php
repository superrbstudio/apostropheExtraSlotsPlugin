<?php
  // Compatible with sf_escaping_strategy: true
  $dimensions = isset($dimensions) ? $sf_data->getRaw('dimensions') : null;
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $map = isset($map) ? $sf_data->getRaw('map') : null;
?>
<?php use_helper('a') ?>

<?php if ($editable): ?>
  <?php slot("a-slot-controls-$pageid-$name-$permid") ?>
		<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'controlsSlot' => false, 'label' => a_get_option($options, 'editLabel', a_('Edit')))) ?>
  <?php end_slot() ?>
<?php endif ?>

<h3><?php echo ($map['title']) ? $map['title'] : 'Map' ?></h3>

<div class="a-map" id="a-map-<?php echo "$pageid-$name-$permid" ?>" style="width:<?php echo $options['width'] ?>px;height:<?php echo $options['height'] ?>px;">
	<?php echo ($map['address']) ? $map['address'] : 'Map Address' ?>
</div>

<div class="a-map-lng-lat">
<?php echo $map['longitude'] ?>, <?php echo $map['latitude'] ?>
</div>

<?php a_js_call('aMapSlot.createGoogleMap(?)', array(
	'longitude' => $map['longitude'],
	'latitude' => $map['latitude'], 
	'zoom' => $options['zoom'], 
	'container' => '#a-map-'.$pageid.'-'.$name.'-'.$permid,
)) ?>