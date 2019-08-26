<?php

require __DIR__ . "/../../../vendor/autoload.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

DataDragonAPI::initByCdn();

$id = 1;

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching profile icon with ProfileIconID:</p>

		<?=DataDragonAPI::getProfileIcon($id)?>

		<pre>&lt;?=DataDragonAPI::getProfileIcon(<?=$id?>)?&gt;</pre>

		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'img-thumbnail', ])?>
				<pre>&lt;?=DataDragonAPI::getProfileIcon(<?=$id?>, 0, [ 'class' => 'img-thumbnail', ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'rounded', ])?>
				<pre>&lt;?=DataDragonAPI::getProfileIcon(<?=$id?>, 0, [ 'class' => 'rounded', ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'rounded-circle', ])?>
				<pre>&lt;?=DataDragonAPI::getProfileIcon(<?=$id?>, 0, [ 'class' => 'rounded-circle', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
