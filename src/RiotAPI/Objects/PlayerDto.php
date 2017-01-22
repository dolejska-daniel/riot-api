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
 *   Class PlayerDto
 * This object contains player information.
 *
 * @package RiotAPI\Objects
 */
class PlayerDto extends ApiObject
{
	/**
	 * Champion id associated with player.
	 * @var int $championId
	 */
	public $championId;

	/**
	 * Summoner id associated with player.
	 * @var int $summonerId
	 */
	public $summonerId;

	/**
	 * Team id associated with player.
	 * @var int $teamId
	 */
	public $teamId;
}