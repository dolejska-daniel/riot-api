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


class MatchEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetMatch( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetTournamentMatch( RiotAPI $api )
	{
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
	 * @param RiotAPI $api
	 */
	public function testGetTournamentMatchIds( RiotAPI $api )
	{
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
	 * @param RiotAPI $api
	 */
	public function testGetMatchlist( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchlistDto $result */
		$result = $api->getMatchlistByAccount(35545652);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchlistDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetRecentMatchlist( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchlistDto $result */
		$result = $api->getRecentMatchlistByAccount(35545652);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchlistDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetMatchTimeline( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\MatchTimelineDto $result */
		$result = $api->getMatchTimeline(1730730260);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MatchTimelineDto::class);
	}
}
