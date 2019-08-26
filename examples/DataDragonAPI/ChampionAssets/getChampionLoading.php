<?php

require __DIR__ . "/../../../vendor/autoload.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

DataDragonAPI::initByCdn();

$key = 'Gragas';

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Fetching champion splash art for champion:</p>

				<?=DataDragonAPI::getChampionLoading($key)?>
				<pre>&lt;?=DataDragonAPI::getChampionLoading(<?=$key?>)?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<p class="lead">Fetching champion splash art for champion:</p>

				<?=DataDragonAPI::getChampionLoading($key, 9)?>
				<pre>&lt;?=DataDragonAPI::getChampionLoading(<?=$key?>, 9)?&gt;</pre>
			</div>
		</div>


		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionLoading($key, 0, [ 'class' => 'img-thumbnail' ])?>
				<pre>&lt;?=DataDragonAPI::getChampionLoading(<?=$key?>, 0, [ 'class' => 'img-thumbnail' ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionLoading($key, 0, [ 'class' => 'rounded', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionLoading(<?=$key?>, 0, [ 'class' => 'rounded', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
