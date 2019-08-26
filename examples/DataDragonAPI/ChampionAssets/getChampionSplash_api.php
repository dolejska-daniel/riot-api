<?php

require __DIR__ . "/../../LeagueAPI/_init.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

$champion = $api->getStaticChampion(61);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching champion splash art for champion:</p>

		<?=DataDragonAPI::getChampionSplashO($champion)?>
		<pre>&lt;?=DataDragonAPI::getChampionSplashO({<?=get_class($champion)?>})?&gt;</pre>

		<p class="lead">Fetching champion splash art for champion:</p>

		<?=DataDragonAPI::getChampionSplashO($champion, 7)?>
		<pre>&lt;?=DataDragonAPI::getChampionSplashO({<?=get_class($champion)?>}, 7)?&gt;</pre>

		<p class="lead">With additional classes:</p>

		<div class="row">
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionSplashO($champion, 0, [ 'class' => 'img-thumbnail' ])?>
				<pre>&lt;?=DataDragonAPI::getChampionSplashO({<?=get_class($champion)?>}, 0, [ 'class' => 'img-thumbnail' ])?&gt;</pre>
			</div>
			<div class="col-sm-6">
				<?=DataDragonAPI::getChampionSplashO($champion, 0, [ 'class' => 'rounded w-100', ])?>
				<pre>&lt;?=DataDragonAPI::getChampionSplashO({<?=get_class($champion)?>}, 0, [ 'class' => 'rounded w-100', ])?&gt;</pre>
			</div>
		</div>
	</body>
</html>
