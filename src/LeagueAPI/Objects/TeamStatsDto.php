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
 *   Class TeamStatsDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class TeamStatsDto extends ApiObject
{
	/**
	 *   Number of towers the team destroyed.
	 *
	 * @var int $towerKills
	 */
	public $towerKills;

	/**
	 *   Number of times the team killed Rift Herald.
	 *
	 * @var int $riftHeraldKills
	 */
	public $riftHeraldKills;

	/**
	 *   Flag indicating whether or not the team scored the first blood.
	 *
	 * @var bool $firstBlood
	 */
	public $firstBlood;

	/**
	 *   Number of inhibitors the team destroyed.
	 *
	 * @var int $inhibitorKills
	 */
	public $inhibitorKills;

	/**
	 *   If match queueId has a draft, contains banned champion data, otherwise 
	 * empty.
	 *
	 * @var TeamBansDto[] $bans
	 */
	public $bans;

	/**
	 *   Flag indicating whether or not the team scored the first Baron kill.
	 *
	 * @var bool $firstBaron
	 */
	public $firstBaron;

	/**
	 *   Flag indicating whether or not the team scored the first Dragon kill.
	 *
	 * @var bool $firstDragon
	 */
	public $firstDragon;

	/**
	 *   For Dominion matches, specifies the points the team had at game end.
	 *
	 * @var int $dominionVictoryScore
	 */
	public $dominionVictoryScore;

	/**
	 *   Number of times the team killed Dragon.
	 *
	 * @var int $dragonKills
	 */
	public $dragonKills;

	/**
	 *   Number of times the team killed Baron.
	 *
	 * @var int $baronKills
	 */
	public $baronKills;

	/**
	 *   Flag indicating whether or not the team destroyed the first inhibitor.
	 *
	 * @var bool $firstInhibitor
	 */
	public $firstInhibitor;

	/**
	 *   Flag indicating whether or not the team destroyed the first tower.
	 *
	 * @var bool $firstTower
	 */
	public $firstTower;

	/**
	 *   Number of times the team killed Vilemaw.
	 *
	 * @var int $vilemawKills
	 */
	public $vilemawKills;

	/**
	 *   Flag indicating whether or not the team scored the first Rift Herald kill.
	 *
	 * @var bool $firstRiftHerald
	 */
	public $firstRiftHerald;

	/**
	 *   100 for blue side. 200 for red side.
	 *
	 * @var int $teamId
	 */
	public $teamId;

	/**
	 *   String indicating whether or not the team won. There are only two values 
	 * visibile in public match history. (Legal values: Fail, Win).
	 *
	 * @var string $win
	 */
	public $win;
}
