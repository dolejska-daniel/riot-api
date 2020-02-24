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

use PHPUnit\Framework\TestCase;

use RiotAPI\LeagueAPI\Exceptions\ServerLimitException;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;


class RatelimitTest extends RiotAPITestCase
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
		$api->clearCache();

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testApiCall_Valid( LeagueAPI $api )
	{
		$data = $api->makeTestEndpointCall("slow");
		$this->assertEquals([], $data);
	}

	/**
	 * @depends testInit
	 * @depends testApiCall_Valid
	 *
	 * @param LeagueAPI $api
	 */
	public function testApiCall_Exception( LeagueAPI $api )
	{
		$this->expectException(ServerLimitException::class);
		$this->expectExceptionMessage("API call rate limit would be exceeded by this call.");

		$api->makeTestEndpointCall("slow");
	}

	/**
	 * @depends testInit
	 * @depends testApiCall_Exception
	 *
	 * @param LeagueAPI $api
	 */
	public function testApiCall_ExceptionTimeout( LeagueAPI $api )
	{
		sleep(1);

		$data = $api->makeTestEndpointCall("slow");
		$this->assertEquals([], $data);

		return $api;
	}
}
