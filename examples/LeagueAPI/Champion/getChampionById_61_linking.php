<?php
/**
 *  This example shows how to fetch current champion information
 *    for one champion with ID 61.
 *
 *  Note: Fields name or title are normally not available with
 *    this request, this is only possible thanks to StaticData
 *    linking feature.
 */

//  Include init file
require __DIR__ . "/../_init.php";

use RiotAPI\LeagueAPI\LeagueAPI;

$custom_api = new LeagueAPI([
	LeagueAPI::SET_KEY                => CFG_API_KEY,
	LeagueAPI::SET_TOURNAMENT_KEY     => CFG_TAPI_KEY,
	LeagueAPI::SET_REGION             => CFG_REGION,
	LeagueAPI::SET_VERIFY_SSL         => CFG_VERIFY_SSL,

	//  This enables static data linking
	LeagueAPI::SET_STATICDATA_LINKING => true,
	LeagueAPI::SET_CACHE_CALLS        => true,
]);

$id = 61;

//  Make a call to LeagueAPI
$ch = $custom_api->getChampionById($id);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching data for champion with ChampionID: <code><?=$id?></code>. Using <b>StaticData linking</b> feature.</p>

		<table class="table">
			<thead>
			<tr>
				<th>ID</th>
				<th>Name & Title</th>
				<th>Is active?</th>
				<th>Is playable in rankeds?</th>
				<th>Is F2P?</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?=$ch->id?></td>
				<td><?=$ch->name . ", " . $ch->title?></td>
				<td><?=$ch->active ? 'Yes' : 'No'?></td>
				<td><?=$ch->rankedPlayEnabled ? 'Yes' : 'No'?></td>
				<td><?=$ch->freeToPlay ? 'Yes' : 'No'?></td>
			</tr>
			</tbody>
		</table>
	</body>
</html>
