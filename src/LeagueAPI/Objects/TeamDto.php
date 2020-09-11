<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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

namespace RiotAPI\LeagueAPI\Objects;


/**
 *   Class TeamDto
 *
 * Used in:
 *   clash (v1)
 *     @link https://developer.riotgames.com/apis#clash-v1/GET_getTeamById
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class TeamDto extends ApiObject
{
	/** @var string $id */
	public $id;

	/** @var int $tournamentId */
	public $tournamentId;

	/** @var string $name */
	public $name;

	/** @var int $iconId */
	public $iconId;

	/** @var int $tier */
	public $tier;

	/**
	 *   Summoner ID of the team captain.
	 *
	 * @var string $captain
	 */
	public $captain;

	/** @var string $abbreviation */
	public $abbreviation;

	/**
	 *   Team members.
	 *
	 * @var PlayerDto[] $players
	 */
	public $players;
}
