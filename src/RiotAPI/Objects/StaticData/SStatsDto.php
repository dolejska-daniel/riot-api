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

namespace RiotAPI\Objects\StaticData;

use RiotAPI\Objects\ApiObject;


/**
 *   Class SStatsDto
 * This object contains champion stats data.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *     @link https://developer.riotgames.com/api/methods#!/1055/3633
 *     @link https://developer.riotgames.com/api/methods#!/1055/3622
 *
 * @package RiotAPI\Objects\StaticData
 */
class SStatsDto extends ApiObject
{
	/** @var double $armor */
	public $armor;

	/** @var double $armorperlevel */
	public $armorperlevel;

	/** @var double $attackdamage */
	public $attackdamage;

	/** @var double $attackdamageperlevel */
	public $attackdamageperlevel;

	/** @var double $attackrange */
	public $attackrange;

	/** @var double $attackspeedoffset */
	public $attackspeedoffset;

	/** @var double $attackspeedperlevel */
	public $attackspeedperlevel;

	/** @var double $crit */
	public $crit;

	/** @var double $critperlevel */
	public $critperlevel;

	/** @var double $hp */
	public $hp;

	/** @var double $hpperlevel */
	public $hpperlevel;

	/** @var double $hpregen */
	public $hpregen;

	/** @var double $hpregenperlevel */
	public $hpregenperlevel;

	/** @var double $movespeed */
	public $movespeed;

	/** @var double $mp */
	public $mp;

	/** @var double $mpperlevel */
	public $mpperlevel;

	/** @var double $mpregen */
	public $mpregen;

	/** @var double $mpregenperlevel */
	public $mpregenperlevel;

	/** @var double $spellblock */
	public $spellblock;

	/** @var double $spellblockperlevel */
	public $spellblockperlevel;
}
