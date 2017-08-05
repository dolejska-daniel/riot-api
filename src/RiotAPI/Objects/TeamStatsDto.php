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
 *   Class TeamStatsDto
 *
 * Used in:
 *   match (v3)
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatch
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\Objects
 */
class TeamStatsDto extends ApiObject
{
	/** @var bool $firstDragon */
	public $firstDragon;

	/** @var bool $firstInhibitor */
	public $firstInhibitor;

	/** @var TeamBansDto[] $bans */
	public $bans;

	/** @var int $baronKills */
	public $baronKills;

	/** @var bool $firstRiftHerald */
	public $firstRiftHerald;

	/** @var bool $firstBaron */
	public $firstBaron;

	/** @var int $riftHeraldKills */
	public $riftHeraldKills;

	/** @var bool $firstBlood */
	public $firstBlood;

	/** @var int $teamId */
	public $teamId;

	/** @var bool $firstTower */
	public $firstTower;

	/** @var int $vilemawKills */
	public $vilemawKills;

	/** @var int $inhibitorKills */
	public $inhibitorKills;

	/** @var int $towerKills */
	public $towerKills;

	/** @var int $dominionVictoryScore */
	public $dominionVictoryScore;

	/** @var string $win */
	public $win;

	/** @var int $dragonKills */
	public $dragonKills;
}
