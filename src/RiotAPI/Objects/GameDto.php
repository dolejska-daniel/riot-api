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
 *   Class GameDto
 * This object contains game information.
 *
 * Used in:
 *   game (v1.3)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1207/4679
 */
class GameDto extends ApiObject
{
    /**
     *   Champion ID associated with game.
     *
     * @var int
     */
    public $championId;

    /**
     *   Date that end game data was recorded, specified as epoch milliseconds.
     *
     * @var int
     */
    public $createDate;

    /**
     *   Other players associated with the game.
     *
     * @var PlayerDto[]
     */
    public $fellowPlayers;

    /**
     *   Game ID.
     *
     * @var int
     */
    public $gameId;

    /**
     *   Game mode. (Legal values: CLASSIC, ODIN, ARAM, TUTORIAL, ONEFORALL,
     * ASCENSION, FIRSTBLOOD, KINGPORO, SIEGE).
     *
     * @var string
     */
    public $gameMode;

    /**
     *   Game type. (Legal values: CUSTOM_GAME, MATCHED_GAME, TUTORIAL_GAME).
     *
     * @var string
     */
    public $gameType;

    /**
     *   Invalid flag.
     *
     * @var bool
     */
    public $invalid;

    /**
     *   IP Earned.
     *
     * @var int
     */
    public $ipEarned;

    /**
     *   Level.
     *
     * @var int
     */
    public $level;

    /**
     *   Map ID.
     *
     * @var int
     */
    public $mapId;

    /**
     *   ID of first summoner spell.
     *
     * @var int
     */
    public $spell1;

    /**
     *   ID of second summoner spell.
     *
     * @var int
     */
    public $spell2;

    /**
     *   Statistics associated with the game for this summoner.
     *
     * @var RawStatsDto
     */
    public $stats;

    /**
     *   Game sub-type. (Legal values: NONE, NORMAL, BOT, RANKED_SOLO_5x5,
     * RANKED_PREMADE_3x3, RANKED_PREMADE_5x5, ODIN_UNRANKED, RANKED_TEAM_3x3, RANKED_TEAM_5x5,
     * NORMAL_3x3, BOT_3x3, CAP_5x5, ARAM_UNRANKED_5x5, ONEFORALL_5x5, FIRSTBLOOD_1x1,
     * FIRSTBLOOD_2x2, SR_6x6, URF, URF_BOT, NIGHTMARE_BOT, ASCENSION, HEXAKILL, KING_PORO,
     * COUNTER_PICK, BILGEWATER, SIEGE, RANKED_FLEX_SR, RANKED_FLEX_TT).
     *
     * @var string
     */
    public $subType;

    /**
     *   Team ID associated with game. Team ID 100 is blue team. Team ID 200 is
     * purple team.
     *
     * @var int
     */
    public $teamId;
}
