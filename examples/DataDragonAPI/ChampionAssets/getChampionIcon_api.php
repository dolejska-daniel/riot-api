<?php

require __DIR__ . "/../../LeagueAPI/_init.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

$champion = $api->getStaticChampion(150);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Fetching champion splash art for champion:</p>

				<?=DataDragonAPI::getChampionIconO($champion)?>
				<pre>&lt;?=DataDragonAPI::getChampionIconO({<?=get_class($champion)?>})?&gt;</pre>
			</div>
		</div>


		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIconO($champion, [ 'class' => 'img-thumbnail' ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIconO({<?=get_class($champion)?>}, [ 'class' => 'img-thumbnail' ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIconO($champion, [ 'class' => 'rounded', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIconO({<?=get_class($champion)?>}, [ 'class' => 'rounded', ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionIconO($champion, [ 'class' => 'rounded-circle', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionIconO({<?=get_class($champion)?>}, [ 'class' => 'rounded-circle', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
