<?php use_helper('I18N') ?>

<div class="a-form-row a-hidden">
	<?php echo $form->renderHiddenFields() ?>
</div>

<div class="a-form-row">
	<?php echo $form['description']->renderLabel('Description') ?>
	<div class="a-form-field">
		<?php echo $form['description']->render() ?>
	</div>
	<?php echo $form['description']->renderError() ?>
</div>

<?php a_js_call('apostrophe.slotEnhancements(?)', array('slot' => '#a-slot-'.$pageid.'-'.$name.'-'.$permid, 'editClass' => 'a-options')) ?>