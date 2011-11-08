<?php use_helper('a') ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $dimensions = isset($dimensions) ? $sf_data->getRaw('dimensions') : null;
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $item = isset($item) ? $sf_data->getRaw('item') : null;
  $itemId = isset($itemId) ? $sf_data->getRaw('itemId') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $embed = isset($embed) ? $sf_data->getRaw('embed') : null;
?>

<?php // Edit Controls ?>
<?php if ($editable): ?>
  <?php slot("a-slot-controls-$pageid-$name-$permid") ?>
			<?php include_partial('a/simpleEditWithVariants', array(
				'pageid' => $page->id, 
				'name' => $name, 
				'permid' => $permid, 
				'slot' => $slot, 
				'page' => $page, 
				'controlsSlot' => false
			)) ?>
  	<li class="a-controls-item choose-image">
  	  <?php include_partial('aImageSlot/choose', array(
				'action' => 'aContactSlot/image', 
				'buttonLabel' => __('Choose image', null, 'apostrophe'), 
				'label' => __('Select an Image', null, 'apostrophe'), 
				'class' => 'a-btn icon a-media', 
				'type' => 'image', 
				'constraints' => $options['constraints'], 
				'itemId' => $itemId, 
				'name' => $name, 
				'slug' => $slug, 
				'permid' => $permid)) ?>
  	</li>
<?php end_slot() ?>
<?php endif ?>


<?php // Slot Markup ?>
<div id="a-contact-<?php echo $pageid.'-'.$name.'-'.$permid; ?>" class="a-contact">

	<?php if ($editable && !$options['description']): ?>
	  <div class="a-contact-help-text"><?php echo __('Choose an image and edit your contact information.', null, 'apostrophe') ?></div>
	<?php endif ?>
	
	<?php if ($item && $embed): ?>
		<?php // contact-slot image ?>
	   <div class="a-contact-image">
	 		<?php echo $embed ?>
	   </div>
	<?php endif ?>
				
	<?php if ($options['description']): ?>
		<div class="a-contact-description">
			<?php echo $options['description'] ?>
		</div> 
	<?php endif ?>

</div>