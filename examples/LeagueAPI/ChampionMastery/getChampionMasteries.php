<?php
/**
 *  This example shows how to fetch champion mastery entries
 *    for given summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

$summoner = $api->getSummonerByName("I am TheKronnY");
$masteries = $api->getChampionMasteries($summoner->id);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching mastery data for summoner with SummonerID: <code><?=$summoner?></code>.</p>

		<table class="table">
			<thead>
			<tr>
				<th>Champion ID</th>
				<th>Mastery level</th>
				<th>Mastery points</th>
				<th>Chest granted?</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($masteries as $m): ?>
				<tr>
					<td><?=$m->championId?></td>
					<td><?=$m->championLevel?></td>
					<td><?=$m->championPoints?></td>
					<td><?=$m->chestGranted ? 'Yes' : 'No'?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>
