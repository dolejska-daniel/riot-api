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
 *   Class CurrentGameParticipant.
 *
 * Used in:
 *   current-game (v1.0)
 *
 *     @link https://developer.riotgames.com/api/methods#!/976/3336
 */
class CurrentGameParticipant extends ApiObject
{
    /**
     *   Flag indicating whether or not this participant is a bot.
     *
     * @var bool
     */
    public $bot;

    /**
     *   The ID of the champion played by this participant.
     *
     * @var int
     */
    public $championId;

    /**
     *   The masteries used by this participant.
     *
     * @var Mastery[]
     */
    public $masteries;

    /**
     *   The ID of the profile icon used by this participant.
     *
     * @var int
     */
    public $profileIconId;

    /**
     *   The runes used by this participant.
     *
     * @var Rune[]
     */
    public $runes;

    /**
     *   The ID of the first summoner spell used by this participant.
     *
     * @var int
     */
    public $spell1Id;

    /**
     *   The ID of the second summoner spell used by this participant.
     *
     * @var int
     */
    public $spell2Id;

    /**
     *   The summoner ID of this participant.
     *
     * @var int
     */
    public $summonerId;

    /**
     *   The summoner name of this participant.
     *
     * @var string
     */
    public $summonerName;

    /**
     *   The team ID of this participant, indicating the participant's team.
     *
     * @var int
     */
    public $teamId;
}
