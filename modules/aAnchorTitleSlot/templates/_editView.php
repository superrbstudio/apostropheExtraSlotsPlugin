<?php use_helper('a') ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $id = isset($id) ? $sf_data->getRaw('id') : null;
	$options = isset($options) ? $sf_data->getRaw('options') : null;
	// If we are not displaying the title, let's just soft-rename the lable for the field
	$label = $options['title'] ? a_('Title') : a_('Anchor Name');
?>

<h4 class="a-slot-form-title"><span class="aAnchor-icon">A</span><?php echo a_('Anchored Title') ?></h4>

<div class="a-form-row a-hidden">
<?php echo $form->renderHiddenFields() ?>
</div>

<?php if (!$options['title']): ?>
<div class="a-form-row help">	
	<div class="a-help"><?php echo a_('In-line Anchor will not be displayed when logged out.') ?></div>
</div>
<?php endif ?>

<div class="a-form-row title">
	<?php echo $form['title']->renderLabel($label) ?>
	<?php echo $form['title']->render() ?>
	<?php echo $form['title']->renderError() ?>
</div>

<?php if (isset($values['anchor'])): ?>
	<div class="a-form-row anchor">
	  <label for="anchor-value"><?php echo a_('Anchor') ?></label>
	  <div class="a-form-field">
			<input class="aAnchor-anchor a-disabled" type="text" name="anchor-value" value="#<?php echo $values['anchor'] ?>" readonly="readonly">
		</div>  
	</div>	
<?php endif ?>

<div class="a-form-row help">
	<div class="a-help">
		<?php echo a_('Add as many anchored titles to the page as you would like.<br/> The Anchored Title slot works the Anchored Navigation component. The component generates a navigation list that jumps to each title. The titles must be unique in order for the named anchors to work correctly.') ?>
	</div>
</div>

<?php a_js_call('apostrophe.slotEnhancements(?)', array('slot' => '#a-slot-'.$pageid.'-'.$name.'-'.$permid, 'editClass' => 'a-options')) ?>