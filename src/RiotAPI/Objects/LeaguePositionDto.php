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
 *   Class LeaguePositionDto
 *
 * Used in:
 *   league (v3)
 *     @link https://developer.riotgames.com/api-methods/#league-v3/GET_getAllLeaguePositionsForSummoner
 *
 * @package RiotAPI\Objects
 */
class LeaguePositionDto extends ApiObject
{
	/** @var string $rank */
	public $rank;

	/** @var string $queueType */
	public $queueType;

	/** @var bool $hotStreak */
	public $hotStreak;

	/** @var MiniSeriesDTO $miniSeries */
	public $miniSeries;

	/** @var int $wins */
	public $wins;

	/** @var bool $veteran */
	public $veteran;

	/** @var int $losses */
	public $losses;

	/** @var string $playerOrTeamId */
	public $playerOrTeamId;

	/** @var string $leagueName */
	public $leagueName;

	/** @var string $playerOrTeamName */
	public $playerOrTeamName;

	/** @var bool $inactive */
	public $inactive;

	/** @var bool $freshBlood */
	public $freshBlood;

	/** @var string $tier */
	public $tier;

	/** @var int $leaguePoints */
	public $leaguePoints;
}
