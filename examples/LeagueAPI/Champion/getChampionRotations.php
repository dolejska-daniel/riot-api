<?php
/**
 *  This example shows how to fetch current champion rotations.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to LeagueAPI
$rotations = $api->getChampionRotations();

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching champion rotations data.</p>

		<table class="table">
			<caption style="caption-side: top">Free champions for new players (level <= <?=$rotations->maxNewPlayerLevel?>)</caption>
			<thead>
			<tr>
				<th>ID</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($rotations->freeChampionIdsForNewPlayers as $championId): ?>
				<tr>
					<td><?=$championId?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<table class="table">
			<caption style="caption-side: top">Free champions</caption>
			<thead>
			<tr>
				<th>ID</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($rotations->freeChampionIds as $championId): ?>
				<tr>
					<td><?=$championId?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>
