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


class LeagueEndpointObjectTest extends RiotAPITestCase
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
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueMappingBySummoners( RiotAPI $api )
	{
		$summonerIds = [
			19827622,
			32473526,
			34937794,
			36615528,
		];

		//  Get library processed results
		/** @var array $result */
		$result = $api->getLeagueMappingBySummoners($summonerIds);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check if all the data were successfully processed
		$this->assertSameSize($result, $rawResult, "Object list count does not match original request result data count!");

		foreach ($result as $summonerId => $leagues)
		{
			$this->assertArrayHasKey($summonerId, $rawResult, "Object identifier is not valid! It does not match original request result data identifier!");
			$this->checkObjectPropertiesAndDataValidityOfObjectList($leagues, $rawResult[$summonerId], Objects\LeagueDto::class);
		}
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueMappingBySummoner( RiotAPI $api )
	{
		$summonerId = 19827622;

		//  Get library processed results
		/** @var Objects\LeagueDto[] $result */
		$result = $api->getLeagueMappingBySummoner($summonerId);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check if all the data were successfully processed
		$this->assertArrayHasKey($summonerId, $rawResult);
		$rawResult = $rawResult[$summonerId];

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\LeagueDto::class);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueEntryBySummoners( RiotAPI $api )
	{
		$summonerIds = [
			19827622,
			32473526,
			34937794,
			36615528,
		];

		//  Get library processed results
		/** @var array $result */
		$result = $api->getLeagueEntryBySummoners($summonerIds);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check if all the data were successfully processed
		$this->assertSameSize($result, $rawResult, "Object list count does not match original request result data count!");

		foreach ($result as $summonerId => $leagues)
		{
			$this->assertArrayHasKey($summonerId, $rawResult, "Object identifier is not valid! It does not match original request result data identifier!");
			$this->checkObjectPropertiesAndDataValidityOfObjectList($leagues, $rawResult[$summonerId], Objects\LeagueDto::class);
		}
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueEntryBySummoner( RiotAPI $api )
	{
		$summonerId = 19827622;

		//  Get library processed results
		/** @var Objects\LeagueDto[] $result */
		$result = $api->getLeagueEntryBySummoner($summonerId);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check if all the data were successfully processed
		$this->assertArrayHasKey($summonerId, $rawResult);
		$rawResult = $rawResult[$summonerId];

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\LeagueDto::class);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueMappingChallenger( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueDto $result */
		$result = $api->getLeagueMappingChallenger('RANKED_SOLO_5x5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueDto::class);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetLeagueMappingMaster( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueDto $result */
		$result = $api->getLeagueMappingMaster('RANKED_SOLO_5x5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueDto::class);
	}
}
