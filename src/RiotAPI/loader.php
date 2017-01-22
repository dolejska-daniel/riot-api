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

//  PHP version check
if (PHP_VERSION_ID < 70000)
	trigger_error('This library requires PHP version 7.0.0 or newer!', E_USER_ERROR);

//  Exceptions
require_once __DIR__ . '/Exceptions/APIException.php';
require_once __DIR__ . '/Exceptions/APILimitException.php';
require_once __DIR__ . '/Exceptions/GeneralException.php';

//  Definition interfaces
require_once __DIR__ . '/Definitions/IPlatform.php';
require_once __DIR__ . '/Definitions/IRegion.php';

//  Definitions
require_once __DIR__ . '/Definitions/Platform.php';
require_once __DIR__ . '/Definitions/Region.php';

//  Object interfaces
require_once __DIR__ . '/Objects/IApiObject.php';

//  Objects
require_once __DIR__ . '/Objects/ApiObject.php';
require_once __DIR__ . '/Objects/AggregatedStatsDto.php';
require_once __DIR__ . '/Objects/BannedChampion.php';
require_once __DIR__ . '/Objects/ChampionDto.php';
require_once __DIR__ . '/Objects/ChampionListDto.php';
require_once __DIR__ . '/Objects/ChampionMasteryDto.php';
require_once __DIR__ . '/Objects/ChampionStatsDto.php';
require_once __DIR__ . '/Objects/CurrentGameInfo.php';
require_once __DIR__ . '/Objects/CurrentGameParticipant.php';
require_once __DIR__ . '/Objects/Event.php';
require_once __DIR__ . '/Objects/FeaturedGameInfo.php';
require_once __DIR__ . '/Objects/FeaturedGames.php';
require_once __DIR__ . '/Objects/Frame.php';
require_once __DIR__ . '/Objects/GameDto.php';
require_once __DIR__ . '/Objects/Incident.php';
require_once __DIR__ . '/Objects/LeagueDto.php';
require_once __DIR__ . '/Objects/LeagueEntryDto.php';
require_once __DIR__ . '/Objects/LobbyEventDTO.php';
require_once __DIR__ . '/Objects/LobbyEventDTOWrapper.php';
require_once __DIR__ . '/Objects/Mastery.php';
require_once __DIR__ . '/Objects/MasteryDto.php';
require_once __DIR__ . '/Objects/MasteryPageDto.php';
require_once __DIR__ . '/Objects/MasteryPagesDto.php';
require_once __DIR__ . '/Objects/MatchDetail.php';
require_once __DIR__ . '/Objects/MatchList.php';
require_once __DIR__ . '/Objects/MatchReference.php';
require_once __DIR__ . '/Objects/Message.php';
require_once __DIR__ . '/Objects/MiniSeriesDto.php';
require_once __DIR__ . '/Objects/Observer.php';
require_once __DIR__ . '/Objects/Participant.php';
require_once __DIR__ . '/Objects/ParticipantFrame.php';
require_once __DIR__ . '/Objects/ParticipantIdentity.php';
require_once __DIR__ . '/Objects/ParticipantStats.php';
require_once __DIR__ . '/Objects/ParticipantTimeline.php';
require_once __DIR__ . '/Objects/ParticipantTimelineData.php';
require_once __DIR__ . '/Objects/Player.php';
require_once __DIR__ . '/Objects/PlayerDto.php';
require_once __DIR__ . '/Objects/PlayerStatsSummaryDto.php';
require_once __DIR__ . '/Objects/PlayerStatsSummaryListDto.php';
require_once __DIR__ . '/Objects/Position.php';
require_once __DIR__ . '/Objects/ProviderRegistrationParameters.php';
require_once __DIR__ . '/Objects/RankedStatsDto.php';
require_once __DIR__ . '/Objects/RawStatsDto.php';
require_once __DIR__ . '/Objects/RecentGamesDto.php';
require_once __DIR__ . '/Objects/Rune.php';
require_once __DIR__ . '/Objects/RunePageDto.php';
require_once __DIR__ . '/Objects/RunePagesDto.php';
require_once __DIR__ . '/Objects/RuneSlotDto.php';
require_once __DIR__ . '/Objects/Service.php';
require_once __DIR__ . '/Objects/Shard.php';
require_once __DIR__ . '/Objects/ShardStatus.php';
require_once __DIR__ . '/Objects/SummonerDto.php';
require_once __DIR__ . '/Objects/SummonerIdParams.php';
require_once __DIR__ . '/Objects/Team.php';
require_once __DIR__ . '/Objects/Timeline.php';
require_once __DIR__ . '/Objects/TournamentCodeParameters.php';
require_once __DIR__ . '/Objects/TournamentRegistrationParameters.php';
require_once __DIR__ . '/Objects/Translation.php';

//  Core class
require_once __DIR__ . '/RiotAPI.php';