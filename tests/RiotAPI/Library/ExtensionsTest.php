<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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

use PHPUnit\Framework\TestCase;

use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Extensions\ChampionListDtoExtension;
use RiotAPI\Objects\ChampionDto;
use RiotAPI\Objects\ChampionListDto;
use RiotAPI\Objects\IApiObject;
use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\SettingsException;


abstract class NoninstantiableExtension implements \RiotAPI\Objects\IApiObjectExtension
{
	public function __construct( IApiObject &$apiObject, RiotAPI &$api ) {}
}


class ExtensionsTest extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => [
				ChampionListDto::class => ChampionListDtoExtension::class,
			],
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInit_noExtension()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInit_settings_invalid_Value()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Value of settings parameter");

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => IApiObject::class,
		]);
	}

	public function testInit_settings_invalid_ClassInterface()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('does not implement IApiObjectExtension interface');

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => [
				ChampionListDto::class => IApiObject::class,
			],
		]);
	}

	public function testInit_settings_invalid_NoninstantiableClass()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('is not instantiable');

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => [
				ChampionListDto::class => NoninstantiableExtension::class,
			],
		]);
	}

	public function testInit_settings_invalid_Class()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('is not valid');

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => [
				ChampionListDto::class => "InvalidClass",
			],
		]);
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCallExtensionFunction_Valid( RiotAPI $api )
	{
		$champions = $api->getChampions();

		$this->assertTrue($champions->isActive(61));
		$this->assertNull($champions->isActive(1347843));

		$this->assertTrue($champions->isRankedEnabled(61));
		$this->assertNull($champions->isRankedEnabled(42487643));

		$this->assertFalse($champions->isFreeToPlay(61));
		$this->assertNull($champions->isFreeToPlay(55645198));

		$this->assertInstanceOf(ChampionDto::class, $champions->getById(61));
		$this->assertNull($champions->getById(814684753));
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testCallExtensionFunction_Invalid( RiotAPI $api )
	{
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('failed to be executed');

		$masteryPages = $api->getChampions();
		$masteryPages->invalidFunction();
	}

	/**
	 * @depends testInit_noExtension
	 *
	 * @param RiotAPI $api
	 */
	public function testCallExtensionFunction_NoExtension( RiotAPI $api )
	{
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('no extension exists for this ApiObject');

		$masteryPages = $api->getChampions();
		$masteryPages->invalidFunction();
	}
}
