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

use PHPUnit\Framework\TestCase;

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Extensions\StaticReforgedRunePathListExtension;
use RiotAPI\LeagueAPI\Objects\IApiObject;
use RiotAPI\LeagueAPI\Objects\IApiObjectExtension;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRuneDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRunePathList;
use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\SettingsException;


abstract class NoninstantiableExtension implements IApiObjectExtension
{
	public function __construct( IApiObject &$apiObject, LeagueAPI &$api ) {}
}


class ExtensionsTest extends TestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_DATADRAGON_INIT => true,
			LeagueAPI::SET_EXTENSIONS      => [
				StaticReforgedRunePathList::class => StaticReforgedRunePathListExtension::class,
			],
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_noExtension()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_settings_invalid_Value()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Value of settings parameter");

		new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_EXTENSIONS     => IApiObject::class,
		]);
	}

	public function testInit_settings_invalid_ClassInterface()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('does not implement IApiObjectExtension interface');

		new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_EXTENSIONS     => [
				StaticReforgedRunePathList::class => IApiObject::class,
			],
		]);
	}

	public function testInit_settings_invalid_NoninstantiableClass()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('is not instantiable');

		new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_EXTENSIONS     => [
				StaticReforgedRunePathList::class => NoninstantiableExtension::class,
			],
		]);
	}

	public function testInit_settings_invalid_Class()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage('is not valid');

		new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
			LeagueAPI::SET_EXTENSIONS     => [
				StaticReforgedRunePathList::class => "InvalidClass",
			],
		]);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCallExtensionFunction_Valid(LeagueAPI $api )
	{
		/** @var StaticReforgedRunePathListExtension $paths */
		$paths = $api->getStaticReforgedRunePaths();

		$this->assertInstanceOf(StaticReforgedRuneDto::class, $paths->getRuneById(8229));
		$this->assertNull($paths->getRuneById(814684753));
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testCallExtensionFunction_Invalid(LeagueAPI $api )
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
	 * @param LeagueAPI $api
	 */
	public function testCallExtensionFunction_NoExtension(LeagueAPI $api )
	{
		$this->expectException(GeneralException::class);
		$this->expectExceptionMessage('no extension exists for this ApiObject');

		$masteryPages = $api->getChampionRotations();
		$masteryPages->invalidFunction();
	}
}
