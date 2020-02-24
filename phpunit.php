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

use PHPUnit\Framework\TestCase;
use RiotAPI\LeagueAPI\Objects;

/**
 *   Class RiotAPITestCase
 * This class provides utilities to validate our API objects.
 */
class RiotAPITestCase extends TestCase
{
	/**
	 *   Recursively validate list of objects.
	 *
	 * @param array  $data
	 * @param array  $originalData
	 * @param string $objectsClass
	 *
	 * @throws ReflectionException
	 */
	public function checkObjectPropertiesAndDataValidityOfObjectList ( array $data, array $originalData, string $objectsClass )
	{
		//  Check list validity
		$this->assertTrue(is_array($data) && !empty($data), "Object list is empty!");
		$this->assertContainsOnlyInstancesOf($objectsClass, $data, "Object list does not contain instances of required class!");
		$this->assertSameSize($data, $originalData, "Object list count does not match original request result data count!");

		//  Check all the objects in the list
		foreach ($data as $n => $object)
		{
			//  Check object identifier
			$this->assertArrayHasKey($n, $originalData, "Object identifier is not valid! It does not match original request result data identifier!");
			//  For each object in the list, check its properties and data validity
			$this->checkObjectPropertiesAndDataValidity($object, $originalData[$n]);
		}
	}

	/**
	 *   Recursively validate object's properties.
	 *
	 * @param Objects\IApiObject $object
	 * @param array              $originalData
	 * @param string|null        $objectClass
	 *
	 * @throws ReflectionException
	 */
	public function checkObjectPropertiesAndDataValidity( Objects\IApiObject $object, array $originalData, string $objectClass = null )
	{
		//  Check class of this object (if required - not required if called from list validation function)
		if (!is_null($objectClass))
			$this->assertInstanceOf($objectClass, $object, "Object is not valid! It is not an instance of required class.");

		//  Create object's reflection
		$ref = new ReflectionClass($object);
		//  Get it's properties
		$props = $ref->getProperties();

		//  Check all object's properties
		foreach ($props as $propRef)
		{
			if ($propRef->isProtected() || $propRef->isPrivate())
				continue;

			//  The actual object's property
			$prop = $object->{$propRef->getName()};

			if (empty($prop))
				continue; // TODO: Continue?

			//  For each property parse its DataType
			$dataType = RiotAPI\LeagueAPI\Objects\ApiObject::getPropertyDataType($propRef->getDocComment());

			//  Check if its data type is non-standard data type (our special object)
			if ($dataType !== false)
			{
				//  This property is our special class (not int, string, bool, etc.)
				if ($dataType->isArray)
				{
					//  This property is list of instances of this class, validate the list
					$this->checkObjectPropertiesAndDataValidityOfObjectList(
						$objectList = $prop,
						$originalData[$propRef->getName()],
						$ref->getNamespaceName() . "\\" . $dataType->class
					);
				}
				else
				{
					//  Check whether object saved in this property is instance of required class
					$this->assertInstanceOf(
						$ref->getNamespaceName() . "\\" . $dataType->class,
						$prop,
						"Instance of {$ref->getName()}->\${$propRef->getName()} is not valid! It does not match with annotation data type."
					);

					//  Check properties and data validity of this object
					$this->checkObjectPropertiesAndDataValidity(
						$prop,
						$originalData[$propRef->getName()]
					);
				}
			}
			else
			{
				//  This property is of standard data type (int, string, bool, etc.)
				$this->assertArrayHasKey($propRef->getName(), $originalData, "Original request result data do not contain key '{$propRef->getName()}'.");
				$this->assertSame(
					$originalData[$propRef->getName()],
					$prop,
					"Value of {$ref->getName()}->\${$propRef->getName()} is not valid! It does not match with original request result data."
				);
			}
		}

		//  Check data validity
		$data = $object->getData();
		$this->assertSame($originalData, $data, "Clean data of {$ref->getName()} are not valid! They do not match with original request result data.");
	}

	/**
	 *   Recursively removes directory and all its contents.
	 *
	 * @param $dir
	 *
	 * @return bool
	 */
	public static function deleteDir($dir)
	{
		if (!file_exists($dir))
			return true;

		if (!is_dir($dir))
			return unlink($dir);

		foreach (scandir($dir) as $item)
		{
			if ($item == '.' || $item == '..')
				continue;

			if (!self::deleteDir($dir . DIRECTORY_SEPARATOR . $item))
				return false;
		}

		return rmdir($dir);
	}

	/**
	 *   Returns usable API key - either from environment or constant.
	 *
	 * @return string
	 */
	public static function getApiKey(): string
	{
		return getenv('API_KEY') ?: 'API_KEY_UNKNOWN';
	}

	/**
	 *   Returns usable tournament API key - either from environment or constant.
	 *
	 * @return string
	 */
	public static function getApiTournamentKey(): string
	{
		return getenv('API_TOURNAMENT_KEY') ?: 'API_KEY_UNKNOWN';
	}
}

//  Autoload required classes
require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Europe/Prague');
