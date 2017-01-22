<?php

/**
 * Copyright (C) 2016  Daniel Dolejška
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace RiotAPI\Objects;


/**
 *   Class RawStatsDto
 *
 * @package RiotAPI\Objects
 */
class RawStatsDto extends ApiObject
{
	/** @var int $assists */
	public $assists;

	/**
	 * Number of enemy inhibitors killed.
	 * @var int $barracksKilled
	 */
	public $barracksKilled;

	/** @var int $bountyLevel */
	public $bountyLevel;

	/** @var int $championsKilled */
	public $championsKilled;

	/** @var int $combatPlayerScore */
	public $combatPlayerScore;

	/** @var int $consumablesPurchased */
	public $consumablesPurchased;

	/** @var int $damageDealtPlayer */
	public $damageDealtPlayer;

	/** @var int $doubleKills */
	public $doubleKills;

	/** @var int $firstBlood */
	public $firstBlood;

	/** @var int $gold */
	public $gold;

	/** @var int $goldEarned */
	public $goldEarned;

	/** @var int $goldSpent */
	public $goldSpent;

	/** @var int $item0 */
	public $item0;

	/** @var int $item1 */
	public $item1;

	/** @var int $item2 */
	public $item2;

	/** @var int $item3 */
	public $item3;

	/** @var int $item4 */
	public $item4;

	/** @var int $item5 */
	public $item5;

	/** @var int $item6 */
	public $item6;

	/** @var int $itemsPurchased */
	public $itemsPurchased;

	/** @var int $killingSprees */
	public $killingSprees;

	/** @var int $largestCriticalStrike */
	public $largestCriticalStrike;

	/** @var int $largestKillingSpree */
	public $largestKillingSpree;

	/** @var int $largestMultiKill */
	public $largestMultiKill;

	/**
	 * Number of tier 3 items built.
	 * @var int $legendaryItemsCreated
	 */
	public $legendaryItemsCreated;

	/** @var int $level */
	public $level;

	/** @var int $magicDamageDealtPlayer */
	public $magicDamageDealtPlayer;

	/** @var int $magicDamageDealtToChampions */
	public $magicDamageDealtToChampions;

	/** @var int $magicDamageTaken */
	public $magicDamageTaken;

	/** @var int $minionsDenied */
	public $minionsDenied;

	/** @var int $minionsKilled */
	public $minionsKilled;

	/** @var int $neutralMinionsKilled */
	public $neutralMinionsKilled;

	/** @var int $neutralMinionsKilledEnemyJungle */
	public $neutralMinionsKilledEnemyJungle;

	/** @var int $neutralMinionsKilledYourJungle */
	public $neutralMinionsKilledYourJungle;

	/**
	 * Flag specifying if the summoner got the killing blow on the nexus.
	 * @var bool $nexusKilled
	 */
	public $nexusKilled;

	/** @var int $nodeCapture */
	public $nodeCapture;

	/** @var int $nodeCaptureAssist */
	public $nodeCaptureAssist;

	/** @var int $nodeNeutralize */
	public $nodeNeutralize;

	/** @var int $nodeNeutralizeAssist */
	public $nodeNeutralizeAssist;

	/** @var int $numDeaths */
	public $numDeaths;

	/** @var int $numItemsBought */
	public $numItemsBought;

	/** @var int $objectivePlayerScore */
	public $objectivePlayerScore;

	/** @var int $pentaKills */
	public $pentaKills;

	/** @var int $physicalDamageDealtPlayer */
	public $physicalDamageDealtPlayer;

	/** @var int $physicalDamageDealtToChampions */
	public $physicalDamageDealtToChampions;

	/** @var int $physicalDamageTaken */
	public $physicalDamageTaken;

	/**
	 * Player position (Legal values: TOP(1), MIDDLE(2), JUNGLE(3), BOT(4))
	 * @var int $playerPosition
	 */
	public $playerPosition;

	/**
	 * Player role (Legal values: DUO(1), SUPPORT(2), CARRY(3), SOLO(4))
	 * @var int $playerRole
	 */
	public $playerRole;

	/** @var int $playerScore0 */
	public $playerScore0;

	/** @var int $playerScore1 */
	public $playerScore1;

	/** @var int $playerScore2 */
	public $playerScore2;

	/** @var int $playerScore3 */
	public $playerScore3;

	/** @var int $playerScore4 */
	public $playerScore4;

	/** @var int $playerScore5 */
	public $playerScore5;

	/** @var int $playerScore6 */
	public $playerScore6;

	/** @var int $playerScore7 */
	public $playerScore7;

	/** @var int $playerScore8 */
	public $playerScore8;

	/** @var int $playerScore9 */
	public $playerScore9;

	/** @var int $quadraKills */
	public $quadraKills;

	/** @var int $sightWardsBought */
	public $sightWardsBought;

	/**
	 * 	Number of times (Q) first champion spell was cast.
	 * @var int $spell1Cast
	 */
	public $spell1Cast;

	/**
	 * Number of times (W) second champion spell was cast.
	 * @var int $spell2Cast
	 */
	public $spell2Cast;

	/**
	 * 	Number of times (E) third champion spell was cast.
	 * @var int $spell3Cast
	 */
	public $spell3Cast;

	/**
	 * 	Number of times (R) fourth champion spell was cast.
	 * @var int $spell4Cast
	 */
	public $spell4Cast;

	/** @var int $summonSpell1Cast */
	public $summonSpell1Cast;

	/** @var int $summonSpell2Cast */
	public $summonSpell2Cast;

	/** @var int $superMonsterKilled */
	public $superMonsterKilled;

	/** @var int $team */
	public $team;

	/** @var int $teamObjective */
	public $teamObjective;

	/** @var int $timePlayed */
	public $timePlayed;

	/** @var int $totalDamageDealt */
	public $totalDamageDealt;

	/** @var int $totalDamageDealtToBuildings */
	public $totalDamageDealtToBuildings;

	/** @var int $totalDamageDealtToChampions */
	public $totalDamageDealtToChampions;

	/** @var int $totalDamageTaken */
	public $totalDamageTaken;

	/** @var int $totalHeal */
	public $totalHeal;

	/** @var int $totalPlayerScore */
	public $totalPlayerScore;

	/** @var int $totalScoreRank */
	public $totalScoreRank;

	/** @var int $totalTimeCrowdControlDealt */
	public $totalTimeCrowdControlDealt;

	/** @var int $totalUnitsHealed */
	public $totalUnitsHealed;

	/** @var int $tripleKills */
	public $tripleKills;

	/** @var int $trueDamageDealtPlayer */
	public $trueDamageDealtPlayer;

	/** @var int $trueDamageDealtToChampions */
	public $trueDamageDealtToChampions;

	/** @var int $trueDamageTaken */
	public $trueDamageTaken;

	/** @var int $turretsKilled */
	public $turretsKilled;

	/** @var int $unrealKills */
	public $unrealKills;

	/** @var int $victoryPointTotal */
	public $victoryPointTotal;

	/** @var int $visionWardsBought */
	public $visionWardsBought;

	/** @var int $wardKilled */
	public $wardKilled;

	/** @var int $wardPlaced */
	public $wardPlaced;

	/**
	 * Flag specifying whether or not this game was won.
	 * @var bool $win
	 */
	public $win;
}