<?php use_helper('a') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>
<?php if ($poll): ?>
  <div data-poll-id="<?php echo "$pageid-$name-$permid" ?>">
    <h4><?php echo htmlspecialchars($poll['question']) ?></h4>
    <ul> 
      <?php foreach ($poll['Choices'] as $choice): ?>
        <li data-choice-id="<?php echo $choice->getId() ?>"><a href="#" data-vote><?php echo $choice['name'] ?></a> (<span data-count><?php echo $choice['count'] ?></span>)</li>
      <?php endforeach ?>
    </ul>
    <p data-voted style="display: none" class="voted">Thanks for voting!</p>
  </div>
<?php endif ?>
<?php a_js_call('pp.enablePoll(?)', array('id' => "$pageid-$name-$permid", 'url' => url_for('@a_poll_vote'))) ?>
