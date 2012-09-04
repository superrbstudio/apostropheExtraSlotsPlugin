<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php $normalViewSelector = "#a-slot-content-container-$pageid-$name-$permid" ?>
<?php if (isset($values['ecwid_code'])): ?>
  <?php $code = $values['ecwid_code'] ?>
  <?php if ($sf_request->isSecure()): ?>
    <?php $code = str_replace('http://app.ecwid.com', 'https://app.ecwid.com', $code) ?>
  <?php endif ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <?php $script = json_encode($sf_request->isSecure() ? 'https://app.ecwid.com/script.js?' . $storeId : 'http://app.ecwid.com/script.js?' . $storeId) ?>
    <script type="text/javascript" charset="utf-8">
      $.getScript(<?php echo $script ?>, function() {
        <?php echo aHtml::ajaxifyEmbedCode($values['ecwid_code'], $normalViewSelector, array('ignoreDynamicScriptSrc' => true)) ?>
      });
    </script>
  <?php else: ?>
    <?php echo $values['ecwid_code'] ?>
  <?php endif ?>
<?php endif ?>
