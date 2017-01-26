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
 *   Class Team
 * This object contains team information.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class Team extends ApiObject
{
    /**
     *   If game was draft mode, contains banned champion data, otherwise null.
     *
     * @var BannedChampion[]
     */
    public $bans;

    /**
     *   Number of times the team killed baron.
     *
     * @var int
     */
    public $baronKills;

    /**
     *   If game was a dominion game, specifies the points the team had at game
     * end, otherwise null.
     *
     * @var int
     */
    public $dominionVictoryScore;

    /**
     *   Number of times the team killed dragon.
     *
     * @var int
     */
    public $dragonKills;

    /**
     *   Flag indicating whether or not the team got the first baron kill.
     *
     * @var bool
     */
    public $firstBaron;

    /**
     *   Flag indicating whether or not the team got first blood.
     *
     * @var bool
     */
    public $firstBlood;

    /**
     *   Flag indicating whether or not the team got the first dragon kill.
     *
     * @var bool
     */
    public $firstDragon;

    /**
     *   Flag indicating whether or not the team destroyed the first inhibitor.
     *
     * @var bool
     */
    public $firstInhibitor;

    /**
     *   Flag indicating whether or not the team got the first rift herald kill.
     *
     * @var bool
     */
    public $firstRiftHerald;

    /**
     *   Flag indicating whether or not the team destroyed the first tower.
     *
     * @var bool
     */
    public $firstTower;

    /**
     *   Number of inhibitors the team destroyed.
     *
     * @var int
     */
    public $inhibitorKills;

    /**
     *   Number of times the team killed rift herald.
     *
     * @var int
     */
    public $riftHeraldKills;

    /**
     *   Team ID.
     *
     * @var int
     */
    public $teamId;

    /**
     *   Number of towers the team destroyed.
     *
     * @var int
     */
    public $towerKills;

    /**
     *   Number of times the team killed vilemaw.
     *
     * @var int
     */
    public $vilemawKills;

    /**
     *   Flag indicating whether or not the team won.
     *
     * @var bool
     */
    public $winner;
}
