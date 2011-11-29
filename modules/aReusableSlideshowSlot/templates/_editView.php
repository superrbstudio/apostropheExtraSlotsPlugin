<?php echo $form->renderHiddenFields() ?>
<?php echo $form->renderGlobalErrors() ?>
<?php if ($form['label_or_reuse']->getValue() === 'label'): ?>
  <?php $isLabel = 'checked="checked"' ?>
  <?php $isReuse = '' ?>
<?php else: ?>
  <?php $isLabel = '' ?>
  <?php $isReuse = 'checked="checked"' ?>
<?php endif ?>
<?php $formName = 'slot-form-' . $id ?>

<div class="a-form-row a-label-or-reuse-row <?php echo $formName ?>_label_or_reuse">
  <input type="radio" class="a-label-or-reuse a-js-label-or-reuse" name="<?php echo $formName ?>[label_or_reuse]" value="label" <?php echo $isLabel ?>/> Label for Reuse
</div>

<div class="a-form-indent"> 
  <?php echo $form['label']->renderRow(array('class' => 'a-js-label')) ?>
</div>

<div class="a-form-row a-reuse-section <?php echo $formName ?>_label_or_reuse">
  <input type="radio" class="a-label-or-reuse a-js-label-or-reuse" name="<?php echo $formName ?>[label_or_reuse]" value="reuse" <?php echo $isReuse ?>/> Reuse Another Slideshow
</div>

<div class="a-form-indent"> 
  <?php echo $form['reuse']->renderRow(array('class' => 'a-js-reuse')) ?>
</div>

<?php a_js_call('aExtraSlots.setupReusableSlideshowSlot(?)', array('id' => 'a-slot-form-' . $id)) ?>