<?php // There is no placeholder for singleton slots because we often use singletons for handling content in non-standard places  ?>
<?php // Placeholder is sized automatically to the width of the column ?>
<?php // Clicking the placeholder serves the same purpose as clicking on 'Choose Images' ?>
<?php // The label text is centered vertically and horizontally within the box ?>

<?php $columns = $columns * $columns; ?>
<?php $placeholderText = a_("Choose Images") ?>

<?php if ($sf_user->isAuthenticated() && (isset($options['singleton']) != true)): ?>
	<?php for ($i=0; $i < $columns; $i++): ?>
		<a href="#<?php echo aTools::slugify($placeholderText) ?>" class="a-ui aPhotoGrid a-js-media-placeholder a-photogrid-placeholder col-<?php echo $i ?>">
			<div class ="pusher" style = "width:<?php echo $boxSize ?>px; height:<?php echo $boxSize ?>px">
				<span><?php echo $placeholderText ?></span>
			</div>
		</a>		
	<?php endfor ?>
<?php endif ?>