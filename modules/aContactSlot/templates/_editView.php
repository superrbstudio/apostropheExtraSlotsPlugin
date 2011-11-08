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

<script type="text/javascript" charset="utf-8">
window.apostrophe.registerOnSubmit("<?php echo $id ?>", 
  function(slotId)
  {
    <?php # FCK doesn't do this automatically on an AJAX "form" submit on every major browser ?>
    var value = FCKeditorAPI.GetInstance('slotform-<?php echo $id ?>-description').GetXHTML();
    $('#slotform-<?php echo $id ?>-description').val(value);
  }
);
</script>