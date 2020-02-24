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

use RiotAPI\LeagueAPI\Definitions\Platform;
use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\GeneralException;


class PlatformTest extends RiotAPITestCase
{
	public function testInit()
	{
		$obj = new Platform();

		$this->assertInstanceOf(Platform::class, $obj);

		return $obj;
	}

	/**
	 * @depends testInit
	 *
	 * @param Platform $region
	 */
	public function testGetList( Platform $region )
	{
		$regionList = $region->getList();
		$this->assertSame(Platform::$list, $regionList);
	}

	/**
	 * @depends testInit
	 *
	 * @param Platform $region
	 */
	public function testGetPlatform( Platform $region )
	{
		$regionName = $region->getPlatformName(Region::EUROPE_EAST);
		$this->assertSame(Platform::$list[Region::EUROPE_EAST], $regionName);
	}

	/**
	 * @depends testInit
	 *
	 * @param Platform $region
	 */
	public function testGetPlatform_Exception( Platform $region )
	{
		$this->expectException(GeneralException::class);

		$region->getPlatformName('MORDOR');
	}
}
