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

namespace RiotAPI\LeagueAPI\Objects\StaticData;

use RiotAPI\LeagueAPI\Objects\ApiObject;


/**
 *   Class StaticStatsDto
 * This object contains champion stats data.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticStatsDto extends ApiObject
{
	/** @var double $armorperlevel */
	public $armorperlevel;

	/** @var double $hpperlevel */
	public $hpperlevel;

	/** @var double $attackdamage */
	public $attackdamage;

	/** @var double $mpperlevel */
	public $mpperlevel;

	/** @var double $attackspeedoffset */
	public $attackspeedoffset;

	/** @var double $armor */
	public $armor;

	/** @var double $hp */
	public $hp;

	/** @var double $hpregenperlevel */
	public $hpregenperlevel;

	/** @var double $spellblock */
	public $spellblock;

	/** @var double $attackrange */
	public $attackrange;

	/** @var double $movespeed */
	public $movespeed;

	/** @var double $attackdamageperlevel */
	public $attackdamageperlevel;

	/** @var double $mpregenperlevel */
	public $mpregenperlevel;

	/** @var double $mp */
	public $mp;

	/** @var double $spellblockperlevel */
	public $spellblockperlevel;

	/** @var double $crit */
	public $crit;

	/** @var double $mpregen */
	public $mpregen;

	/** @var double $attackspeedperlevel */
	public $attackspeedperlevel;

	/** @var double $hpregen */
	public $hpregen;

	/** @var double $critperlevel */
	public $critperlevel;
}
