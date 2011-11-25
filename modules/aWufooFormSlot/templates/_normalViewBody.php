<?php use_helper('a') ?>

<?php $values = $sf_data->getRaw('values') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php $normalViewSelector = "#a-slot-content-container-$pageid-$name-$permid" ?>
<?php if (isset($values['wufoo_code'])): ?>
  <?php if ($sf_request->isXmlHttpRequest()): ?>
    <?php $script = json_encode($sf_request->isSecure() ? 'https://secure.wufoo.com/scripts/embed/form.js' : 'http://wufoo.com/scripts/embed/form.js') ?>
    <script type="text/javascript" charset="utf-8">
      $.getScript(<?php echo $script ?>, function() {
        <?php echo aHtml::ajaxifyEmbedCode($values['wufoo_code'], $normalViewSelector, array('ignoreDynamicScriptSrc' => true)) ?>
      });
    </script>
  <?php else: ?>
    <?php echo $values['wufoo_code'] ?>
  <?php endif ?>
<?php endif ?>
