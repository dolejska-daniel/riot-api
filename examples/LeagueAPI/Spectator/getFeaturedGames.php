<?php
/**
 *  This example shows how to fetch summoner information based on summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

use RiotAPI\LeagueAPI\Exceptions\RequestException;

try
{
	$featured = $api->getFeaturedGames();
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
		<p class="lead">Fetching currently featured games info:</p>

		<?php foreach ($featured->gameList as $g): ?>
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
		<?php endforeach; ?>
	</body>
</html>
