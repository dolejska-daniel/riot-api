<?php

/**
 * Copyright (C) 2016-2017  Daniel Dolejška
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
 *   Class ParticipantStatsDto
 *
 * Used in:
 *   match (v3)
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatch
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\Objects
 */
class ParticipantStatsDto extends ApiObject
{
	/** @var int $physicalDamageDealt */
	public $physicalDamageDealt;

	/** @var int $neutralMinionsKilledTeamJungle */
	public $neutralMinionsKilledTeamJungle;

	/** @var int $magicDamageDealt */
	public $magicDamageDealt;

	/** @var int $totalPlayerScore */
	public $totalPlayerScore;

	/** @var int $deaths */
	public $deaths;

	/** @var bool $win */
	public $win;

	/** @var int $neutralMinionsKilledEnemyJungle */
	public $neutralMinionsKilledEnemyJungle;

	/** @var int $altarsCaptured */
	public $altarsCaptured;

	/** @var int $largestCriticalStrike */
	public $largestCriticalStrike;

	/** @var int $totalDamageDealt */
	public $totalDamageDealt;

	/** @var int $magicDamageDealtToChampions */
	public $magicDamageDealtToChampions;

	/** @var int $visionWardsBoughtInGame */
	public $visionWardsBoughtInGame;

	/** @var int $damageDealtToObjectives */
	public $damageDealtToObjectives;

	/** @var int $largestKillingSpree */
	public $largestKillingSpree;

	/** @var int $item1 */
	public $item1;

	/** @var int $quadraKills */
	public $quadraKills;

	/** @var int $teamObjective */
	public $teamObjective;

	/** @var int $totalTimeCrowdControlDealt */
	public $totalTimeCrowdControlDealt;

	/** @var int $longestTimeSpentLiving */
	public $longestTimeSpentLiving;

	/** @var int $wardsKilled */
	public $wardsKilled;

	/** @var bool $firstTowerAssist */
	public $firstTowerAssist;

	/** @var bool $firstTowerKill */
	public $firstTowerKill;

	/** @var int $item2 */
	public $item2;

	/** @var int $item3 */
	public $item3;

	/** @var int $item0 */
	public $item0;

	/** @var bool $firstBloodAssist */
	public $firstBloodAssist;

	/** @var int $visionScore */
	public $visionScore;

	/** @var int $wardsPlaced */
	public $wardsPlaced;

	/** @var int $item4 */
	public $item4;

	/** @var int $item5 */
	public $item5;

	/** @var int $item6 */
	public $item6;

	/** @var int $turretKills */
	public $turretKills;

	/** @var int $tripleKills */
	public $tripleKills;

	/** @var int $damageSelfMitigated */
	public $damageSelfMitigated;

	/** @var int $champLevel */
	public $champLevel;

	/** @var int $nodeNeutralizeAssist */
	public $nodeNeutralizeAssist;

	/** @var bool $firstInhibitorKill */
	public $firstInhibitorKill;

	/** @var int $goldEarned */
	public $goldEarned;

	/** @var int $magicalDamageTaken */
	public $magicalDamageTaken;

	/** @var int $kills */
	public $kills;

	/** @var int $doubleKills */
	public $doubleKills;

	/** @var int $nodeCaptureAssist */
	public $nodeCaptureAssist;

	/** @var int $trueDamageTaken */
	public $trueDamageTaken;

	/** @var int $nodeNeutralize */
	public $nodeNeutralize;

	/** @var bool $firstInhibitorAssist */
	public $firstInhibitorAssist;

	/** @var int $assists */
	public $assists;

	/** @var int $unrealKills */
	public $unrealKills;

	/** @var int $neutralMinionsKilled */
	public $neutralMinionsKilled;

	/** @var int $objectivePlayerScore */
	public $objectivePlayerScore;

	/** @var int $combatPlayerScore */
	public $combatPlayerScore;

	/** @var int $damageDealtToTurrets */
	public $damageDealtToTurrets;

	/** @var int $altarsNeutralized */
	public $altarsNeutralized;

	/** @var int $physicalDamageDealtToChampions */
	public $physicalDamageDealtToChampions;

	/** @var int $goldSpent */
	public $goldSpent;

	/** @var int $trueDamageDealt */
	public $trueDamageDealt;

	/** @var int $trueDamageDealtToChampions */
	public $trueDamageDealtToChampions;

	/** @var int $participantId */
	public $participantId;

	/** @var int $pentaKills */
	public $pentaKills;

	/** @var int $totalHeal */
	public $totalHeal;

	/** @var int $totalMinionsKilled */
	public $totalMinionsKilled;

	/** @var bool $firstBloodKill */
	public $firstBloodKill;

	/** @var int $nodeCapture */
	public $nodeCapture;

	/** @var int $largestMultiKill */
	public $largestMultiKill;

	/** @var int $sightWardsBoughtInGame */
	public $sightWardsBoughtInGame;

	/** @var int $totalDamageDealtToChampions */
	public $totalDamageDealtToChampions;

	/** @var int $totalUnitsHealed */
	public $totalUnitsHealed;

	/** @var int $inhibitorKills */
	public $inhibitorKills;

	/** @var int $totalScoreRank */
	public $totalScoreRank;

	/** @var int $totalDamageTaken */
	public $totalDamageTaken;

	/** @var int $killingSprees */
	public $killingSprees;

	/** @var int $timeCCingOthers */
	public $timeCCingOthers;

	/** @var int $physicalDamageTaken */
	public $physicalDamageTaken;
}
