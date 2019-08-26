<?php

require __DIR__ . "/../../../vendor/autoload.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

DataDragonAPI::initByCdn();

$key = 'Velkoz';

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching champion splash art for champion:</p>

		<?=DataDragonAPI::getChampionSplash($key)?>
		<pre>&lt;?=DataDragonAPI::getChampionSplash(<?=$key?>)?&gt;</pre>

		<p class="lead">Fetching champion splash art for champion:</p>

		<?=DataDragonAPI::getChampionSplash($key, 2)?>
		<pre>&lt;?=DataDragonAPI::getChampionSplash(<?=$key?>, 2)?&gt;</pre>

		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionSplash($key, 0, [ 'class' => 'img-thumbnail' ])?>
				<pre>&lt;?=DataDragonAPI::getChampionSplash(<?=$key?>, 0, [ 'class' => 'img-thumbnail' ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionSplash($key, 0, [ 'class' => 'rounded w-100', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionSplash(<?=$key?>, 0, [ 'class' => 'rounded w-100', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
