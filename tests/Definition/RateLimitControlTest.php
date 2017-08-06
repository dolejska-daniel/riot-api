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

use RiotAPI\Definitions\Region;
use RiotAPI\Definitions\RateLimitControl;
use RiotAPI\Exceptions\SettingsException;
use RiotAPI\RiotAPI;


class RateLimitControlTest extends RiotAPITestCase
{
	public static $apiKey;

	public static $region;

	public static $endpoint;

	public static $resource;

	public static $app_limit_header;

	public static $app_count_header;

	public static $method_limit_header;

	public static $method_count_header;

	/**
	 * @after serialize
	 */
	public function testInit()
	{
		self::$apiKey   = getenv("API_KEY");
		self::$region   = Region::EUROPE_EAST;
		self::$endpoint = RiotAPI::RESOURCE_CHAMPION . "/champions";
		self::$resource = RiotAPI::RESOURCE_CHAMPION;

		self::$app_limit_header = "1:1,10:10";
		self::$app_count_header = "1:1,1:10";

		self::$method_limit_header = "100:1,10000:10";
		self::$method_count_header = "1:1,1:10";

		$obj = new RateLimitControl(new Region());

		$this->assertInstanceOf(RateLimitControl::class, $obj);

		return $obj;
	}

	/**
	 * @depends testInit
	 *
	 * @param RateLimitControl $control
	 */
	public function testSetLimits( RateLimitControl $control )
	{
		$control->registerLimits(self::$apiKey, self::$region, self::$endpoint, self::$app_limit_header, self::$method_limit_header);
	}

	/**
	 * @depends testInit
	 *
	 * @param RateLimitControl $control
	 *
	 * @return RateLimitControl
	 */
	public function testCanCall_True( RateLimitControl $control )
	{
		$this->assertTrue($control->canCall(self::$apiKey, self::$region, self::$resource, self::$endpoint));

		return $control;
	}

	/**
	 * @depends testCanCall_True
	 *
	 * @param RateLimitControl $control
	 *
	 * @return RateLimitControl
	 */
	public function testRegisterCall( RateLimitControl $control )
	{
		$control->registerCall(self::$apiKey, self::$region, self::$endpoint, self::$app_count_header, self::$method_count_header);
		return $control;
	}

	/**
	 * @depends testRegisterCall
	 *
	 * @param RateLimitControl $control
	 *
	 * @return RateLimitControl
	 */
	public function testCanCall_False( RateLimitControl $control )
	{
		$this->assertFalse($control->canCall(self::$apiKey, self::$region, self::$resource, self::$endpoint));
		return $control;
	}

	/**
	 * @depends testCanCall_False
	 *
	 * @param RateLimitControl $control
	 */
	public function testCanCall_TrueExpired( RateLimitControl $control )
	{
		$this->assertFalse($control->canCall(self::$apiKey, self::$region, self::$resource, self::$endpoint));
		while ($control->canCall(self::$apiKey, self::$region, self::$resource, self::$endpoint) == false);
		$this->assertTrue($control->canCall(self::$apiKey, self::$region, self::$resource, self::$endpoint));
	}
}
