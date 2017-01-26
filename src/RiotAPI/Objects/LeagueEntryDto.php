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
 *   Class LeagueEntryDto
 * This object contains league participant information representing a summoner or team.
 *
 * Used in:
 *   league (v2.5)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1215/4701
 *     @link https://developer.riotgames.com/api/methods#!/1215/4705
 *     @link https://developer.riotgames.com/api/methods#!/1215/4704
 *     @link https://developer.riotgames.com/api/methods#!/1215/4706
 */
class LeagueEntryDto extends ApiObject
{
    /**
     *   The league division of the participant.
     *
     * @var string
     */
    public $division;

    /**
     *   Specifies if the participant is fresh blood.
     *
     * @var bool
     */
    public $isFreshBlood;

    /**
     *   Specifies if the participant is on a hot streak.
     *
     * @var bool
     */
    public $isHotStreak;

    /**
     *   Specifies if the participant is inactive.
     *
     * @var bool
     */
    public $isInactive;

    /**
     *   Specifies if the participant is a veteran.
     *
     * @var bool
     */
    public $isVeteran;

    /**
     *   The league points of the participant.
     *
     * @var int
     */
    public $leaguePoints;

    /**
     *   The number of losses for the participant.
     *
     * @var int
     */
    public $losses;

    /**
     *   Mini series data for the participant. Only present if the participant is
     * currently in a mini series.
     *
     * @var MiniSeriesDto
     */
    public $miniSeries;

    /**
     *   The ID of the participant (i.e., summoner or team) represented by this
     * entry.
     *
     * @var string
     */
    public $playerOrTeamId;

    /**
     *   The name of the the participant (i.e., summoner or team) represented by
     * this entry.
     *
     * @var string
     */
    public $playerOrTeamName;

    /**
     *   The playstyle of the participant. (Legal values: NONE, SOLO, SQUAD, TEAM).
     *
     * @var string
     */
    public $playstyle;

    /**
     *   The number of wins for the participant.
     *
     * @var int
     */
    public $wins;
}
