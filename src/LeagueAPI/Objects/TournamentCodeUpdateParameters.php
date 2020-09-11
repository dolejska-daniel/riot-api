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
 *   Class TournamentCodeUpdateParameters
 *
 * Used in:
 *   tournament (v4)
 *     @link https://developer.riotgames.com/apis#tournament-v4/PUT_updateCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class TournamentCodeUpdateParameters extends ApiObject
{
	/**
	 *   Optional list of encrypted summonerIds in order to validate the players 
	 * eligible to join the lobby. NOTE: We currently do not enforce participants at the 
	 * team level, but rather the aggregate of teamOne and teamTwo. We may add the 
	 * ability to enforce at the team level in the future.
	 *
	 * @var string[] $allowedSummonerIds
	 */
	public $allowedSummonerIds;

	/**
	 *   The pick type (Legal values: BLIND_PICK, DRAFT_MODE, ALL_RANDOM, 
	 * TOURNAMENT_DRAFT).
	 *
	 * @var string $pickType
	 */
	public $pickType;

	/**
	 *   The map type (Legal values: SUMMONERS_RIFT, TWISTED_TREELINE, 
	 * HOWLING_ABYSS).
	 *
	 * @var string $mapType
	 */
	public $mapType;

	/**
	 *   The spectator type (Legal values: NONE, LOBBYONLY, ALL).
	 *
	 * @var string $spectatorType
	 */
	public $spectatorType;
}
