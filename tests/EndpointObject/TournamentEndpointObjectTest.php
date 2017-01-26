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

declare(strict_types=1);

use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Definition\Region;

use RiotAPI\Exception\GeneralException;


class TournamentEndpointObjectTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY                => getenv('API_KEY'),
			RiotAPI::SET_TOURNAMENT_KEY     => getenv('API_KEY_TOURNAMENT'),
			RiotAPI::SET_TOURNAMENT_INTERIM => false,
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
			RiotAPI::SET_TOURNAMENT_KEY     => getenv('API_KEY_TOURNAMENT'),
			RiotAPI::SET_TOURNAMENT_INTERIM => true,
			RiotAPI::SET_REGION             => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA     => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentCodes( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('Not yet implemented.');

		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => new Objects\SummonerIdParams([
				'participants' => [0],
			]),
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->createTournamentCodes(1, 10, $params);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends      testInitInterim
	 * @dataProvider testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentCodes_Interim( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		$codeParams = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => new Objects\SummonerIdParams([
				'participants' => [0],
			]),
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->createTournamentCodes(1, 10, $codeParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentProvider( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('Not yet implemented.');

		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => Region::EUROPE_EAST,
			'url' => 'http://riottestapi.cz'
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournamentProvider($providerParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends      testInitInterim
	 * @dataProvider testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournamentProvider_Interim( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		$providerParams = new Objects\ProviderRegistrationParameters([
			'region' => Region::EUROPE_EAST,
			'url' => 'http://riottestapi.cz'
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournamentProvider($providerParams);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($result, $rawResult);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournament( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('Not yet implemented.');

		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 1,
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
	 * @depends      testInitInterim
	 * @dataProvider testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testCreateTournament_Interim( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		$tournamentParams = new Objects\TournamentRegistrationParameters([
			'providerId' => 1,
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
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentLobbyEvents( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('Not yet implemented.');

		//  Get library processed results
		/** @var Objects\LobbyEventDtoWrapper $result */
		$result = $api->getTournamentLobbyEvents('239d180f-fb8a-439e-85d9-95142e10b4f5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}

	/**
	 * @depends      testInitInterim
	 * @dataProvider testInitInterim
	 *
	 * @param RiotAPI $api
	 */
	public function testGetTournamentLobbyEvents_Interim( RiotAPI $api )
	{
		//  TODO
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		//  Get library processed results
		/** @var Objects\LobbyEventDtoWrapper $result */
		$result = $api->getTournamentLobbyEvents('239d180f-fb8a-439e-85d9-95142e10b4f5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}
}
