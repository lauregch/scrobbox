
<script type="text/javascript">
	function love( b, id )
	{
		// document.forms["myform"].submit();
		// alert("Value is sumitted");

		$.post(	"<?= site_url('history/_ajax_love_track') ?>",
				{ love_b : b, track_id : id },
				function( response ) {}
			);
	}
</script>


<?php if ( count($scrobbles) == 0 ) : ?>
	<?= $username ?> did not scrobble any song yet.
<?php endif; ?>


<?php foreach ($scrobbles as $s) : ?>

<?php
	$id			= $s['id'];
	$track      = $s['track'];
	$artist     = $s['artist'];
	$date       = $s['date'];
	$artist_url = $s['artist_url'];
	$track_url  = $s['track_url'];
	$track_icon = $s['track_icon'];
	$loved		= $s['track_loved'];
?>

	<div class="scrobble">
		<div class="pic">
			<?= img($track_icon) ?>
		</div>
		<div class="song">
			<a href="<?=$artist_url?>"><?=$artist?></a> - <a href="<?=$track_url?>"><?=$track?></a>
			<?= time_ago( $date ) ?>
			<a href="javascript:love(<?= $loved==1?0:1 ?>, <?=$id?>)"><?= $loved==1 ? 'unlove' : 'love' ?></a>
			<a href="./delete/<?=$id?>">remove</a>
		</div>
	</div>

<?php endforeach; ?>
