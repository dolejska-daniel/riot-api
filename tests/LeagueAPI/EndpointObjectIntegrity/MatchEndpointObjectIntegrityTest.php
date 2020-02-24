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


class MatchEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
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
	public function testGetMatch(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchDto $result */
		$result = $api->getMatch(1594938572);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetTournamentMatch(LeagueAPI $api )
	{
		$this->markTestIncomplete('No DummyData for this call yet.');

		//  Get library processed results
		/** @var Objects\MatchDto $result */
		$result = $api->getMatchByTournamentCode(2641970449, '239d180f-fb8a-439e-85d9-95142e10b4f5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetTournamentMatchIds(LeagueAPI $api )
	{
		$this->markTestIncomplete('No DummyData for this call yet.');

		//  Get library processed results
		/** @var array $result */
		$result = $api->getMatchIdsByTournamentCode('239d180f-fb8a-439e-85d9-95142e10b4f5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($rawResult, $result, 'Data do not match original request result data!');
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetMatchlist(LeagueAPI $api )
	{
		$accountId = "tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ";
		//  Get library processed results
		/** @var Objects\MatchlistDto $result */
		$result = $api->getMatchlistByAccount($accountId);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchlistDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetMatchTimeline(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchTimelineDto $result */
		$result = $api->getMatchTimeline(1730730260);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchTimelineDto::class);
	}
}
