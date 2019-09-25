<?php
/**
 *  This example shows how to fetch summoner information based on summoner ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

$id = "I am TheKronnY";

try
{
	$summoner = $api->getSummonerByName($id); // accountIds are unique per API key, getByName first is necessary
	$s = $api->getSummonerByAccountId($summoner->accountId);
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
        <p class="lead">Fetching data for summoner with AccountID: <code><?=$summoner->accountId?></code>.</p>

        <table class="table">
            <thead>
            <tr>
	            <th>Key</th>
	            <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr>
	            <th><code>id</code></th>
	            <td><code><?=$s->id?></code></td>
            </tr>
            <tr>
	            <th><code>accountId</code></th>
	            <td><code><?=$s->accountId?></code></td>
            </tr>
            <tr>
	            <th><code>profileIconId</code></th>
	            <td><?=$s->profileIconId?></td>
            </tr>
            <tr>
	            <th><code>name</code></th>
	            <td><?=$s->name?></td>
            </tr>
            <tr>
	            <th><code>summonerLevel</code></th>
	            <td><?=$s->summonerLevel?></td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
