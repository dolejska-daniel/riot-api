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

use PHPUnit\Framework\TestCase;

use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Extensions\MasteryPagesDtoExtension;
use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\MasteryPageDto;
use RiotAPI\Objects\MasteryPagesDto;
use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\SettingsException;


class CallsCacheTest extends TestCase
{
	public function testInit_numeric()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_CACHE_CALLS    => true,
			RiotAPI::SET_CACHE_CALLS_LENGTH => 1,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInit_array()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_CACHE_CALLS    => true,
			RiotAPI::SET_CACHE_CALLS_LENGTH => [
				RiotAPI::RESOURCE_CHAMPION        => 60,
				RiotAPI::RESOURCE_CHAMPIONMASTERY => 60,
				RiotAPI::RESOURCE_LEAGUE          => 60,
				RiotAPI::RESOURCE_STATICDATA      => 360,
				RiotAPI::RESOURCE_STATUS          => 60,
				RiotAPI::RESOURCE_MASTERIES       => 60,
				//RiotAPI::RESOURCE_MATCH           => 60,
				RiotAPI::RESOURCE_RUNES           => 60,
				RiotAPI::RESOURCE_SPECTATOR       => 60,
				RiotAPI::RESOURCE_SUMMONER        => 60,
				RiotAPI::RESOURCE_TOURNAMENT      => 60,
				RiotAPI::RESOURCE_TOURNAMENT_STUB => 60,
			],
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}


	public function dataProvider_settings_length_invalid()
	{
		return [
			"String" => [ "INVALID PARAMETER" ],
			"Bool"   => [ false ],
			"Array #1" => [
				[
					RiotAPI::RESOURCE_CHAMPION   => "INVALID PARAMETER",
					RiotAPI::RESOURCE_STATICDATA => "INVALID PARAMETER",
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
					RiotAPI::RESOURCE_CHAMPION => 80,
					RiotAPI::RESOURCE_MATCH    => null,
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

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_CACHE_CALLS    => true,
			RiotAPI::SET_CACHE_CALLS_LENGTH => $callsLength,
		]);
	}
}
