<?php
/**
 *  This example shows how to fetch current champion information
 *    for all champions with active StaticData linking.
 *
 *  This request may take a while, but will also fetch champion
 *    names. Fetching StaticData with special tags is not yet
 *    available.
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

//  Make a call to LeagueAPI
$champs = $custom_api->getChampions();

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching champion data. Using <b>StaticData linking</b> feature.</p>

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
			<?php foreach ($champs as $ch): ?>
				<tr>
					<td><?=$ch->id?></td>
					<td><?=$ch->name . ", " . $ch->title?></td>
					<td><?=$ch->active ? 'Yes' : 'No'?></td>
					<td><?=$ch->rankedPlayEnabled ? 'Yes' : 'No'?></td>
					<td><?=$ch->freeToPlay ? 'Yes' : 'No'?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>
