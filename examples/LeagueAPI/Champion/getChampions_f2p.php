<?php
/**
 *  This example shows how to fetch current champion information
 *    for currently free to play champions.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to RiotAPI
$champs = $api->getChampions(true);

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
	<?php foreach ($champs as $ch): ?>
		<tr>
			<td><?=$ch->id?></td>
			<td><?=$ch->active ? 'Yes' : 'No'?></td>
			<td><?=$ch->rankedPlayEnabled ? 'Yes' : 'No'?></td>
			<td><?=$ch->freeToPlay ? 'Yes' : 'No'?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
