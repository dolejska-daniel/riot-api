<?php
/**
 *  This example shows how to fetch champion mastery score
 *    for given summoner ID and champion ID.
 */

//  Include init file
require __DIR__ . "/../_init.php";

//  Make a call to RiotAPI
$score = $api->getChampionMasteryScore(30904166);

?>

<p>Champion mastery score for summoner 30904166 is <?=$score?>.</p>
