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
 *   Class SummonerDto
 * represents a summoner
 *
 * Used in:
 *   summoner (v4)
 *     @link https://developer.riotgames.com/apis#summoner-v4/GET_getByAccountId
 *     @link https://developer.riotgames.com/apis#summoner-v4/GET_getBySummonerName
 *     @link https://developer.riotgames.com/apis#summoner-v4/GET_getByPUUID
 *     @link https://developer.riotgames.com/apis#summoner-v4/GET_getBySummonerId
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class SummonerDto extends ApiObject
{
	/**
	 *   ID of the summoner icon associated with the summoner.
	 *
	 * @var int $profileIconId
	 */
	public $profileIconId;

	/**
	 *   Summoner name.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Encrypted PUUID. Exact length of 78 characters.
	 *
	 * @var string $puuid
	 */
	public $puuid;

	/**
	 *   Summoner level associated with the summoner.
	 *
	 * @var int $summonerLevel
	 */
	public $summonerLevel;

	/**
	 *   Date summoner was last modified specified as epoch milliseconds. The 
	 * following events will update this timestamp: profile icon change, playing the 
	 * tutorial or advanced tutorial, finishing a game, summoner name change.
	 *
	 * @var int $revisionDate
	 */
	public $revisionDate;

	/**
	 *   Encrypted summoner ID. Max length 63 characters.
	 *
	 * @var string $id
	 */
	public $id;

	/**
	 *   Encrypted account ID. Max length 56 characters.
	 *
	 * @var string $accountId
	 */
	public $accountId;
}
