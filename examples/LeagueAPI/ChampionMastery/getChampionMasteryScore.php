<?php
/**
 *  This example shows how to fetch champion mastery score
 *    for given summoner ID and champion ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

$summoner = $api->getSummonerByName("I am TheKronnY");
$score = $api->getChampionMasteryScore($summoner->id);

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Champion mastery score for summoner <code><?=$summoner->name?></code> is <code><?=$score?></code>.</p>
	</body>
</html>
