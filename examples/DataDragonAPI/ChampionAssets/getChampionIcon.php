<?php

require __DIR__ . "/../../../vendor/autoload.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

DataDragonAPI::initByCdn();

$key = 'Riven';

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Fetching champion splash art for champion:</p>

				<?=DataDragonAPI::getChampionIcon($key)?>
				<pre>&lt;?=DataDragonAPI::getChampionIcon(<?=$key?>)?&gt;</pre>
			</div>
		</div>


		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIcon($key, [ 'class' => 'img-thumbnail' ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIcon(<?=$key?>, [ 'class' => 'img-thumbnail' ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIcon($key, [ 'class' => 'rounded', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIcon(<?=$key?>, [ 'class' => 'rounded', ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIcon($key, [ 'class' => 'rounded-circle', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIcon(<?=$key?>, [ 'class' => 'rounded-circle', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
