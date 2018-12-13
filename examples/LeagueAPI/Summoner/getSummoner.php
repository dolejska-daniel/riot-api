<?php
/**
 *  This example shows how to fetch summoner information based on summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

$id = 30904166;

//  Make a call to LeagueAPI
try
{
	$s = $api->getSummoner($id);
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
		<p class="lead">Fetching data for summoner with SummonerID: <code><?=$id?></code>.</p>

		<table class="table">
			<thead>
			<tr>
				<th>SummonerID</th>
				<th>AccountID</th>
				<th>Profile icon</th>
				<th>Summoner name</th>
				<th>Summoner level</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?=$s->id?></td>
				<td><?=$s->accountId?></td>
				<td><?=$s->profileIconId?></td>
				<td><?=$s->name?></td>
				<td><?=$s->summonerLevel?></td>
			</tr>
			</tbody>
		</table>
	</body>
</html>
