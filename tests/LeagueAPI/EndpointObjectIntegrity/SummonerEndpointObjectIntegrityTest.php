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

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects;
use RiotAPI\LeagueAPI\Definitions\Region;


class SummonerEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_SAVE_DUMMY_DATA => getenv('SAVE_DUMMY_DATA') ?? false,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummonerByAccountId(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummonerByAccountId("R6fx3_ynno6O06vJb2N1EmfsIIIdJsAFctOSkzsvId5QHA");
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check result validity
		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummonerByName(LeagueAPI $api )
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
	 * @param LeagueAPI $api
	 */
	public function testGetSummoner(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummoner("Tl4pOlcp2vxCpPp9wVNjTuoMzpb8N6gLgMZiJwOL2JCkdlY");
		//  Get raw result
		$rawResult = $api->getResult();

		//  Check result validity
		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
	}
}
