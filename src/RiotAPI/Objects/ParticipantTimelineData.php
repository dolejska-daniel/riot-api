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
 *   Class ParticipantTimelineData
 * This object contains timeline data.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class ParticipantTimelineData extends ApiObject
{
    /**
     *   Value per minute from 10 min to 20 min.
     *
     * @var float
     */
    public $tenToTwenty;

    /**
     *   Value per minute from 30 min to the end of the game.
     *
     * @var float
     */
    public $thirtyToEnd;

    /**
     *   Value per minute from 20 min to 30 min.
     *
     * @var float
     */
    public $twentyToThirty;

    /**
     *   Value per minute from the beginning of the game to 10 min.
     *
     * @var float
     */
    public $zeroToTen;
}
