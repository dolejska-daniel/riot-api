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


class StatusEndpointObjectTest extends RiotAPITestCase
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
	public function testGetShards( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\Shard[] $result */
		$result = $api->getShards();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\Shard::class);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetShardInfo( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\ShardStatus $result */
		$result = $api->getShardStatus(Region::EUROPE_EAST);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\ShardStatus::class);
	}
}
