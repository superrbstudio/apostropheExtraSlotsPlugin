<?php // We are just using the aBlogSlot normal view here because it works. ?>
<?php // No need to duplicate code. ?>

<?php use_helper('a') ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $aBlogPosts = isset($aBlogPosts) ? $sf_data->getRaw('aBlogPosts') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $title = count($aBlogPosts) > 1 ? __('Click For Next Post', null, 'apostrophe') : false;
  $id = isset($id) ? $sf_data->getRaw('id') : null;
	$id = ($options['idSuffix']) ? $id.'-'.$options['idSuffix']:$id;
?>

<div class="a-blog-sideshow" id="a-blog-slideshow-<?php echo $id ?>">
	<?php include_partial('aBlogSlot/normalView', array('aBlogPosts' => $aBlogPosts, 'name' => $name, 'options' => $options, 'page' => $page, 'permid' => $permid, 'slot' => $slot, 'editable' => $editable)) ?>
</div>

<?php if ($options['arrows'] && (count($aBlogPosts) > 1)): ?>
<ul id="a-blog-slideshow-controls-<?php echo $id ?>" class="a-slideshow-controls blog">
	<li class="a-arrow-btn icon a-arrow-left alt"><span class="icon"></span><?php echo __('Previous', null, 'apostrophe') ?></li>
	<?php if ($options['position']): ?>
		<li class="a-slideshow-position">
			<span class="a-slideshow-position-head">1</span> of <span class="a-slideshow-position-total"><?php echo count($aBlogPosts); ?></span>
		</li>
	<?php endif ?>
	<li class="a-arrow-btn icon a-arrow-right alt"><span class="icon"></span><?php echo __('Next', null, 'apostrophe') ?></li>
</ul>
<?php endif ?>

<?php a_js_call('apostrophe.slideshowSlot(?)', array(
	'debug' => true,
	'slideshowSelector' => '#a-blog-slideshow-'.$id,
	'slideshowItemsSelector' => '.a-blog-item', 
	'id' => $id,
	'position' => $options['position'], 
	'interval' => $options['interval'],  
	'transition' => $options['transition'], 
	'duration' => $options['duration'], 
	'title' => $title,
)) ?>