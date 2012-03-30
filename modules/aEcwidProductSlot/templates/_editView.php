<div class="a-form-row ecwid_code">
	<div class="a-form-field">
			<h4 for="<?php echo $form['ecwid_code']->renderId() ?>" class="a-form-field-label">Ecwid Product HTML Code</h4>
    	<?php echo $form['ecwid_code']->render() ?>
      <?php echo $form['ecwid_code']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['ecwid_code']->renderError() ?></div>
  <?php echo $form->renderHiddenFields() ?>
</div>
