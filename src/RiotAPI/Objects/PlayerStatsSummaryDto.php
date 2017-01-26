<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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
 *   Class PlayerStatsSummaryDto
 * This object contains player stats summary information.
 *
 * Used in:
 *   stats (v1.3)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1209/4687
 */
class PlayerStatsSummaryDto extends ApiObject
{
    /**
     *   Aggregated stats.
     *
     * @var AggregatedStatsDto
     */
    public $aggregatedStats;

    /**
     *   Number of losses for this queue type. Returned for ranked queue types
     * only.
     *
     * @var int
     */
    public $losses;

    /**
     *   Date stats were last modified specified as epoch milliseconds.
     *
     * @var int
     */
    public $modifyDate;

    /**
     *   Player stats summary type. (Legal values: AramUnranked5x5, Ascension,
     * Bilgewater, CAP5x5, CoopVsAI, CoopVsAI3x3, CounterPick, FirstBlood1x1, FirstBlood2x2,
     * Hexakill, KingPoro, NightmareBot, OdinUnranked, OneForAll5x5, RankedPremade3x3,
     * RankedPremade5x5, RankedSolo5x5, RankedTeam3x3, RankedTeam5x5, SummonersRift6x6, Unranked,
     * Unranked3x3, URF, URFBots, Siege, RankedFlexSR, RankedFlexTT).
     *
     * @var string
     */
    public $playerStatSummaryType;

    /**
     *   Number of wins for this queue type.
     *
     * @var int
     */
    public $wins;
}
