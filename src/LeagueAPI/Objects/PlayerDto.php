<?php

/**
 * Copyright (C) 2016-2019  Daniel Dolejška
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
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchIdsByTournamentCode
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class PlayerDto extends ApiObject
{
	/** @var string $currentPlatformId */
	public $currentPlatformId;

	/** @var string $summonerName */
	public $summonerName;

	/** @var string $matchHistoryUri */
	public $matchHistoryUri;

	/**
	 *   Original platformId.
	 *
	 * @var string $platformId
	 */
	public $platformId;

	/**
	 *   Player's current accountId (Encrypted).
	 *
	 * @var string $currentAccountId
	 */
	public $currentAccountId;

	/** @var int $profileIcon */
	public $profileIcon;

	/**
	 *   Player's summonerId (Encrypted).
	 *
	 * @var string $summonerId
	 */
	public $summonerId;

	/**
	 *   Player's original accountId (Encrypted).
	 *
	 * @var string $accountId
	 */
	public $accountId;
}
