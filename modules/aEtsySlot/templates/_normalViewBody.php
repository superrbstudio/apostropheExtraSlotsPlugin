<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php $normalViewSelector = "#a-slot-content-container-$pageid-$name-$permid" ?>
<?php if (isset($values['etsy_code'])): ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <script type="text/javascript" charset="utf-8">
      var apostropheSaveDocumentWrite = document.write;
      var apostropheDocumentWriteBuffer = '';
      document.write = function(markup) {
        apostropheDocumentWriteBuffer += markup;
      };
      $(function() {
        jQuery.getScript('http://www.etsy.com/assets/js/etsy_mini_shop.js', function() {
          $('<?php echo $normalViewSelector ?>').append(<?php echo json_encode($values['etsy_code']) ?>);
          $('<?php echo $normalViewSelector ?>').append(apostropheDocumentWriteBuffer);
          document.write = apostropheSaveDocumentWrite;
        });
      });
    </script>
  <?php else: ?>
    <?php echo $values['etsy_code'] ?>
  <?php endif ?>
<?php endif ?>
