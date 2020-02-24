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

use RiotAPI\LeagueAPI\Exceptions\RequestException;


class TournamentStubEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_TOURNAMENT_KEY  => RiotAPITestCase::getApiTournamentKey(),
			LeagueAPI::SET_INTERIM         => true,
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_SAVE_DUMMY_DATA => getenv('SAVE_DUMMY_DATA') ?? false,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentCodes(LeagueAPI $api )
	{
		$params = new Objects\TournamentCodeParameters([
			'allowedSummonerIds' => [
				"wk07XyBvsx0tT6qVY1qKeH-PqCJgEFwKecEfew",
				"AOc8l0ucYYH2UPDY_QFSsRMvnkc7xDH3zf9-Bg",
				"w7j8mTu_r1FFx9Z46l-qW-ryNlQM_3KyI5JTRA",
				"RvCDqKrSsX3b2j7Q-666QvoTR54tH4c3ZF0e9w",
				"CW_QDuT7YoVesISw_7lw7g5roBxjjDVkX6RmEQ",
				"0s4wsmI0ruT6y3YKOZNH1MEqyq64nYfNEeyk_g",
				"UO4uklosNOReoERwfrXEHwnmCSR4_znfOgk11A",
				"AdyAWnr4FwFWutSPvsFFRxUWA_PQ3vSvvsi1TA",
				"kOokFfNudFZ8pM7Tvoo_dm1pXVBps_zSJ37AVg",
				"oI2eFR83UGC3N5OltKkz3NSP_Th_C8nw_0sGcw"
			],
			'mapType'            => 'SUMMONERS_RIFT',
			'pickType'           => 'ALL_RANDOM',
			'spectatorType'      => 'ALL',
			'teamSize'           => 5
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
	 * @param LeagueAPI $api
	 */
	public function testCreateTournamentProvider(LeagueAPI $api )
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
	 * @param LeagueAPI $api
	 */
	public function testCreateTournament(LeagueAPI $api )
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
	 * @param LeagueAPI $api
	 */
	public function testGetTournamentLobbyEvents(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LobbyEventDtoWrapper $result */
		$result = $api->getTournamentLobbyEvents_STUB('EUNE1132-TOURNAMENTCODE0001');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LobbyEventDtoWrapper::class);
	}
}
