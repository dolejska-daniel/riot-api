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
 *   Class SummonerDto
 * This object contains summoner information.
 *
 * Used in:
 *   summoner (v1.4)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1208/4684
 *     @link https://developer.riotgames.com/api/methods#!/1208/4681
 */
class SummonerDto extends ApiObject
{
    /**
     *   Summoner ID.
     *
     * @var int
     */
    public $id;

    /**
     *   Summoner name.
     *
     * @var string
     */
    public $name;

    /**
     *   ID of the summoner icon associated with the summoner.
     *
     * @var int
     */
    public $profileIconId;

    /**
     *   Date summoner was last modified specified as epoch milliseconds. The
     * following events will update this timestamp: profile icon change, playing the
     * tutorial or advanced tutorial, finishing a game, summoner name change.
     *
     * @var int
     */
    public $revisionDate;

    /**
     *   Summoner level associated with the summoner.
     *
     * @var int
     */
    public $summonerLevel;
}
