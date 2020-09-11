<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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

namespace RiotAPI\LeagueAPI\Objects;


/**
 *   Class ParticipantStatsDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class ParticipantStatsDto extends ApiObject
{
	/** @var int $item0 */
	public $item0;

	/** @var int $item2 */
	public $item2;

	/** @var int $totalUnitsHealed */
	public $totalUnitsHealed;

	/** @var int $item1 */
	public $item1;

	/** @var int $largestMultiKill */
	public $largestMultiKill;

	/** @var int $goldEarned */
	public $goldEarned;

	/** @var bool $firstInhibitorKill */
	public $firstInhibitorKill;

	/** @var int $physicalDamageTaken */
	public $physicalDamageTaken;

	/** @var int $nodeNeutralizeAssist */
	public $nodeNeutralizeAssist;

	/** @var int $totalPlayerScore */
	public $totalPlayerScore;

	/** @var int $champLevel */
	public $champLevel;

	/** @var int $damageDealtToObjectives */
	public $damageDealtToObjectives;

	/** @var int $totalDamageTaken */
	public $totalDamageTaken;

	/** @var int $neutralMinionsKilled */
	public $neutralMinionsKilled;

	/** @var int $deaths */
	public $deaths;

	/** @var int $tripleKills */
	public $tripleKills;

	/** @var int $magicDamageDealtToChampions */
	public $magicDamageDealtToChampions;

	/** @var int $wardsKilled */
	public $wardsKilled;

	/** @var int $pentaKills */
	public $pentaKills;

	/** @var int $damageSelfMitigated */
	public $damageSelfMitigated;

	/** @var int $largestCriticalStrike */
	public $largestCriticalStrike;

	/** @var int $nodeNeutralize */
	public $nodeNeutralize;

	/** @var int $totalTimeCrowdControlDealt */
	public $totalTimeCrowdControlDealt;

	/** @var bool $firstTowerKill */
	public $firstTowerKill;

	/** @var int $magicDamageDealt */
	public $magicDamageDealt;

	/** @var int $totalScoreRank */
	public $totalScoreRank;

	/** @var int $nodeCapture */
	public $nodeCapture;

	/** @var int $wardsPlaced */
	public $wardsPlaced;

	/** @var int $totalDamageDealt */
	public $totalDamageDealt;

	/** @var int $timeCCingOthers */
	public $timeCCingOthers;

	/** @var int $magicalDamageTaken */
	public $magicalDamageTaken;

	/** @var int $largestKillingSpree */
	public $largestKillingSpree;

	/** @var int $totalDamageDealtToChampions */
	public $totalDamageDealtToChampions;

	/** @var int $physicalDamageDealtToChampions */
	public $physicalDamageDealtToChampions;

	/** @var int $neutralMinionsKilledTeamJungle */
	public $neutralMinionsKilledTeamJungle;

	/** @var int $totalMinionsKilled */
	public $totalMinionsKilled;

	/** @var bool $firstInhibitorAssist */
	public $firstInhibitorAssist;

	/** @var int $visionWardsBoughtInGame */
	public $visionWardsBoughtInGame;

	/** @var int $objectivePlayerScore */
	public $objectivePlayerScore;

	/** @var int $kills */
	public $kills;

	/** @var bool $firstTowerAssist */
	public $firstTowerAssist;

	/** @var int $combatPlayerScore */
	public $combatPlayerScore;

	/** @var int $inhibitorKills */
	public $inhibitorKills;

	/** @var int $turretKills */
	public $turretKills;

	/** @var int $participantId */
	public $participantId;

	/** @var int $trueDamageTaken */
	public $trueDamageTaken;

	/** @var bool $firstBloodAssist */
	public $firstBloodAssist;

	/** @var int $nodeCaptureAssist */
	public $nodeCaptureAssist;

	/** @var int $assists */
	public $assists;

	/** @var int $teamObjective */
	public $teamObjective;

	/** @var int $altarsNeutralized */
	public $altarsNeutralized;

	/** @var int $goldSpent */
	public $goldSpent;

	/** @var int $damageDealtToTurrets */
	public $damageDealtToTurrets;

	/** @var int $altarsCaptured */
	public $altarsCaptured;

	/** @var bool $win */
	public $win;

	/** @var int $totalHeal */
	public $totalHeal;

	/** @var int $unrealKills */
	public $unrealKills;

	/** @var int $visionScore */
	public $visionScore;

	/** @var int $physicalDamageDealt */
	public $physicalDamageDealt;

	/** @var bool $firstBloodKill */
	public $firstBloodKill;

	/** @var int $longestTimeSpentLiving */
	public $longestTimeSpentLiving;

	/** @var int $killingSprees */
	public $killingSprees;

	/** @var int $sightWardsBoughtInGame */
	public $sightWardsBoughtInGame;

	/** @var int $trueDamageDealtToChampions */
	public $trueDamageDealtToChampions;

	/** @var int $neutralMinionsKilledEnemyJungle */
	public $neutralMinionsKilledEnemyJungle;

	/** @var int $doubleKills */
	public $doubleKills;

	/** @var int $trueDamageDealt */
	public $trueDamageDealt;

	/** @var int $quadraKills */
	public $quadraKills;

	/** @var int $item4 */
	public $item4;

	/** @var int $item3 */
	public $item3;

	/** @var int $item6 */
	public $item6;

	/** @var int $item5 */
	public $item5;

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

	/**
	 *   Primary path keystone rune.
	 *
	 * @var int $perk0
	 */
	public $perk0;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk0Var1
	 */
	public $perk0Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk0Var2
	 */
	public $perk0Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk0Var3
	 */
	public $perk0Var3;

	/**
	 *   Primary path rune.
	 *
	 * @var int $perk1
	 */
	public $perk1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk1Var1
	 */
	public $perk1Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk1Var2
	 */
	public $perk1Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk1Var3
	 */
	public $perk1Var3;

	/**
	 *   Primary path rune.
	 *
	 * @var int $perk2
	 */
	public $perk2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk2Var1
	 */
	public $perk2Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk2Var2
	 */
	public $perk2Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk2Var3
	 */
	public $perk2Var3;

	/**
	 *   Primary path rune.
	 *
	 * @var int $perk3
	 */
	public $perk3;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk3Var1
	 */
	public $perk3Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk3Var2
	 */
	public $perk3Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk3Var3
	 */
	public $perk3Var3;

	/**
	 *   Secondary path rune.
	 *
	 * @var int $perk4
	 */
	public $perk4;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk4Var1
	 */
	public $perk4Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk4Var2
	 */
	public $perk4Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk4Var3
	 */
	public $perk4Var3;

	/**
	 *   Secondary path rune.
	 *
	 * @var int $perk5
	 */
	public $perk5;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk5Var1
	 */
	public $perk5Var1;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk5Var2
	 */
	public $perk5Var2;

	/**
	 *   Post game rune stats.
	 *
	 * @var int $perk5Var3
	 */
	public $perk5Var3;

	/**
	 *   Primary rune path.
	 *
	 * @var int $perkPrimaryStyle
	 */
	public $perkPrimaryStyle;

	/**
	 *   Secondary rune path.
	 *
	 * @var int $perkSubStyle
	 */
	public $perkSubStyle;
}
