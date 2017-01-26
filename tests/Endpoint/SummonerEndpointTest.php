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

use RiotAPI\Exception\RequestParameterException;
use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Definition\Region;


class SummonerEndpointTest extends RiotAPITestCase
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

	public static function randomString( $length = 8 ): string
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charsLength = strlen($chars);

		$s = '';
		for ($i = 0; $i < 8; $i++)
			$s.= $chars[rand(0, $charsLength-1)];
		return $s;
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonersByName_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Maximum allowed summoner name count is 40.");

		$summonerNames = [];
		for ($i = 1; $i <= 100; $i++)
			$summonerNames[] = self::randomString();
		$api->getSummonersByName($summonerNames);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerByName_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Summoner name list is not allowed by this function, please use 'getSummonersByName' function.");

		$summonerName = implode(',', [
			self::randomString(),
			self::randomString(),
			self::randomString(),
			self::randomString(),
		]);
		$api->getSummonerByName($summonerName);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummoners_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Maximum allowed summoner ID count is 40.");

		$summonerIds = range(30904166, 30904266);
		$api->getSummoners($summonerIds);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummoner( RiotAPI $api )
	{
		$this->assertTrue(true);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonersMasteries_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Maximum allowed summoner ID count is 40.");

		$summonerIds = range(30904166, 30904266);
		$api->getSummonersMasteries($summonerIds);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerMasteries( RiotAPI $api )
	{
		$this->assertTrue(true);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonersNames_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Maximum allowed summoner ID count is 40.");

		$summonerIds = range(30904166, 30904266);
		$api->getSummonersNames($summonerIds);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerName( RiotAPI $api )
	{
		$this->assertTrue(true);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonersRunes_Exception( RiotAPI $api )
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage("Maximum allowed summoner ID count is 40.");

		$summonerIds = range(30904166, 30904266);
		$api->getSummonersRunes($summonerIds);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerRunes( RiotAPI $api )
	{
		$this->assertTrue(true);
	}
}
