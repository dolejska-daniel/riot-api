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
 *   Class SSummonerSpellDto
 * This object contains summoner spell data.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1055/3634
 *     @link https://developer.riotgames.com/api/methods#!/1055/3628
 */
class SSummonerSpellDto extends ApiObject
{
    /** @var float[] $cooldown */
    public $cooldown;

    /** @var string $cooldownBurn */
    public $cooldownBurn;

    /** @var int[] $cost */
    public $cost;

    /** @var string $costBurn */
    public $costBurn;

    /** @var string $costType */
    public $costType;

    /** @var string $description */
    public $description;

    /**
     *   This field is a List of List of Double.
     *
     * @var Sobject[]
     */
    public $effect;

    /** @var string[] $effectBurn */
    public $effectBurn;

    /** @var int $id */
    public $id;

    /** @var SImageDto $image */
    public $image;

    /** @var string $key */
    public $key;

    /** @var SLevelTipDto $leveltip */
    public $leveltip;

    /** @var int $maxrank */
    public $maxrank;

    /** @var string[] $modes */
    public $modes;

    /** @var string $name */
    public $name;

    /**
     *   This field is either a List of Integer or the String 'self' for spells
     * that target one's own champion.
     *
     * @var Sobject
     */
    public $range;

    /** @var string $rangeBurn */
    public $rangeBurn;

    /** @var string $resource */
    public $resource;

    /** @var string $sanitizedDescription */
    public $sanitizedDescription;

    /** @var string $sanitizedTooltip */
    public $sanitizedTooltip;

    /** @var int $summonerLevel */
    public $summonerLevel;

    /** @var string $tooltip */
    public $tooltip;

    /** @var SSpellVarsDto[] $vars */
    public $vars;
}
