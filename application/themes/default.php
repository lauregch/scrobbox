<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" > 
	<head>
		<title><?php echo $titre; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= $charset ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?= css_url('default') ?>" />
<?php foreach($css as $url): ?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?= $url ?>" />
<?php endforeach; ?>
<?php foreach($js as $url): ?>
		<script type="text/javascript" src="<?= $url ?>"></script> 
<?php endforeach; ?>
	</head>

	<body>
		<div id="contenu">
			<h1>Scrobbox</h1>
			<?php echo $output; ?>
		</div>

	</body>

</html>