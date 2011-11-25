<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php $normalViewSelector = "#a-slot-content-container-$pageid-$name-$permid" ?>
<?php if (isset($values['etsy_code'])): ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <script type="text/javascript" charset="utf-8">
      <?php echo aHtml::ajaxifyEmbedCode($values['etsy_code'], $normalViewSelector) ?>
    </script>
  <?php else: ?>
    <?php echo $values['etsy_code'] ?>
  <?php endif ?>
<?php endif ?>
