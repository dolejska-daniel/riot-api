<?php
/**
 *  This example shows how to fetch summoner information based on summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

use RiotAPI\LeagueAPI\Exceptions\RequestException;

$id = "I am TheKronnY";

try
{
	$summoner = $api->getSummonerByName($id); // summonerIds are unique per API key, getByName first is necessary
	$g = $api->getCurrentGameInfo($summoner->id);
}
catch (RequestException $ex)
{
	if ($ex->getCode() == 404)
		die("Player not currently in-game.");

	die("Request failed to be processed: " . $ex->getMessage());
}
catch (Exception $ex)
{
	die("Request failed to be processed: " . $ex->getMessage());
}

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching current game info for summoner: <code><?=$id?></code>.</p>

		<table class="table">
			<thead>
			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th><code>gameId</code></th>
				<td><code><?=$g->gameId?></code></td>
			</tr>
			<tr>
				<th><code>gameLength</code></th>
				<td><code><?=$g->gameLength?></code></td>
			</tr>
			<tr>
				<th><code>participants</code></th>
				<td>
					<ul>
						<?php foreach ($g->participants as $p): ?>
						<li><?=$p->summonerName?> (team <code><?=$p->teamId?></code>)</li>
						<?php endforeach; ?>
					</ul>
				</td>
			</tr>
			</tbody>
		</table>
	</body>
</html>
