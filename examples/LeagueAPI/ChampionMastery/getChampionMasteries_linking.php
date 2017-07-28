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

use RiotAPI\RiotAPI;

$custom_api = new RiotAPI([
	RiotAPI::SET_KEY                => CFG_API_KEY,
	RiotAPI::SET_TOURNAMENT_KEY     => CFG_TAPI_KEY,
	RiotAPI::SET_REGION             => CFG_REGION,
	RiotAPI::SET_VERIFY_SSL         => CFG_VERIFY_SSL,

	//  This enables static data linking
	RiotAPI::SET_STATICDATA_LINKING => true,
]);

//  Make a call to RiotAPI
$masteries = $custom_api->getChampionMasteries(30904166);

?>

<table>
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
