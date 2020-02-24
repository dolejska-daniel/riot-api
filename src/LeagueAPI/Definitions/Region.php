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

namespace RiotAPI\LeagueAPI\Definitions;

use RiotAPI\LeagueAPI\Exceptions\GeneralException;


/**
 *   Class Region
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class Region implements IRegion
{
	// ==================================================================dd=
	//     Standard game regions (servers)
	// ==================================================================dd=

	const NORTH_AMERICA = 'na';

	const EUROPE_WEST = 'euw';

	const EUROPE_EAST = 'eune';

	const LAMERICA_SOUTH = 'las';

	const LAMERICA_NORTH = 'lan';

	const BRASIL = 'br';

	const RUSSIA = 'ru';

	const TURKEY = 'tr';

	const OCEANIA = 'oce';

	const KOREA = 'kr';

	const JAPAN = 'jp';

	public static $list = array(
		self::NORTH_AMERICA   => self::NORTH_AMERICA,
		self::EUROPE          => self::EUROPE,
		self::EUROPE_WEST     => self::EUROPE_WEST,
		self::EUROPE_EAST     => self::EUROPE_EAST,
		self::LAMERICA_SOUTH  => self::LAMERICA_SOUTH,
		self::LAMERICA_NORTH  => self::LAMERICA_NORTH,
		self::BRASIL          => self::BRASIL,
		self::RUSSIA          => self::RUSSIA,
		self::TURKEY          => self::TURKEY,
		self::OCEANIA         => self::OCEANIA,
		self::ASIA            => self::ASIA,
		self::KOREA           => self::KOREA,
		self::JAPAN           => self::JAPAN,
		self::AMERICAS        => self::AMERICAS,
	);


	// ==================================================================dd=
	//     Control functions
	// ==================================================================dd=

	public function getList(): array
	{
		return self::$list;
	}

	public function getRegionName( string $region ): string
	{
		$region = strtolower($region);
		if (!isset(self::$list[$region]))
			throw new GeneralException('Invalid region provided. Can not find requested region.');

		return self::$list[$region];
	}
}