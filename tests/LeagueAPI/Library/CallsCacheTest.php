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

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\SettingsException;


class CallsCacheTest extends TestCase
{
	public function testInit_simple()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_CACHE_CALLS    => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_numeric()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_CACHE_CALLS    => true,
			LeagueAPI::SET_CACHE_CALLS_LENGTH => 1,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_array()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_CACHE_CALLS    => true,
			LeagueAPI::SET_CACHE_CALLS_LENGTH => [
				LeagueAPI::RESOURCE_CHAMPION        => 60,
				LeagueAPI::RESOURCE_CHAMPIONMASTERY => 60,
				LeagueAPI::RESOURCE_LEAGUE          => 60,
				LeagueAPI::RESOURCE_STATICDATA      => 360,
				LeagueAPI::RESOURCE_STATUS          => 60,
				//LeagueAPI::RESOURCE_MATCH           => 60,
				LeagueAPI::RESOURCE_SPECTATOR       => 60,
				LeagueAPI::RESOURCE_SUMMONER        => 60,
				LeagueAPI::RESOURCE_TOURNAMENT      => 60,
				LeagueAPI::RESOURCE_TOURNAMENT_STUB => 60,
			],
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}


	public function dataProvider_settings_length_invalid()
	{
		return [
			"String" => [ "INVALID PARAMETER" ],
			"Bool"   => [ false ],
			"Array #1" => [
				[
					LeagueAPI::RESOURCE_CHAMPION   => "INVALID PARAMETER",
					LeagueAPI::RESOURCE_STATICDATA => "INVALID PARAMETER",
				]
			],
			"Array #2" => [
				[
					"INVALID RESOURCE 1" => 60,
					"INVALID RESOURCE 2" => 360,
				]
			],
			"Array #3" => [
				[
					LeagueAPI::RESOURCE_CHAMPION => 80,
					LeagueAPI::RESOURCE_MATCH    => null,
					"INVALID RESOURCE 1"       => 60,
				]
			],
		];
	}

	/**
	 * @dataProvider dataProvider_settings_length_invalid
	 *
	 * @param $callsLength
	 */
	public function testInit_settings_length_invalid( $callsLength )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is not valid.");

		new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_CACHE_CALLS    => true,
			LeagueAPI::SET_CACHE_CALLS_LENGTH => $callsLength,
		]);
	}
}
