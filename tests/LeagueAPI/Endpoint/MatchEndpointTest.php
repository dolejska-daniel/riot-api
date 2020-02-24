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


class MatchEndpointTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
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
	public function testGetMatch(LeagueAPI $api )
	{
		$match_id = 1730730260;
		//  Get library processed results
		/** @var Objects\MatchDto $result */
		$result = $api->getMatch($match_id);

		$this->assertEquals($match_id, $result->gameId);
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

		$this->assertTrue(true);
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

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetMatchlist(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchlistDto $result */
		$result = $api->getMatchlistByAccount("tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ");

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetMatchTimeline(LeagueAPI $api )
	{
		$match_id = 1730730260;
		//  Get library processed results
		/** @var Objects\MatchTimelineDto $result */
		$result = $api->getMatchTimeline($match_id);

		$this->assertTrue(true);
	}
}
