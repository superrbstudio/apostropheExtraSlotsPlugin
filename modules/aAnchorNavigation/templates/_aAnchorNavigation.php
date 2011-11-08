<?php $navigation = isset($navigation) ? $sf_data->getRaw('navigation') : null; ?>

<ul class="a-nav a-nav-<?php echo $name ?> inline-anchors clearfix" id="a-anchored-nav-<?php echo $name ?>">

  <?php $n=0; foreach($navigation as $item): ?>
    <li class="a-nav-item <?php if($n == 0) echo ' first';
        if($n == 1) echo ' second';
        if($n == count($navigation) - 2) echo ' next-last';
        if($n == count($navigation)-1) echo ' last'
    ?>" id="a-nav-item-<?php echo $name ?>-<?php echo $item['anchor']?>">

			<a href="#<?php echo $item['anchor'] ?>"><?php echo $item['title'] ?></a>
    </li>
  <?php $n++; endforeach ?>
  
</ul>

<?php a_js_call('aExtraSlots.setupAAnchorNavigation(?)', array('navId' => 'a-anchored-nav-'.$name)) ?>