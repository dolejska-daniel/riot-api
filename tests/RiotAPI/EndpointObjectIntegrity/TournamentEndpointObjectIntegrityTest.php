<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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

use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\RequestException;


class TournamentEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY                => getenv('API_KEY'),
			RiotAPI::SET_TOURNAMENT_KEY     => getenv('API_TOURNAMENT_KEY'),
			RiotAPI::SET_INTERIM            => false,
			RiotAPI::SET_REGION             => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA     => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInitInterim()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY                => getenv('API_KEY'),
			RiotAPI::SET_TOURNAMENT_KEY     => getenv('API_TOURNAMENT_KEY'),
			RiotAPI::SET_INTERIM            => true,
			RiotAPI::SET_REGION             => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA     => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentCodes( RiotAPI $api )
	{
		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'            => 'SUMMONERS_RIFT',
			'pickType'           => 'ALL_RANDOM',
			'spectatorType'      => 'ALL',
			'teamSize'           => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->createTournamentCodes(465010, 1, $params);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentCodes_Interim( RiotAPI $api )
	{
		$codeParams = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'            => 'SUMMONERS_RIFT',
			'pickType'           => 'ALL_RANDOM',
			'spectatorType'      => 'ALL',
			'teamSize'           => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->createTournamentCodes(1132, 10, $codeParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentProvider( RiotAPI $api )
	{
		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => Region::EUROPE_EAST,
			'url'    => 'https://github.com/dolejska-daniel/riot-api'
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournamentProvider($providerParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentProvider_Interim( RiotAPI $api )
	{
		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => Region::EUROPE_EAST,
			'url'    => 'https://github.com/dolejska-daniel/riot-api'
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournamentProvider($providerParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournament( RiotAPI $api )
	{
		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 3339,
			'name'       => 'TestTournament',
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournament($tournamentParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournament_Interim( RiotAPI $api )
	{
		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 672,
			'name'       => 'TestTournament',
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournament($tournamentParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentLobbyEvents( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LobbyEventDtoWrapper $result */
		$result = $api->getTournamentLobbyEvents('EUNE045c8-8f1f371e-dbc3-494c-8dd5-c5a3acf89506');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentLobbyEvents_Interim( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LobbyEventDtoWrapper $result */
		$result = $api->getTournamentLobbyEvents('EUNE1132-TOURNAMENTCODE0001');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testEditTournamentCode( RiotAPI $api )
	{
		$codeParams = new Objects\TournamentCodeUpdateParameters([
			'allowedParticipants' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'             => 'SUMMONERS_RIFT',
			'pickType'            => 'ALL_RANDOM',
			'spectatorType'       => 'ALL',
			'teamSize'            => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->editTournamentCode('EUNE045c8-8f1f371e-dbc3-494c-8dd5-c5a3acf89506', $codeParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testEditTournamentCode_Interim( RiotAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("This endpoint is not available in interim mode.");

		$codeParams = new Objects\TournamentCodeUpdateParameters([
			'allowedParticipants' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			'mapType'             => 'SUMMONERS_RIFT',
			'pickType'            => 'ALL_RANDOM',
			'spectatorType'       => 'ALL',
			'teamSize'            => 5
		]);

		//  Get library processed results
		$api->editTournamentCode('EUNE045c8-8f1f371e-dbc3-494c-8dd5-c5a3acf89506', $codeParams);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentCodeData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\TournamentCodeDto $result */
		$result = $api->getTournamentCodeData('EUNE045c8-8f1f371e-dbc3-494c-8dd5-c5a3acf89506');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\TournamentCodeDto::class);
	}

	/**
	 * @depends testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentCodeData_Interim( RiotAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("This endpoint is not available in interim mode.");

		//  Get library processed results
		$api->getTournamentCodeData('EUNE045c8-8f1f371e-dbc3-494c-8dd5-c5a3acf89506');
	}
}
