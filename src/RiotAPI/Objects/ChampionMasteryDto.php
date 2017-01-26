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
 *   Class ChampionMasteryDto
 * This object contains single Champion Mastery information for player and champion combination.
 *
 * Used in:
 *   championmastery (unknown)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1091/3769
 *     @link https://developer.riotgames.com/api/methods#!/1091/3768
 *     @link https://developer.riotgames.com/api/methods#!/1091/3764
 */
class ChampionMasteryDto extends ApiObject
{
    /**
     *   Champion ID for this entry.
     *
     * @var int
     */
    public $championId;

    /**
     *   Champion level for specified player and champion combination.
     *
     * @var int
     */
    public $championLevel;

    /**
     *   Total number of champion points for this player and champion combination -
     * they are used to determine championLevel.
     *
     * @var int
     */
    public $championPoints;

    /**
     *   Number of points earned since current level has been achieved. Zero if
     * player reached maximum champion level for this champion.
     *
     * @var int
     */
    public $championPointsSinceLastLevel;

    /**
     *   Number of points needed to achieve next level. Zero if player reached
     * maximum champion level for this champion.
     *
     * @var int
     */
    public $championPointsUntilNextLevel;

    /**
     *   Is chest granted for this champion or not in current season.
     *
     * @var bool
     */
    public $chestGranted;

    /**
     *   Last time this champion was played by this player - in Unix milliseconds
     * time format.
     *
     * @var int
     */
    public $lastPlayTime;

    /**
     *   Player ID for this entry.
     *
     * @var int
     */
    public $playerId;
}
