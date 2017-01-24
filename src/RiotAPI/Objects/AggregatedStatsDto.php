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
 * Used in:
 *   stats (v1.3)
 *     @link https://developer.riotgames.com/api/methods#!/1209/4686
 *     @link https://developer.riotgames.com/api/methods#!/1209/4687
 *
 * @package RiotAPI\Objects
 */
class AggregatedStatsDto extends ApiObject
{
	/**
	 *   Dominion only.
	 *
	 * @var int $averageAssists
	 */
	public $averageAssists;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageChampionsKilled
	 */
	public $averageChampionsKilled;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageCombatPlayerScore
	 */
	public $averageCombatPlayerScore;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageNodeCapture
	 */
	public $averageNodeCapture;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageNodeCaptureAssist
	 */
	public $averageNodeCaptureAssist;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageNodeNeutralize
	 */
	public $averageNodeNeutralize;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageNodeNeutralizeAssist
	 */
	public $averageNodeNeutralizeAssist;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageNumDeaths
	 */
	public $averageNumDeaths;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageObjectivePlayerScore
	 */
	public $averageObjectivePlayerScore;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageTeamObjective
	 */
	public $averageTeamObjective;

	/**
	 *   Dominion only.
	 *
	 * @var int $averageTotalPlayerScore
	 */
	public $averageTotalPlayerScore;

	/** @var int $botGamesPlayed */
	public $botGamesPlayed;

	/** @var int $killingSpree */
	public $killingSpree;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxAssists
	 */
	public $maxAssists;

	/** @var int $maxChampionsKilled */
	public $maxChampionsKilled;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxCombatPlayerScore
	 */
	public $maxCombatPlayerScore;

	/** @var int $maxLargestCriticalStrike */
	public $maxLargestCriticalStrike;

	/** @var int $maxLargestKillingSpree */
	public $maxLargestKillingSpree;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxNodeCapture
	 */
	public $maxNodeCapture;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxNodeCaptureAssist
	 */
	public $maxNodeCaptureAssist;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxNodeNeutralize
	 */
	public $maxNodeNeutralize;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxNodeNeutralizeAssist
	 */
	public $maxNodeNeutralizeAssist;

	/**
	 *   Only returned for ranked statistics.
	 *
	 * @var int $maxNumDeaths
	 */
	public $maxNumDeaths;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxObjectivePlayerScore
	 */
	public $maxObjectivePlayerScore;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxTeamObjective
	 */
	public $maxTeamObjective;

	/** @var int $maxTimePlayed */
	public $maxTimePlayed;

	/** @var int $maxTimeSpentLiving */
	public $maxTimeSpentLiving;

	/**
	 *   Dominion only.
	 *
	 * @var int $maxTotalPlayerScore
	 */
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

	/**
	 *   Only returned for ranked statistics.
	 *
	 * @var int $totalDeathsPerSession
	 */
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

	/**
	 *   Dominion only.
	 *
	 * @var int $totalNodeCapture
	 */
	public $totalNodeCapture;

	/**
	 *   Dominion only.
	 *
	 * @var int $totalNodeNeutralize
	 */
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
