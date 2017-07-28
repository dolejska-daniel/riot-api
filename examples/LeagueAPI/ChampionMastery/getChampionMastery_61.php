<?php
/**
 *  This example shows how to fetch champion mastery for given
 *    summoner ID and champion ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to RiotAPI
$m = $api->getChampionMastery(30904166, 61);

?>

<table>
	<thead>
	<tr>
		<th>Champion ID</th>
		<th>Mastery level</th>
		<th>Mastery points</th>
		<th>Chest granted?</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?=$m->championId?></td>
		<td><?=$m->championLevel?></td>
		<td><?=$m->championPoints?></td>
		<td><?=$m->chestGranted ? 'Yes' : 'No'?></td>
	</tr>
	</tbody>
</table>
