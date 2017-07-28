<?php
/**
 *  This example shows how to fetch shard status data for
 *    custom region.
 */

//  Include init file
require __DIR__ . "/../_init.php";

use RiotAPI\Definitions\Region;

//  Make a call to RiotAPI
$status = $api->getStatusData(Region::NORTH_AMERICA);

?>

<h1>Status for <?=$status->name?> (<?=$status->region_tag?>: <?=$status->hostname?>)</h1>

<?php foreach ($status->services as $s): ?>

<h2><?=$s->name?></h2>

<p><b>Status: <?=$s->status?></b></p>

<h3>Incidents</h3>
<table>
	<thead>
	<tr>
		<th>Incident ID</th>
		<th>Created at</th>
		<th>Is active?</th>
		<th>Updates</th>
	</tr>
	</thead>
	<tbody>
	<?php if (count($s->incidents)): ?>
		<?php foreach ($s->incidents as $i): ?>
		<tr>
			<td><?=$i->id?></td>
			<td><?=$i->created_at?></td>
			<td><?=$i->active ? 'Yes' : 'No'?></td>
			<td>
			<?php foreach ($i->updates as $u): ?>
				<p><?=$u->author?> at <?=$u->created_at?><br>
					<?=$u->content?></p>
			<?php endforeach; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="4">No recent incidents.</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>

<?php endforeach; ?>