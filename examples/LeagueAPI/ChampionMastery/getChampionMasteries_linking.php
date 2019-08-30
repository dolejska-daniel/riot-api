<?php
/**
 *  This example shows how to fetch champion mastery entries
 *    for given summoner ID with active StaticData linking.
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

$summoner = $custom_api->getSummonerByName("I am TheKronnY");
$masteries = $custom_api->getChampionMasteries($summoner->id);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching mastery data for summoner with SummonerID: <code><?=$summoner?></code>. Using <b>StaticData linking</b> feature.</p>

		<table class="table">
			<thead>
			<tr>
				<th>Champion ID</th>
				<th>Champion name</th>
				<th>Mastery level</th>
				<th>Mastery points</th>
				<th>Chest granted?</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($masteries as $m): ?>
				<tr>
					<td><?=$m->championId?></td>
					<td><?=$m->name . ", " . $m->title?></td>
					<td><?=$m->championLevel?></td>
					<td><?=$m->championPoints?></td>
					<td><?=$m->chestGranted ? 'Yes' : 'No'?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>
