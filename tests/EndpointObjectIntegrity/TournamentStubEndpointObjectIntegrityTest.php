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

declare(strict_types=1);

use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\RequestException;


class TournamentStubEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
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
			'allowedSummonerIds' => new Objects\SummonerIdParams([
				'participants' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
			]),
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'ALL_RANDOM',
			'spectatorType' => 'ALL',
			'teamSize'      => 5
		]);

		//  Get library processed results
		/** @var array $result */
		$result = $api->createTournamentCodes_STUB(1132, 10, $params);
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
			'url'    => 'http://callbackurl.com'
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournamentProvider_STUB($providerParams);
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
			'providerId' => 672,
			'name'       => 'TestTournament',
		]);

		//  Get library processed results
		/** @var int $result */
		$result = $api->createTournament_STUB($tournamentParams);
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
		$result = $api->getTournamentLobbyEvents_STUB('EUNE1132-TOURNAMENTCODE0001');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}
}
