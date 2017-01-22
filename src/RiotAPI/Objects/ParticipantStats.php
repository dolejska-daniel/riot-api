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
 *   Class ParticipantStats
 * This object contains participant statistics information
 *
 * @package RiotAPI\Objects
 */
class ParticipantStats extends ApiObject
{
	/** @var int $assists */
	public $assists;

	/** @var int $champLevel */
	public $champLevel;

	/** @var int $combatPlayerScore */
	public $combatPlayerScore;

	/** @var int $deaths */
	public $deaths;

	/** @var int $doubleKills */
	public $doubleKills;

	/** @var bool $firstBloodAssist */
	public $firstBloodAssist;

	/** @var bool $firstBloodKill */
	public $firstBloodKill;

	/** @var bool $firstInhibitorAssist */
	public $firstInhibitorAssist;

	/** @var bool $firstInhibitorKill */
	public $firstInhibitorKill;

	/** @var bool $wardsPlaced */
	public $firstTowerAssist;

	/** @var bool $firstTowerKill */
	public $firstTowerKill;

	/** @var int $goldEarned */
	public $goldEarned;

	/** @var int $goldSpent */
	public $goldSpent;

	/** @var int $inhibitorKills */
	public $inhibitorKills;

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

	/** @var int $killingSprees */
	public $killingSprees;

	/** @var int $kills */
	public $kills;

	/** @var int $largestCriticalStrike */
	public $largestCriticalStrike;

	/** @var int $largestKillingSpree */
	public $largestKillingSpree;

	/** @var int $largestMultiKill */
	public $largestMultiKill;

	/** @var int $magicDamageDealt */
	public $magicDamageDealt;

	/** @var int $magicDamageDealtToChampions */
	public $magicDamageDealtToChampions;

	/** @var int $magicDamageTaken */
	public $magicDamageTaken;

	/** @var int $minionsKilled */
	public $minionsKilled;

	/** @var int $neutralMinionsKilled */
	public $neutralMinionsKilled;

	/** @var int $neutralMinionsKilledEnemyJungle */
	public $neutralMinionsKilledEnemyJungle;

	/** @var int $neutralMinionsKilledTeamJungle */
	public $neutralMinionsKilledTeamJungle;

	/** @var int $nodeCapture */
	public $nodeCapture;

	/** @var int $nodeCaptureAssist */
	public $nodeCaptureAssist;

	/** @var int $nodeNeutralize */
	public $nodeNeutralize;

	/** @var int $nodeNeutralizeAssist */
	public $nodeNeutralizeAssist;

	/** @var int $objectivePlayerScore */
	public $objectivePlayerScore;

	/** @var int $pentaKills */
	public $pentaKills;

	/** @var int $physicalDamageDealt */
	public $physicalDamageDealt;

	/** @var int $physicalDamageDealtToChampions */
	public $physicalDamageDealtToChampions;

	/** @var int $physicalDamageTaken */
	public $physicalDamageTaken;

	/** @var int $quadraKills */
	public $quadraKills;

	/** @var int $sightWardsBoughtInGame */
	public $sightWardsBoughtInGame;

	/** @var int $teamObjective */
	public $teamObjective;

	/** @var int $totalDamageDealt */
	public $totalDamageDealt;

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

	/** @var int $towerKills */
	public $towerKills;

	/** @var int $tripleKills */
	public $tripleKills;

	/** @var int $trueDamageDealt */
	public $trueDamageDealt;

	/** @var int $trueDamageDealtToChampions */
	public $trueDamageDealtToChampions;

	/** @var int $trueDamageTaken */
	public $trueDamageTaken;

	/** @var int $unrealKills */
	public $unrealKills;

	/** @var int $visionWardsBoughtInGame */
	public $visionWardsBoughtInGame;

	/** @var int $wardsKilled */
	public $wardsKilled;

	/** @var int $wardsPlaced */
	public $wardsPlaced;

	/** @var bool $winner */
	public $winner;
}