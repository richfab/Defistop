<div id="Gallery">
	<ul class="clearing-thumbs" data-clearing>
		<?php foreach ($positions as $position): ?>
			
			<li><a href="/defistop/img/photos/<?php echo $position['Position']['photo_url']; ?>"><img data-caption="<?php echo $position['User']['team_name']; ?> - <?php echo $position['Position']['commentaire']; ?>" src="/defistop/img/photos/S_<?php echo $position['Position']['photo_url']; ?>"></a></li>
			
		<?php endforeach; ?>
	</ul>
	<p>
		<?php echo $this->Paginator->numbers();	?>
	</p>
	<?php if(empty($positions)): ?>
	<a>Aucune photo</a>
	<?php endif; ?>

</div>