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


class LeagueEndpointObjectIntegrityTest extends RiotAPITestCase
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
	public function testGetLeaguePositionsForSummoner(LeagueAPI $api )
	{
		$summonerId = "KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8";
		//  Get library processed results
		/** @var Objects\LeaguePositionDto[] $result */
		$result = $api->getLeaguePositionsForSummoner($summonerId);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\LeaguePositionDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetLeagueEntries(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueEntryDto[] $result */
		$result = $api->getLeagueEntries("RANKED_SOLO_5x5", "DIAMOND", "IV");
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\LeagueEntryDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetLeagueEntriesForSummoner(LeagueAPI $api )
	{
		$summonerId = "KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8";
		//  Get library processed results
		/** @var Objects\LeagueEntryDto[] $result */
		$result = $api->getLeagueEntriesForSummoner($summonerId);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\LeagueEntryDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetLeagueChallenger(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueChallenger('RANKED_SOLO_5x5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueListDto::class);

		return $result;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetLeagueGrandmaster(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueGrandmaster('RANKED_SOLO_5x5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueListDto::class);

		return $result;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetLeagueMaster(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueChallenger('RANKED_SOLO_5x5');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueListDto::class);

		return $result;
	}

	/**
	 * @depends testInit
	 * @depends testGetLeagueChallenger
	 *
	 * @param LeagueAPI $api
	 * @param string  $league_id
	 */
	public function testGetLeagueById(LeagueAPI $api, Objects\LeagueListDto $leagueListDto )
	{
		//  Get library processed results
		/** @var Objects\LeagueListDto $result */
		$result = $api->getLeagueById($leagueListDto->leagueId);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\LeagueListDto::class);
	}
}
