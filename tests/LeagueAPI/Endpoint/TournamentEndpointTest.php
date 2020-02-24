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

declare(strict_types=1);

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects;
use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\RequestParameterException;


class TournamentEndpointTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_TOURNAMENT_KEY  => RiotAPITestCase::getApiTournamentKey(),
			LeagueAPI::SET_INTERIM         => false,
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_CACHE_RATELIMIT => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_teamSize_tooSmall(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Team size (teamSize) must be greater than or equal to 1.");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 0
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_teamSize_tooLarge(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Team size (teamSize) must be less than or equal to 5.");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 10
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_participants_notEnough(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Not enough players to fill teams (more participants required).");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5 ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_invalidPickType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of pick type (pickType) is invalid.");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'THIS_IS_INVALID_PICK_TYPE',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_invalidMapType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of map type (mapType) is invalid.");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'       => 'THIS_IS_INVALID_MAP_TYPE',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes_invalidSpectatorType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of spectator type (spectatorType) is invalid.");

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'THIS_IS_INVALID_SPECTATOR_TYPE',
			'teamSize'      => 5
		]);

		//  Get library processed results
		$api->createTournamentCodes(1, 10, $params);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testEditTournamentCode_invalidPickType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of pick type (pickType) is invalid.");

		$codeParams = new Objects\TournamentCodeUpdateParameters([
			'allowedParticipants' => implode(',', [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]),
			'mapType'             => 'SUMMONERS_RIFT',
			'pickType'            => 'THIS_IS_INVALID_PICK_TYPE',
			'spectatorType'       => 'ALL',
			'teamSize'            => 5
		]);

		//  Get library processed results
		$api->editTournamentCode('239d180f-fb8a-439e-85d9-95142e10b4f5', $codeParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testEditTournamentCode_invalidMapType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of map type (mapType) is invalid.");

		$codeParams = new Objects\TournamentCodeUpdateParameters([
			'allowedParticipants' => implode(',', [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]),
			'mapType'             => 'THIS_IS_INVALID_MAP_TYPE',
			'pickType'            => 'ALL_RANDOM',
			'spectatorType'       => 'ALL',
			'teamSize'            => 5
		]);

		//  Get library processed results
		$api->editTournamentCode('239d180f-fb8a-439e-85d9-95142e10b4f5', $codeParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testEditTournamentCode_invalidSpectatorType(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of spectator type (spectatorType) is invalid.");

		$codeParams = new Objects\TournamentCodeUpdateParameters([
			'allowedParticipants' => implode(',', [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]),
			'mapType'             => 'SUMMONERS_RIFT',
			'pickType'            => 'ALL_RANDOM',
			'spectatorType'       => 'THIS_IS_INVALID_SPECTATOR_TYPE',
			'teamSize'            => 5
		]);

		//  Get library processed results
		$api->editTournamentCode('239d180f-fb8a-439e-85d9-95142e10b4f5', $codeParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournament_emptyName(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Tournament name (name) may not be empty.");

		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 672,
			'name'       => '',
		]);

		//  Get library processed results
		$api->createTournament($tournamentParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournament_invalidProviderId(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("ProviderID (providerId) must be greater than or equal to 1.");

		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 0,
			'name'       => 'TestTournament',
		]);

		//  Get library processed results
		$api->createTournament($tournamentParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentProvider_emptyUrl(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Callback URL (url) may not be empty.");

		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => Region::EUROPE_EAST,
			'url'    => ''
		]);

		//  Get library processed results
		$api->createTournamentProvider($providerParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentProvider_invalidRegion(LeagueAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Value of region (region) is invalid.");

		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => 'THIS_IS_INVALID_REGION',
			'url'    => 'http://callbackurl.com'
		]);

		//  Get library processed results
		$api->createTournamentProvider($providerParams);
	}
}
