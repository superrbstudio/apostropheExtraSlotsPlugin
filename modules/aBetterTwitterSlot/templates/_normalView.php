<?php use_helper('a') ?>

<?php
// Compatible with sf_escaping_strategy: true
$editable = isset($editable) ? $sf_data->getRaw('editable') : null;
$name = isset($name) ? $sf_data->getRaw('name') : null;
$options = isset($options) ? $sf_data->getRaw('options') : null;
$pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
$permid = isset($permid) ? $sf_data->getRaw('permid') : null;
$slot = isset($slot) ? $sf_data->getRaw('slot') : null;
$values = isset($values) ? $sf_data->getRaw('values') : null
?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>

<div class="a-tweet-container tweet" id="a-tweet-container-<?php echo $pageid ?>-<?php echo $name ?>-<?php echo $permid ?>"></div>

<?php a_js_call('$("#a-tweet-container-'.$pageid.'-'.$name.'-'.$permid.'").tweet(?)', array(
  'join_text' => $options['join_text'],

  'avatar_size' => ($options['avatar_size']) ? $options['avatar_size'] :                // Template settings beat user settings
                   (isset($values) && !empty($values['avatar'])) ? $values['avatar'] :  // User settings beat defaults
                   32,                                                                  // Default setting

  'count' => ($options['count']) ? $options['count'] :                                  // Template settings beat user settings
             (isset($values) && !empty($values['count'])) ? $values['count'] :          // User settings beat defaults
             6,                                                                         // Default setting

  'query' => ($options['query']) ? $options['query'] :                                  // Template settings beat user settings
             (isset($values) && !empty($values['search'])) ? $values['search'] :        // User settings beat defaults
             '@apostrophenow',                                                          // Default setting

  'auto_join_text_default' => $options['auto_join_text_default'],
  'auto_join_text_ed' => $options['auto_join_text_ed'],
  'auto_join_text_ing' => $options['auto_join_text_ing'],
  'auto_join_text_reply' => $options['auto_join_text_reply'],
  'auto_join_text_url' => $options['auto_join_text_url'],
  'loading_text' => $options['loading_text'],
)) ?>