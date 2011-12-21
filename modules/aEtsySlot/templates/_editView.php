<?php // Just echo the form. You might want to render the form fields differently ?>
<?php // echo $form ?>

<div class="a-form-row etsy_code">
	<div class="a-form-field">
			<h4 for="<?php echo $form['etsy_code']->renderId() ?>" class="a-form-field-label">Etsy Mini Code</h4>
    	<?php echo $form['etsy_code']->render() ?>
      <?php echo $form['etsy_code']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['etsy_code']->renderError() ?></div>
  <?php echo $form->renderHiddenFields() ?>
</div>
