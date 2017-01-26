<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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
 *
 *     @link https://developer.riotgames.com/api/methods#!/1055/3633
 *     @link https://developer.riotgames.com/api/methods#!/1055/3622
 */
class SStatsDto extends ApiObject
{
    /** @var float $armor */
    public $armor;

    /** @var float $armorperlevel */
    public $armorperlevel;

    /** @var float $attackdamage */
    public $attackdamage;

    /** @var float $attackdamageperlevel */
    public $attackdamageperlevel;

    /** @var float $attackrange */
    public $attackrange;

    /** @var float $attackspeedoffset */
    public $attackspeedoffset;

    /** @var float $attackspeedperlevel */
    public $attackspeedperlevel;

    /** @var float $crit */
    public $crit;

    /** @var float $critperlevel */
    public $critperlevel;

    /** @var float $hp */
    public $hp;

    /** @var float $hpperlevel */
    public $hpperlevel;

    /** @var float $hpregen */
    public $hpregen;

    /** @var float $hpregenperlevel */
    public $hpregenperlevel;

    /** @var float $movespeed */
    public $movespeed;

    /** @var float $mp */
    public $mp;

    /** @var float $mpperlevel */
    public $mpperlevel;

    /** @var float $mpregen */
    public $mpregen;

    /** @var float $mpregenperlevel */
    public $mpregenperlevel;

    /** @var float $spellblock */
    public $spellblock;

    /** @var float $spellblockperlevel */
    public $spellblockperlevel;
}
