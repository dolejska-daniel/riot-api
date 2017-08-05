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

use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Definitions\Region;


class SummonerEndpointObjectIntegrityTest extends RiotAPITestCase
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
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerByAccount( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummonerByAccount(35545652);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check result validity
		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummonerByName( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummonerByName('I am TheKronnY');
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check result validity
		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetSummoner( RiotAPI $api )
	{
		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummoner(30904166);
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check result validity
		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
	}
}
