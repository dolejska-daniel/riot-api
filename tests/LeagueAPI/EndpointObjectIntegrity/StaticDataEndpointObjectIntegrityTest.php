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
use RiotAPI\LeagueAPI\Objects\StaticData;
use RiotAPI\LeagueAPI\Definitions\Region;


class StaticDataEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::NORTH_AMERICA,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_SAVE_DUMMY_DATA => getenv('SAVE_DUMMY_DATA') ?? false,
			LeagueAPI::SET_DATADRAGON_INIT => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticChampions_ById(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionListDto $result */
		$result = $api->getStaticChampions();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticChampions_ByKey(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionListDto $result */
		$result = $api->getStaticChampions(true);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticChampion(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionDto $result */
		$result = $api->getStaticChampion(61); //  Orianna <3
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticChampion_extended(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionDto $result */
		$result = $api->getStaticChampion(61, true); //  Orianna <3
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticItems(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemListDto $result */
		$result = $api->getStaticItems();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticItemListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticItem(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemDto $result */
		$result = $api->getStaticItem(3089); //  RABADON YAY
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticItemDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticLanguageStrings(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticLanguageStringsDto $result */
		$result = $api->getStaticLanguageStrings();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticLanguageStringsDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticLanguages(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var array $result */
		$result = $api->getStaticLanguages();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($rawResult, $result, "List does not match original request result data!");
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticMaps(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMapDataDto $result */
		$result = $api->getStaticMaps();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticMapDataDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticMasteries(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryListDto $result */
		$result = $api->getStaticMasteries('en_US', "6.24.1");
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticMasteryListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticMastery(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryDto $result */
		$result = $api->getStaticMastery(6362, 'en_US', "6.24.1"); //  THE LORD OF THUNDER
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticMasteryDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticProfileIcons(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticProfileIconDataDto $result */
		$result = $api->getStaticProfileIcons();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticProfileIconDataDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticRealm(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRealmDto $result */
		$result = $api->getStaticRealm();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticRealmDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticReforgedRunePaths(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticReforgedRunePathList $result */
		$result = $api->getStaticReforgedRunePaths();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticReforgedRunePathList::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticReforgedRunes(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticReforgedRuneList $result */
		$result = $api->getStaticReforgedRunes();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticReforgedRuneList::class);
	}


	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticRunes(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneListDto $result */
		$result = $api->getStaticRunes('en_US', "6.24.1");
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticRuneListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticRune(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneDto $result */
		$result = $api->getStaticRune(5357, 'en_US', "6.24.1"); //  GIMME MOAR AP
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticRuneDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticSummonerSpells_ById(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellListDto $result */
		$result = $api->getStaticSummonerSpells();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticSummonerSpellListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticSummonerSpells_ByKey(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellListDto $result */
		$result = $api->getStaticSummonerSpells(true);
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticSummonerSpellListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticSummonerSpell(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellDto $result */
		$result = $api->getStaticSummonerSpell(4); //  JUST IN CASE?
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticSummonerSpellDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetStaticVersions(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var array $result */
		$result = $api->getStaticVersions();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($rawResult, $result, "List does not match original request result data!");
	}
}
