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

use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\GeneralException;


class RegionTest extends RiotAPITestCase
{
	public function testInit()
	{
		$obj = new Region();

		$this->assertInstanceOf(Region::class, $obj);

		return $obj;
	}

	/**
	 * @depends testInit
	 *
	 * @param Region $region
	 */
	public function testGetList( Region $region )
	{
		$regionList = $region->getList();
		$this->assertSame(Region::$list, $regionList);
	}

	/**
	 * @depends testInit
	 *
	 * @param Region $region
	 */
	public function testGetRegion( Region $region )
	{
		$regionName = $region->getRegionName(Region::EUROPE_EAST);
		$this->assertSame(Region::$list[Region::EUROPE_EAST], $regionName);
	}

	/**
	 * @depends testInit
	 *
	 * @param Region $region
	 */
	public function testGetRegion_Exception( Region $region )
	{
		$this->expectException(GeneralException::class);

		$region->getRegionName('MORDOR');
	}
}
