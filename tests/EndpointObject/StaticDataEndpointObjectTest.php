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
use RiotAPI\Objects\StaticData;
use RiotAPI\Definition\Region;


class StaticDataEndpointObjectTest extends RiotAPITestCase
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
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticChampions( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SChampionListDto $result */
		$result = $api->getStaticChampions(null, null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SChampionListDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticChampion( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SChampionDto $result */
		$result = $api->getStaticChampion(61, null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SChampionDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticItems( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SItemListDto $result */
		$result = $api->getStaticItems(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SItemListDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticItem( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SItemDto $result */
		$result = $api->getStaticItem(3089, null, null, 'all'); //  RABADON YAY
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SItemDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticLanguageStrings( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SLanguageStringsDto $result */
		$result = $api->getStaticLanguageStrings();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SLanguageStringsDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticLanguages( RiotAPI $api )
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
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticMaps( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SMapDataDto $result */
		$result = $api->getStaticMaps();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SMapDataDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticMasteries( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SMasteryListDto $result */
		$result = $api->getStaticMasteries(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SMasteryListDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticMastery( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SMasteryDto $result */
		$result = $api->getStaticMastery(6362, null, null, 'all'); //  THE LORD OF THUNDER
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SMasteryDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticRealm( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SRealmDto $result */
		$result = $api->getStaticRealm();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SRealmDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticRunes( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SRuneListDto $result */
		$result = $api->getStaticRunes(null, null, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SRuneListDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticRune( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SRuneDto $result */
		$result = $api->getStaticRune(5357, null, null, 'all'); //  GIMME MOAR AP
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SRuneDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticSummonerSpells( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SSummonerSpellListDto $result */
		$result = $api->getStaticSummonerSpells(null, null, false, 'all');
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SSummonerSpellListDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticSummonerSpell( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\SSummonerSpellDto $result */
		$result = $api->getStaticSummonerSpell(4, null, null, 'all'); //  JUST IN CASE?
		//  Get raw result
		$rawResult = $api->getResult();

		$this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SSummonerSpellDto::class);
	}

	/**
	 * @depends testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testgetStaticVersions( RiotAPI $api )
	{
		//  Get library processed results
		/** @var array $result */
		$result = $api->getStaticVersions();
		//  Get raw result
		$rawResult = $api->getResult();

		$this->assertSame($rawResult, $result, "List does not match original request result data!");
	}
}
