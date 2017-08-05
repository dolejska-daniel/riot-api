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


class StaticDataEndpointTest extends RiotAPITestCase
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
	public function testGetStaticChampions_ChampionData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionListDto $result */
		$result = $api->getStaticChampions(null, null, null, ['skins', 'image']);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticChampion_ChampionData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticChampionDto $result */
		$result = $api->getStaticChampion(61, null, null, ['skins', 'image']);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticItems_ItemListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemListDto $result */
		$result = $api->getStaticItems(null, null, ['gold', 'image']);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticItem_ItemListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticItemDto $result */
		$result = $api->getStaticItem(3089, null, null, ['gold', 'image']); //  RABADON YAY

		$this->assertTrue(true);
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
		$result = $api->getStaticLanguageStrings('cs_CZ', null);

		$this->assertTrue(true);
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

		$this->assertTrue(true);
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
		$result = $api->getStaticMaps(null, null);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticMasteries_MasteryListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryListDto $result */
		$result = $api->getStaticMasteries(null, null, ['masteryTree', 'image']);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticMastery_MasteryListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticMasteryDto $result */
		$result = $api->getStaticMastery(6362, null, null, ['masteryTree', 'image']); //  THE LORD OF THUNDER

		$this->assertTrue(true);
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

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticRunes_RuneListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneListDto $result */
		$result = $api->getStaticRunes(null, null, ['stats', 'image']);

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticRune_RuneListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticRuneDto $result */
		$result = $api->getStaticRune(5357, null, null, ['stats', 'image']); //  GIMME MOAR AP

		$this->assertTrue(true);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticSummonerSpells_SpellListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellListDto $result */
		$result = $api->getStaticSummonerSpells(null, null, true, ['vars', 'image']);

		$this->assertSame('Exhaust', $result->data[3]->name);
		$this->assertSame('SummonerExhaust', $result->data[3]->key);
		$this->assertSame('Exhausts target enemy champion, reducing their Movement Speed by 30%, and their damage dealt by 40% for 2.5 seconds.', $result->data[3]->description);

		$this->assertSame('Flash', $result->data[4]->name);
		$this->assertSame('SummonerFlash', $result->data[4]->key);
		$this->assertSame('Teleports your champion a short distance toward your cursor\'s location.', $result->data[4]->description);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testGetStaticSummonerSpell_SpellListData( RiotAPI $api )
	{
		//  Get library processed results
		/** @var StaticData\StaticSummonerSpellDto $result */
		$result = $api->getStaticSummonerSpell(4, null, null, ['vars', 'image']); //  JUST IN CASE?

		$this->assertSame('Flash', $result->name);
		$this->assertSame('SummonerFlash', $result->key);
		$this->assertSame('Teleports your champion a short distance toward your cursor\'s location.', $result->description);
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

		$this->assertTrue(true);
	}
}
