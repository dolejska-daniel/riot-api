<?php
/**
 *  This example shows how to fetch current champion information
 *    for one champion with ID 61.
 */

//  Include init file
require __DIR__ . "/../_init.php";

$id = 61;

//  Make a call to LeagueAPI
$ch = $api->getChampionById($id);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching data for champion with ChampionID: <code><?=$id?></code>.</p>

		<table class="table">
			<thead>
			<tr>
				<th>ID</th>
				<th>Is active?</th>
				<th>Is playable in rankeds?</th>
				<th>Is F2P?</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?=$ch->id?></td>
				<td><?=$ch->active ? 'Yes' : 'No'?></td>
				<td><?=$ch->rankedPlayEnabled ? 'Yes' : 'No'?></td>
				<td><?=$ch->freeToPlay ? 'Yes' : 'No'?></td>
			</tr>
			</tbody>
		</table>
	</body>
</html>
