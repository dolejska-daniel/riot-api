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

use RiotAPI\Exceptions\RequestParameterException;


class LeagueEndpointTest extends RiotAPITestCase
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
	public function testGetLeaguesForSummoner( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto[] $result */
		$result = $api->getLeaguesForSummoner(30904166);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeaguePositionsForSummoner( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeaguePositionDto[] $result */
		$result = $api->getLeaguePositionsForSummoner(30904166);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueChallenger( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueChallenger('RANKED_SOLO_5x5');

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueMaster( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueMaster('RANKED_SOLO_5x5');

		$this->assertTrue(true);
	}
}
