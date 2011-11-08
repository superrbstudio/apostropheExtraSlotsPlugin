<?php use_helper('a') ?>

<?php
// Compatible with sf_escaping_strategy: true
$editable = isset($editable) ? $sf_data->getRaw('editable') : null;
$name = isset($name) ? $sf_data->getRaw('name') : null;
$options = isset($options) ? $sf_data->getRaw('options') : null;
$pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
$permid = isset($permid) ? $sf_data->getRaw('permid') : null;
$slot = isset($slot) ? $sf_data->getRaw('slot') : null;
$values = isset($values) ? $sf_data->getRaw('values') : null
?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'label' => 'Edit Anchor', )) ?>

<?php if (isset($values['title'])): ?>
	
	<?php if ($options['title']): ?>
		<h3 class="a-anchor-title" id="<?php echo $values['anchor'] ?>">
			<?php if ($editable): ?><span class="aAnchor-icon" title="<?php echo $values['title'] ?>">A</span><?php endif ?>
			<?php echo htmlspecialchars($values['title']) ?>
		</h3>
	<?php else: ?>
		<a name="<?php echo $values['anchor'] ?>" id="<?php echo $values['anchor'] ?>" ></a>
		<?php if ($editable): ?>
			<h3 class="logged-in-display-only">
				<span class="aAnchor-icon" title="<?php echo $values['title'] ?>">A</span>
				<?php echo htmlspecialchars($values['title']) ?>
			</h3>				
		<?php endif ?>
	<?php endif ?>
	
<?php endif ?>


