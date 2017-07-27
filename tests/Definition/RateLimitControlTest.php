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

use RiotAPI\Definitions\Region;
use RiotAPI\Definitions\RateLimitControl;
use RiotAPI\Exceptions\SettingsException;


class RateLimitControlTest extends RiotAPITestCase
{
	public static $apiKey;

	public static $region;

	/**
	 * @after serialize
	 */
	public function testInit()
	{
		self::$apiKey = getenv("API_KEY");
		self::$region = Region::EUROPE_EAST;

		$obj = new RateLimitControl(new Region());

		$this->assertInstanceOf(RateLimitControl::class, $obj);

		return $obj;
	}

	/**
	 * @depends testInit
	 *
	 * @param RateLimitControl $control
	 */
	public function testSetLimits_Valid( RateLimitControl $control )
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
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
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');

		$this->assertTrue($control->canCall(self::$apiKey, self::$region));

		return $control;
	}

	/**
	 * @depends testRegisterCall
	 *
	 * @param RateLimitControl $control
	 */
	public function testCanCall_False( RateLimitControl $control )
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');

		$this->assertTrue($control->canCall(self::$apiKey, self::$region));
	}

	/**
	 * @depends testCanCall_False
	 *
	 * @runInSeparateProcess
	 * @param RateLimitControl $control
	 */
	public function testCanCall_TrueExpired( RateLimitControl $control )
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');

		$this->assertFalse($control->canCall(self::$apiKey, self::$region));
		while ($control->canCall(self::$apiKey, self::$region));
		$this->assertTrue($control->canCall(self::$apiKey, self::$region));
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
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');

		$control->registerCall(self::$apiKey, self::$region, "1:1,1:10");
		return $control;
	}
}
