<?php use_helper('a') ?>
<?php slot('a-page-header') ?>
  <div class="a-ui a-admin-header">
    <h3 class="a-admin-title">Reusable Slideshow: <?php echo $reusableSlot['label'] ?></h3>
  </div>
<?php end_slot() ?>

<div class="a-ui a-reusable-slideshow-admin-edit">
  <form action="<?php echo url_for('@reusable_slideshow_admin_edit?id=' . $reusableSlot['id']) ?>" method="POST">
    <?php echo $form ?>
    <ul class="a-ui a-controls clearfix">
      <li><?php echo a_anchor_submit_button('Change Label') ?></li>
      <li><?php echo a_link_button('Cancel', '@reusable_slideshow_admin_index', array(), array('icon', 'a-cancel', 'alt')) ?>
    </ul>
  </form>

  <?php // Please don't put options here that don't exist in the plugin ?>
  <?php $slideshowOptions = array(
  	'width' => 560,
  	'maxHeight' => 400,
  	'flexHeight' => false,
  	'arrows' => true,
  	'title' => false,
  	'slideshowLabel' => true,
  	'description' => true
  	) ?>

<?php // Set the current page so we can render the slideshow ?>
  <?php aTools::setCurrentPage(aPageTable::retrieveByIdWithSlots($reusableSlot['page_id'])) ?>

  <?php // Simulate the usual stack of divs so the CSS applies ?>
  <div class="a-slot a-normal aReusableSlideshow a-reusable-slideshow-preview">
    <div class="a-slot-content clearfix">
      <div class="a-slot-content-container a-normal-view">
        <?php include_component('aReusableSlideshowSlot', 'normalView', array('name' => $reusableSlot['area_name'], 'permid' => $reusableSlot['permid'], 'type' => 'aReusableSlideshow', 'options' => $slideshowOptions)) ?>
      </div>
    </div>
  </div>

  <?php // Clear the current page so we don't get its dressing / navigation / etc. ?>
  <?php aTools::setCurrentPage(null) ?>

</div>