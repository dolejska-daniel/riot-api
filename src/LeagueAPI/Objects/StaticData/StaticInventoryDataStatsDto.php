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
 *   Class StaticInventoryDataStatsDto
 * This object contains stats for inventory (e.g., runes and items).
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticInventoryDataStatsDto extends ApiObject
{
	/** @var double $PercentCritDamageMod */
	public $PercentCritDamageMod;

	/** @var double $PercentSpellBlockMod */
	public $PercentSpellBlockMod;

	/** @var double $PercentHPRegenMod */
	public $PercentHPRegenMod;

	/** @var double $PercentMovementSpeedMod */
	public $PercentMovementSpeedMod;

	/** @var double $FlatSpellBlockMod */
	public $FlatSpellBlockMod;

	/** @var double $FlatCritDamageMod */
	public $FlatCritDamageMod;

	/** @var double $FlatEnergyPoolMod */
	public $FlatEnergyPoolMod;

	/** @var double $PercentLifeStealMod */
	public $PercentLifeStealMod;

	/** @var double $FlatMPPoolMod */
	public $FlatMPPoolMod;

	/** @var double $FlatMovementSpeedMod */
	public $FlatMovementSpeedMod;

	/** @var double $PercentAttackSpeedMod */
	public $PercentAttackSpeedMod;

	/** @var double $FlatBlockMod */
	public $FlatBlockMod;

	/** @var double $PercentBlockMod */
	public $PercentBlockMod;

	/** @var double $FlatEnergyRegenMod */
	public $FlatEnergyRegenMod;

	/** @var double $PercentSpellVampMod */
	public $PercentSpellVampMod;

	/** @var double $FlatMPRegenMod */
	public $FlatMPRegenMod;

	/** @var double $PercentDodgeMod */
	public $PercentDodgeMod;

	/** @var double $FlatAttackSpeedMod */
	public $FlatAttackSpeedMod;

	/** @var double $FlatArmorMod */
	public $FlatArmorMod;

	/** @var double $FlatHPRegenMod */
	public $FlatHPRegenMod;

	/** @var double $PercentMagicDamageMod */
	public $PercentMagicDamageMod;

	/** @var double $PercentMPPoolMod */
	public $PercentMPPoolMod;

	/** @var double $FlatMagicDamageMod */
	public $FlatMagicDamageMod;

	/** @var double $PercentMPRegenMod */
	public $PercentMPRegenMod;

	/** @var double $PercentPhysicalDamageMod */
	public $PercentPhysicalDamageMod;

	/** @var double $FlatPhysicalDamageMod */
	public $FlatPhysicalDamageMod;

	/** @var double $PercentHPPoolMod */
	public $PercentHPPoolMod;

	/** @var double $PercentArmorMod */
	public $PercentArmorMod;

	/** @var double $PercentCritChanceMod */
	public $PercentCritChanceMod;

	/** @var double $PercentEXPBonus */
	public $PercentEXPBonus;

	/** @var double $FlatHPPoolMod */
	public $FlatHPPoolMod;

	/** @var double $FlatCritChanceMod */
	public $FlatCritChanceMod;

	/** @var double $FlatEXPBonus */
	public $FlatEXPBonus;
}
