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
 *   Class PlayerDto
 *
 * Used in:
 *   clash (v1)
 *     @link https://developer.riotgames.com/apis#clash-v1/GET_getPlayersBySummoner
 *     @link https://developer.riotgames.com/apis#clash-v1/GET_getTeamById
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class PlayerDto extends ApiObject
{
	/** @var string $summonerId */
	public $summonerId;

	/** @var string $teamId */
	public $teamId;

	/**
	 *   (Legal values: UNSELECTED, FILL, TOP, JUNGLE, MIDDLE, BOTTOM, UTILITY).
	 *
	 * @var string $position
	 */
	public $position;

	/**
	 *   (Legal values: CAPTAIN, MEMBER).
	 *
	 * @var string $role
	 */
	public $role;

	/** @var int $profileIcon */
	public $profileIcon;

	/**
	 *   Player's original accountId.
	 *
	 * @var string $accountId
	 */
	public $accountId;

	/** @var string $matchHistoryUri */
	public $matchHistoryUri;

	/**
	 *   Player's current accountId when the match was played.
	 *
	 * @var string $currentAccountId
	 */
	public $currentAccountId;

	/**
	 *   Player's current platformId when the match was played.
	 *
	 * @var string $currentPlatformId
	 */
	public $currentPlatformId;

	/** @var string $summonerName */
	public $summonerName;

	/**
	 *   Player's original platformId.
	 *
	 * @var string $platformId
	 */
	public $platformId;
}
