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
 *   Class MatchDetail
 * This object contains match detail information
 *
 * Used in:
 *   match (v2.2)
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 *
 * @package RiotAPI\Objects
 */
class MatchDetail extends ApiObject
{
	/**
	 *   Match map ID.
	 *
	 * @var int $mapId
	 */
	public $mapId;

	/**
	 *   Match creation time. Designates when the team select lobby is created 
	 * and/or the match is made through match making, not when the game actually starts.
	 *
	 * @var int $matchCreation
	 */
	public $matchCreation;

	/**
	 *   Match duration.
	 *
	 * @var int $matchDuration
	 */
	public $matchDuration;

	/**
	 *   ID of the match.
	 *
	 * @var int $matchId
	 */
	public $matchId;

	/**
	 *   Match mode (Legal values: CLASSIC, ODIN, ARAM, TUTORIAL, ONEFORALL, 
	 * ASCENSION, FIRSTBLOOD, KINGPORO, SIEGE).
	 *
	 * @var string $matchMode
	 */
	public $matchMode;

	/**
	 *   Match type (Legal values: CUSTOM_GAME, MATCHED_GAME, TUTORIAL_GAME).
	 *
	 * @var string $matchType
	 */
	public $matchType;

	/**
	 *   Match version.
	 *
	 * @var string $matchVersion
	 */
	public $matchVersion;

	/**
	 *   Participant identity information.
	 *
	 * @var ParticipantIdentity[] $participantIdentities
	 */
	public $participantIdentities;

	/**
	 *   Participant information.
	 *
	 * @var Participant[] $participants
	 */
	public $participants;

	/**
	 *   Platform ID of the match.
	 *
	 * @var string $platformId
	 */
	public $platformId;

	/**
	 *   Match queue type (Legal values: CUSTOM, NORMAL_5x5_BLIND, RANKED_SOLO_5x5, 
	 * RANKED_PREMADE_5x5, BOT_5x5, NORMAL_3x3, RANKED_PREMADE_3x3, NORMAL_5x5_DRAFT, ODIN_5x5_BLIND, 
	 * ODIN_5x5_DRAFT, BOT_ODIN_5x5, BOT_5x5_INTRO, BOT_5x5_BEGINNER, BOT_5x5_INTERMEDIATE, 
	 * RANKED_TEAM_3x3, RANKED_TEAM_5x5, BOT_TT_3x3, GROUP_FINDER_5x5, ARAM_5x5, ONEFORALL_5x5, 
	 * FIRSTBLOOD_1x1, FIRSTBLOOD_2x2, SR_6x6, URF_5x5, ONEFORALL_MIRRORMODE_5x5, BOT_URF_5x5, 
	 * NIGHTMARE_BOT_5x5_RANK1, NIGHTMARE_BOT_5x5_RANK2, NIGHTMARE_BOT_5x5_RANK5, ASCENSION_5x5, HEXAKILL, 
	 * BILGEWATER_ARAM_5x5, KING_PORO_5x5, COUNTER_PICK, BILGEWATER_5x5, SIEGE, 
	 * DEFINITELY_NOT_DOMINION_5x5, ARURF_5X5, TEAM_BUILDER_DRAFT_UNRANKED_5x5, TEAM_BUILDER_DRAFT_RANKED_5x5, 
	 * TEAM_BUILDER_RANKED_SOLO, RANKED_FLEX_SR).
	 *
	 * @var string $queueType
	 */
	public $queueType;

	/**
	 *   Region where the match was played (Legal values: br, eune, euw, jp, kr, 
	 * lan, las, na, oce, ru, tr).
	 *
	 * @var string $region
	 */
	public $region;

	/**
	 *   Season match was played (Legal values: PRESEASON3, SEASON3, PRESEASON2014, 
	 * SEASON2014, PRESEASON2015, SEASON2015, PRESEASON2016, SEASON2016, PRESEASON2017, 
	 * SEASON2017).
	 *
	 * @var string $season
	 */
	public $season;

	/**
	 *   Team information.
	 *
	 * @var Team[] $teams
	 */
	public $teams;

	/**
	 *   Match timeline data (not included by default).
	 *
	 * @var Timeline $timeline
	 */
	public $timeline;
}
