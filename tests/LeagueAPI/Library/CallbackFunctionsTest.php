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


class CallbackFunctionsTest extends TestCase
{
	public function classCallback()
	{

	}

	public function testInit()
	{
		$functionCallback = function () {

		};

		$api = new LeagueAPI([
			LeagueAPI::SET_KEY              => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION           => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA   => true,
			LeagueAPI::SET_CALLBACKS_BEFORE => [
				[ $this, 'classCallback' ],
				$functionCallback,
				function() {

				},
			],
			LeagueAPI::SET_CALLBACKS_AFTER => [
				[ $this, 'classCallback' ],
				$functionCallback,
				function() {

				},
			],
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_noArray()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY              => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION           => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA   => true,
			LeagueAPI::SET_CALLBACKS_BEFORE => function() {

			},
			LeagueAPI::SET_CALLBACKS_AFTER => function() {

			},
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}


	public function dataProvider_invalid_callbacks()
	{
		return [
			[
				"INVALID BEFORE CALLBACK 1",
				"INVALID AFTER CALLBACK 1",
			],
			[
				function() {},
				125,
			],
			[
				[
					function() {},
					"INVALID BEFORE CALLBACK 1",
				],
				function() {},
			],
			[
				function() {},
				[
					function() {},
					"INVALID AFTER CALLBACK 1",
				],
			],
		];
	}

	/**
	 * @dataProvider dataProvider_invalid_callbacks
	 *
	 * @param $beforeCallbacks
	 * @param $afterCallbacks
	 *
	 * @return LeagueAPI
	 */
	public function testInit_invalid( $beforeCallbacks, $afterCallbacks )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is not valid.");

		new LeagueAPI([
			LeagueAPI::SET_KEY              => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION           => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA   => true,
			LeagueAPI::SET_CALLBACKS_BEFORE => $beforeCallbacks,
			LeagueAPI::SET_CALLBACKS_AFTER  => $afterCallbacks,
		]);
	}
}
