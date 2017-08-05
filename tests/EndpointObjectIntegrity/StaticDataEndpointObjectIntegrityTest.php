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
use RiotAPI\Objects\StaticData;
use RiotAPI\Definitions\Region;


class StaticDataEndpointObjectIntegrityTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::NORTH_AMERICA,
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
	public function testGetStaticChampions( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionListDto $result */
		$result = $api->getStaticChampions(null, null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticChampion( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionDto $result */
		$result = $api->getStaticChampion(61, null, null, 'all'); //  Orianna <3
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticChampionDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticItems( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemListDto $result */
		$result = $api->getStaticItems(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticItemListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticItem( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemDto $result */
		$result = $api->getStaticItem(3089, null, null, 'all'); //  RABADON YAY
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticItemDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticLanguageStrings( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetStaticLanguages( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetStaticMaps( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetStaticMasteries( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryListDto $result */
		$result = $api->getStaticMasteries(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticMasteryListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticMastery( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryDto $result */
		$result = $api->getStaticMastery(6362, null, null, 'all'); //  THE LORD OF THUNDER
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticMasteryDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticProfileIcons( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetStaticRealm( RiotAPI $api )
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
	 * @param RiotAPI $api
	 */
	public function testGetStaticRunes( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneListDto $result */
		$result = $api->getStaticRunes(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticRuneListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticRune( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneDto $result */
		$result = $api->getStaticRune(5357, null, null, 'all'); //  GIMME MOAR AP
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticRuneDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticSummonerSpells( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellListDto $result */
		$result = $api->getStaticSummonerSpells(null, null, false, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticSummonerSpellListDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticSummonerSpell( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellDto $result */
		$result = $api->getStaticSummonerSpell(4, null, null, 'all'); //  JUST IN CASE?
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\StaticSummonerSpellDto::class);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticVersions( RiotAPI $api )
	{
		//  Get library processed results
		/** @var array $result */
		$result = $api->getStaticVersions();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($rawResult, $result, "List does not match original request result data!");
	}
}
