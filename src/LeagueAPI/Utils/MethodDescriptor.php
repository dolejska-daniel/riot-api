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

namespace RiotAPI\LeagueAPI\Utils;

use Nette\Utils\Strings;

/**
 *   Class MethodDescriptor
 *
 * @package RiotAPI\LeagueAPI\Utils
 */
class MethodDescriptor
{
	const CLI_METHOD_NAME = 'cli-name';
	const CLI_METHOD_NAMESPACE = 'cli-namespace';

	/**
	 * @param \ReflectionMethod $method
	 * @return MethodDescriptor
	 */
	public static function fromReflectionMethod(\ReflectionMethod $method)
	{
		return new self($method->getDocComment());
	}

	/** @var string $description */
	public $description;
	/** @var array $props */
	public $props = [];

	/**
	 * MethodDescriptor constructor.
	 * @param string $docComment
	 */
	public function __construct(string $docComment)
	{
		preg_match_all('/@(?<key>\S+)\s+(?<value>.+)?/', $docComment, $matches);
		foreach ($matches[0] as $index => $source)
		{
			$key   = $matches['key'][$index];
			$value = $matches['value'][$index];

			if (isset($this->matches[$key]))
			{
				if (!is_array($this->props[$key]))
					$this->props[$key] = [$this->props[$key]];

				$this->props[$key][] = $value;
			}
			else
			{
				$this->props[$key] = $value;
			}
		}

		preg_match('/^([\S\s]+?)\*\s+(@|\*\/)/', $docComment, $matches);
		$desc = Strings::trim(@$matches[1] ?: "", Strings::TRIM_CHARACTERS . '*/');
		$this->description = Strings::replace($desc, '/(\s*\n\s|\s\*\s)/');
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function getProp($name)
	{
		return $this->props[$name];
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public function propExists($name)
	{
		return isset($this->props[$name]);
	}

	/**
	 * @return bool
	 */
	public function isCLIMethod()
	{
		return $this->propExists(self::CLI_METHOD_NAME);
	}
}