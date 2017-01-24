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

namespace RiotAPI\Objects;

/**
 *   Class ApiObject
 *
 * @property $_data
 *
 * @package RiotAPI\Objects
 */
class ApiObject implements IApiObject
{
	/**
	 *   ApiObject constructor.
	 *
	 * @param array $data
	 */
	public function __construct( array $data )
	{
		if ($data instanceof \Traversable)
			$data = iterator_to_array($data);

		$this->_data = $data;

		// Tries to assigns data to class properties
		$selfRef = new \ReflectionClass($this);
		$namespace = $selfRef->getNamespaceName();

		foreach ($data as $property => $value)
		{
			try
			{
				if ($propRef = $selfRef->getProperty($property))
				{
					//  Object has required property, time to discover if it's
					$dataType = self::getPropertyDataType($propRef->getDocComment());
					if ($dataType !== false)
					{
						//  Property is special DataType
						$newRef = new \ReflectionClass("$namespace\\$dataType->class");
						if ($dataType->isArray)
						{
							//  Property is array of special DataType
							foreach ($value as $identifier => $d)
							{
								$this->$property[$identifier] = $newRef->newInstance($d);
							}
						}
						else
							$this->$property = $newRef->newInstance($value);
					}
					else
						$this->$property = $value;
				}
			}
			//  If property does not exist
			catch (\ReflectionException $ex) {}
		}
	}

	/**
	 *   Returns DataType specified in PHPDoc comment.
	 *
	 * @param string $phpDocComment
	 *
	 * @return bool|\stdClass
	 */
	protected static function getPropertyDataType( string $phpDocComment )
	{
		$o = new \stdClass();

		preg_match('/@var\s(\w+)(\[\])?/', $phpDocComment, $matches);

		$o->class = $matches[1];
		$o->isArray = isset($matches[2]);

		if (in_array($o->class, [ 'integer', 'int', 'string', 'bool', 'boolean', 'double', 'float', 'array' ]))
			return false;

		return $o;
	}


	/**
	 *   This variable contains all the data in an array.
	 *
	 * @var array
	 */
	protected $_data;

	/**
	 *   Gets all the original data fetched from RiotAPI.
	 *
	 * @return array
	 */
	public function getData(): array
	{
		return $this->_data;
	}
}