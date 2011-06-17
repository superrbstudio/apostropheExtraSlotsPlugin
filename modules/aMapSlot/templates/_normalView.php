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

<?php if ($editable && !$options['address']): ?>
	<?php (isset($options['width']) && $options['width'])?  $width = $options['width'] .'px;': $width = '100%;'; ?>
	<?php (isset($options['height']) && $options['height'])? $height = $options['height'].'px;' : $height = (($options['width']) ? floor($options['width']*.56):'100px;'); ?>		
	<?php $style = 'width:'.$width.' height:'.$height ?>
	<div class="a-media-placeholder" style="<?php echo $style ?>">
		<span style="line-height:<?php echo $height ?>px;">Click edit to setup a map</span>
	</div>
<?php endif ?>

<?php if ($options['address']): ?>

<?php if ($options['title']): ?>
	<h3 class="a-map-title"><?php echo $options['title']?></h3>	
<?php endif ?>

<div class="a-map" id="a-map-<?php echo "$pageid-$name-$permid" ?>" style="width:<?php echo $options['width'] ?>px;height:<?php echo $options['height'] ?>px;">
	<span class="a-map-address"><?php echo ($options['address']) ? $options['address'] : '' ?></span>
</div>

<?php a_js_call('aMapSlot.createGoogleMap(?)', array(
	'title' => $options['title'], 
	'longitude' => $options['longitude'],
	'latitude' => $options['latitude'], 
	'zoom' => $options['zoom'], 
	'controls' => (($options['controls']) ? array('pan' => $options['controls']['pan'], 'zoom' => $options['controls']['zoom'], 'scale' => $options['controls']['scale']) : false), 
	'container' => '#a-map-'.$pageid.'-'.$name.'-'.$permid,
)) ?>	

<?php endif ?>
