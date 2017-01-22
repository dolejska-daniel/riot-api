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
 *   Class AggregatedStatsDto
 * This object contains aggregated stat information.
 *
 * @package RiotAPI\Objects
 */
class AggregatedStatsDto extends ApiObject
{
	/** @var int $averageAssists */
	public $averageAssists;

	/** @var int $averageChampionsKilled */
	public $averageChampionsKilled;

	/** @var int $averageCombatPlayerScore */
	public $averageCombatPlayerScore;

	/** @var int $averageNodeCapture */
	public $averageNodeCapture;

	/** @var int $averageNodeCaptureAssist */
	public $averageNodeCaptureAssist;

	/** @var int $averageNodeNeutralize */
	public $averageNodeNeutralize;

	/** @var int $averageNodeNeutralizeAssist */
	public $averageNodeNeutralizeAssist;

	/** @var int $averageNumDeaths */
	public $averageNumDeaths;

	/** @var int $averageObjectivePlayerScore */
	public $averageObjectivePlayerScore;

	/** @var int $averageTeamObjective */
	public $averageTeamObjective;

	/** @var int $averageTotalPlayerScore */
	public $averageTotalPlayerScore;

	/** @var int $botGamesPlayed */
	public $botGamesPlayed;

	/** @var int $killingSpree */
	public $killingSpree;

	/** @var int $maxAssists */
	public $maxAssists;

	/** @var int $maxChampionsKilled */
	public $maxChampionsKilled;

	/** @var int $maxCombatPlayerScore */
	public $maxCombatPlayerScore;

	/** @var int $maxLargestCriticalStrike */
	public $maxLargestCriticalStrike;

	/** @var int $maxLargestKillingSpree */
	public $maxLargestKillingSpree;

	/** @var int $maxNodeCapture */
	public $maxNodeCapture;

	/** @var int $maxNodeCaptureAssist */
	public $maxNodeCaptureAssist;

	/** @var int $maxNodeNeutralize */
	public $maxNodeNeutralize;

	/** @var int $maxNodeNeutralizeAssist */
	public $maxNodeNeutralizeAssist;

	/** @var int $maxNumDeaths */
	public $maxNumDeaths;

	/** @var int $maxObjectivePlayerScore */
	public $maxObjectivePlayerScore;

	/** @var int $maxTeamObjective */
	public $maxTeamObjective;

	/** @var int $maxTimePlayed */
	public $maxTimePlayed;

	/** @var int $maxTimeSpentLiving */
	public $maxTimeSpentLiving;

	/** @var int $maxTotalPlayerScore */
	public $maxTotalPlayerScore;

	/** @var int $mostChampionKillsPerSession */
	public $mostChampionKillsPerSession;

	/** @var int $mostSpellsCast */
	public $mostSpellsCast;

	/** @var int $normalGamesPlayed */
	public $normalGamesPlayed;

	/** @var int $rankedPremadeGamesPlayed */
	public $rankedPremadeGamesPlayed;

	/** @var int $rankedSoloGamesPlayed */
	public $rankedSoloGamesPlayed;

	/** @var int $totalAssists */
	public $totalAssists;

	/** @var int $totalChampionKills */
	public $totalChampionKills;

	/** @var int $totalDamageDealt */
	public $totalDamageDealt;

	/** @var int $totalDamageTaken */
	public $totalDamageTaken;

	/** @var int $totalDeathsPerSession */
	public $totalDeathsPerSession;

	/** @var int $totalDoubleKills */
	public $totalDoubleKills;

	/** @var int $totalFirstBlood */
	public $totalFirstBlood;

	/** @var int $totalGoldEarned */
	public $totalGoldEarned;

	/** @var int $totalHeal */
	public $totalHeal;

	/** @var int $totalMagicDamageDealt */
	public $totalMagicDamageDealt;

	/** @var int $totalMinionKills */
	public $totalMinionKills;

	/** @var int $totalNeutralMinionsKilled */
	public $totalNeutralMinionsKilled;

	/** @var int $totalNodeCapture */
	public $totalNodeCapture;

	/** @var int $totalNodeNeutralize */
	public $totalNodeNeutralize;

	/** @var int $totalPentaKills */
	public $totalPentaKills;

	/** @var int $totalPhysicalDamageDealt */
	public $totalPhysicalDamageDealt;

	/** @var int $totalQuadraKills */
	public $totalQuadraKills;

	/** @var int $totalSessionsLost */
	public $totalSessionsLost;

	/** @var int $totalSessionsPlayed */
	public $totalSessionsPlayed;

	/** @var int $totalSessionsWon */
	public $totalSessionsWon;

	/** @var int $totalTripleKills */
	public $totalTripleKills;

	/** @var int $totalTurretsKilled */
	public $totalTurretsKilled;

	/** @var int $totalUnrealKills */
	public $totalUnrealKills;
}