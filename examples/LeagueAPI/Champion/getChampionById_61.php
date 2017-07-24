<?php
/**
 *  This example shows how to fetch current champion information
 *    for one champion with ID 61.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to RiotAPI
$ch = $api->getChampionById(61);

?>

<table>
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
