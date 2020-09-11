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
 *   Class LeagueEntryDto
 *
 * Used in:
 *   league-exp (v4)
 *     @link https://developer.riotgames.com/apis#league-exp-v4/GET_getLeagueEntries
 *   league (v4)
 *     @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueEntriesForSummoner
 *     @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueEntriesForSummoner
 *   tft-league (v1)
 *     @link https://developer.riotgames.com/apis#tft-league-v1/GET_getLeagueEntriesForSummoner
 *     @link https://developer.riotgames.com/apis#tft-league-v1/GET_getLeagueEntriesForSummoner
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class LeagueEntryDto extends ApiObject
{
	/** @var string $leagueId */
	public $leagueId;

	/**
	 *   Player's summonerId (Encrypted).
	 *
	 * @var string $summonerId
	 */
	public $summonerId;

	/** @var string $summonerName */
	public $summonerName;

	/** @var string $queueType */
	public $queueType;

	/** @var string $tier */
	public $tier;

	/** @var string $rank */
	public $rank;

	/** @var int $leaguePoints */
	public $leaguePoints;

	/**
	 *   Winning team on Summoners Rift. First placement in Teamfight Tactics.
	 *
	 * @var int $wins
	 */
	public $wins;

	/**
	 *   Losing team on Summoners Rift. Second through eighth placement in 
	 * Teamfight Tactics.
	 *
	 * @var int $losses
	 */
	public $losses;

	/** @var bool $hotStreak */
	public $hotStreak;

	/** @var bool $veteran */
	public $veteran;

	/** @var bool $freshBlood */
	public $freshBlood;

	/** @var bool $inactive */
	public $inactive;

	/** @var MiniSeriesDto $miniSeries */
	public $miniSeries;
}
