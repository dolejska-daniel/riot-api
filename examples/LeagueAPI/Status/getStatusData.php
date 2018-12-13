<?php
/**
 *  This example shows how to fetch shard status data for
 *    active region.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to LeagueAPI
$status = $api->getStatusData();

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<h1>Status for <?=$status->name?> (<?=$status->region_tag?>: <?=$status->hostname?>)</h1>

		<?php foreach ($status->services as $s): ?>

			<h2><?=$s->name?></h2>

			<p><b>Status</b>: <b class="text-<?=($s->status == "online" ? "success" : ($s->status == "offline" ? "danger" : "warning"))?>"><?=$s->status?></b></p>

			<h3>Incidents</h3>
			<table class="table">
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
	</body>
</html>


