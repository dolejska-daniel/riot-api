<?php
/**
 *  This example shows how to fetch champion mastery entries
 *    for given summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to RiotAPI
$masteries = $api->getChampionMasteries(30904166);

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
