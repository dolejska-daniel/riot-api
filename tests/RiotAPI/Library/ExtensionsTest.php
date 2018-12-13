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
use RiotAPI\Extensions\StaticReforgedRunePathListExtension;
use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\StaticData\StaticReforgedRuneDto;
use RiotAPI\Objects\StaticData\StaticReforgedRunePathList;
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
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA  => true,
			RiotAPI::SET_DATADRAGON_INIT => true,
			RiotAPI::SET_EXTENSIONS      => [
				StaticReforgedRunePathList::class => StaticReforgedRunePathListExtension::class,
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
				StaticReforgedRunePathList::class => IApiObject::class,
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
				StaticReforgedRunePathList::class => NoninstantiableExtension::class,
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
				StaticReforgedRunePathList::class => "InvalidClass",
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
		/** @var StaticReforgedRunePathListExtension $paths */
		$paths = $api->getStaticReforgedRunePaths();

		$this->assertInstanceOf(StaticReforgedRuneDto::class, $paths->getRuneById(8229));
		$this->assertNull($paths->getRuneById(814684753));
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

		/** @var StaticReforgedRunePathListExtension $paths */
		$paths = $api->getStaticReforgedRunePaths();
		$paths->invalidFunction();
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

		$masteryPages = $api->getChampionRotations();
		$masteryPages->invalidFunction();
	}
}
