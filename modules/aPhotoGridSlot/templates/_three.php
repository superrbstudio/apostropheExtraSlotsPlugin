<?php
  // Compatible with sf_escaping_strategy: true
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $title = count($items) > 1 ? __('Click For Next Image', null, 'apostrophe') : false;
?>

<?php if (!count($items)): ?>
	<?php include_partial('aPhotoGridSlot/placeholder', array('columns' => 3, 'boxSize' => $options['gridWidth']/3 )) ?>
<?php endif ?>


<?php foreach ($items as $item): ?>

  <?php $thumbnailDimensions = aDimensions::constrain(
    $item->width,
    $item->height,
    $item->format,
    array(
      "width" =>  $options['gridWidth']/3,
      "height" => $options['gridWidth']/3,
      "resizeType" => 'c'
  )) ?>


  <?php $fullDimensions = aDimensions::constrain(
    $item->width,
    $item->height,
    $item->format,
    array(
      "width" =>  $options['width'],
      "height" => $options['height'],
      "resizeType" => $options['resizeType']
  )) ?>


 <?php $thumbnail = $item->getImgSrcUrl($thumbnailDimensions['width'], $thumbnailDimensions['height'], $thumbnailDimensions['resizeType'], $thumbnailDimensions['format'], false); ?>
 <?php $full = $item->getImgSrcUrl($fullDimensions['width'], $fullDimensions['height'], $fullDimensions['resizeType'], $fullDimensions['format'], false);  ?>
 
 <div class="a-grid-image">
   <a href="<?php echo $full ?>" title="<?php echo $item->title ?>" class="light-box"><img src="<?php echo $thumbnail ?>" /></a>
   <?php /* ?> 
   $item->title
   $item->caption
   $item->description
   <?php //*/ ?>
 </div>
   
<?php endforeach ?>