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
 *   Class TournamentRegistrationParameters
 *
 * Used in:
 *   tournament-stub (v4)
 *     @link https://developer.riotgames.com/apis#tournament-stub-v4/POST_registerTournament
 *   tournament (v4)
 *     @link https://developer.riotgames.com/apis#tournament-v4/POST_registerTournament
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class TournamentRegistrationParameters extends ApiObject
{
	/**
	 *   The provider ID to specify the regional registered provider data to 
	 * associate this tournament.
	 *
	 * @var int $providerId
	 */
	public $providerId;

	/**
	 *   The optional name of the tournament.
	 *
	 * @var string $name
	 */
	public $name;
}
