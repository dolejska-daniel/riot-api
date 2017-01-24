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
 *   Class Mastery
 *
 * Used in:
 *   current-game (v1.0)
 *     @link https://developer.riotgames.com/api/methods#!/976/3336
 *   match (v2.2)
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 *
 * @package RiotAPI\Objects
 */
class Mastery extends ApiObject
{
	/**
	 *   The ID of the mastery.
	 *
	 * @var int $masteryId
	 */
	public $masteryId;

	/**
	 *   The number of points put into this mastery by the user.
	 *
	 * @var int $rank
	 */
	public $rank;
}
