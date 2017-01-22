<?php

/**
 * Copyright (C) 2016  Daniel Dolejška
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
 *   Class LeagueEntryDto
 * This object contains league participant information representing a summoner or team.
 *
 * @package RiotAPI\Objects
 */
class LeagueEntryDto extends ApiObject
{
	/**
	 * The league division of the participant.
	 * @var string $division
	 */
	public $division;

	/**
	 * Specifies if the participant is fresh blood.
	 * @var bool $isFreshBlood
	 */
	public $isFreshBlood;

	/**
	 * Specifies if the participant is on a hot streak.
	 * @var bool $isHotStreak
	 */
	public $isHotStreak;

	/**
	 * Specifies if the participant is inactive.
	 * @var bool $isInactive
	 */
	public $isInactive;

	/**
	 * Specifies if the participant is a veteran.
	 * @var bool $isVeteran
	 */
	public $isVeteran;

	/**
	 * The league points of the participant.
	 * @var int $leaguePoints
	 */
	public $leaguePoints;

	/**
	 * The number of losses for the participant.
	 * @var int $losses
	 */
	public $losses;

	/**
	 * Mini series data for the participant. Only present if the participant is currently in a mini series.
	 * @var MiniSeriesDto $miniSeries
	 */
	public $miniSeries;

	/**
	 * The ID of the participant (i.e., summoner or team) represented by this entry.
	 * @var string $playerOrTeamId
	 */
	public $playerOrTeamId;

	/**
	 * The name of the the participant (i.e., summoner or team) represented by this entry.
	 * @var string $playerOrTeamName
	 */
	public $playerOrTeamName;

	/**
	 * The playstyle of the participant. (Legal values: NONE, SOLO, SQUAD, TEAM)
	 * @var string $playstyle
	 */
	public $playstyle;

	/**
	 * The number of wins for the participant.
	 * @var int $wins
	 */
	public $wins;
}