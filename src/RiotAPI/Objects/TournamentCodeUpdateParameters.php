<?php

/**
 * Copyright (C) 2016-2017  Daniel Dolejška
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
 *   Class TournamentCodeUpdateParameters
 *
 * Used in:
 *   tournament (v3)
 *     @link https://developer.riotgames.com/api-methods/#tournament-v3/PUT_updateCode
 *
 * @package RiotAPI\Objects
 */
class TournamentCodeUpdateParameters extends ApiObject
{
	/**
	 *   The spectator type (Legal values: NONE, LOBBYONLY, ALL).
	 *
	 * @var string $spectatorType
	 */
	public $spectatorType;

	/**
	 *   The pick type (Legal values: BLIND_PICK, DRAFT_MODE, ALL_RANDOM, 
	 * TOURNAMENT_DRAFT).
	 *
	 * @var string $pickType
	 */
	public $pickType;

	/**
	 *   Comma separated list of summoner Ids.
	 *
	 * @var string $allowedParticipants
	 */
	public $allowedParticipants;

	/**
	 *   The map type (Legal values: SUMMONERS_RIFT, TWISTED_TREELINE, 
	 * HOWLING_ABYSS).
	 *
	 * @var string $mapType
	 */
	public $mapType;
}
