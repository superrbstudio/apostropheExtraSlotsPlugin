<?php use_helper('a') ?>

<h4><?php echo a_('Better Twitter Slot') ?></h4>

<div class="a-form-row a-hidden">
	<?php echo $form->renderHiddenFields() ?>
</div>

<div class="a-form-row search">
  <?php echo $form['search']->renderLabel() ?>
  <div class="a-form-field">
    <?php echo $form['search']->render() ?>  
    <?php echo $form['search']->renderHelp() ?>
  </div>
  <?php echo $form['search']->renderError() ?>  
</div>

<div class="a-form-row avatar">
  <?php echo $form['avatar']->renderLabel() ?>
  <div class="a-form-field">
    <div class="a-form-field-wrapper"><?php echo $form['avatar']->render() ?>px</div>
    <?php echo $form['avatar']->renderHelp() ?>
    <div class="a-twitter-avatar-example">
      Example (<?php echo $form['avatar']->getValue() ?>x<?php echo $form['avatar']->getValue() ?>): <b class="a-twitter-avatar-example-badge" style="height:<?php echo $form['avatar']->getValue() ?>px;width:<?php echo $form['avatar']->getValue() ?>px;"></b>
    </div>
  </div>
  <?php echo $form['avatar']->renderError() ?>  
</div>

<div class="a-form-row count">
  <?php echo $form['count']->renderLabel() ?>
  <div class="a-form-field">
    <?php echo $form['count']->render() ?>  
    <?php echo $form['count']->renderHelp() ?>
  </div>
  <?php echo $form['avatar']->renderError() ?>  
</div>

<?php a_js_call('apostrophe.slotEnhancements(?)', array('slot' => '#a-slot-'.$pageid.'-'.$name.'-'.$permid, 'editClass' => 'a-options')) ?>