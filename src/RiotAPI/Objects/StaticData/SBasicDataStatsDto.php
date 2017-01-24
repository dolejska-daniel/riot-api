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
 *   Class SBasicDataStatsDto
 * This object contains basic data stats.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *     @link https://developer.riotgames.com/api/methods#!/1055/3621
 *     @link https://developer.riotgames.com/api/methods#!/1055/3627
 *     @link https://developer.riotgames.com/api/methods#!/1055/3623
 *     @link https://developer.riotgames.com/api/methods#!/1055/3629
 *
 * @package RiotAPI\Objects\StaticData
 */
class SBasicDataStatsDto extends ApiObject
{
	/** @var double $FlatArmorMod */
	public $FlatArmorMod;

	/** @var double $FlatAttackSpeedMod */
	public $FlatAttackSpeedMod;

	/** @var double $FlatBlockMod */
	public $FlatBlockMod;

	/** @var double $FlatCritChanceMod */
	public $FlatCritChanceMod;

	/** @var double $FlatCritDamageMod */
	public $FlatCritDamageMod;

	/** @var double $FlatEXPBonus */
	public $FlatEXPBonus;

	/** @var double $FlatEnergyPoolMod */
	public $FlatEnergyPoolMod;

	/** @var double $FlatEnergyRegenMod */
	public $FlatEnergyRegenMod;

	/** @var double $FlatHPPoolMod */
	public $FlatHPPoolMod;

	/** @var double $FlatHPRegenMod */
	public $FlatHPRegenMod;

	/** @var double $FlatMPPoolMod */
	public $FlatMPPoolMod;

	/** @var double $FlatMPRegenMod */
	public $FlatMPRegenMod;

	/** @var double $FlatMagicDamageMod */
	public $FlatMagicDamageMod;

	/** @var double $FlatMovementSpeedMod */
	public $FlatMovementSpeedMod;

	/** @var double $FlatPhysicalDamageMod */
	public $FlatPhysicalDamageMod;

	/** @var double $FlatSpellBlockMod */
	public $FlatSpellBlockMod;

	/** @var double $PercentArmorMod */
	public $PercentArmorMod;

	/** @var double $PercentAttackSpeedMod */
	public $PercentAttackSpeedMod;

	/** @var double $PercentBlockMod */
	public $PercentBlockMod;

	/** @var double $PercentCritChanceMod */
	public $PercentCritChanceMod;

	/** @var double $PercentCritDamageMod */
	public $PercentCritDamageMod;

	/** @var double $PercentDodgeMod */
	public $PercentDodgeMod;

	/** @var double $PercentEXPBonus */
	public $PercentEXPBonus;

	/** @var double $PercentHPPoolMod */
	public $PercentHPPoolMod;

	/** @var double $PercentHPRegenMod */
	public $PercentHPRegenMod;

	/** @var double $PercentLifeStealMod */
	public $PercentLifeStealMod;

	/** @var double $PercentMPPoolMod */
	public $PercentMPPoolMod;

	/** @var double $PercentMPRegenMod */
	public $PercentMPRegenMod;

	/** @var double $PercentMagicDamageMod */
	public $PercentMagicDamageMod;

	/** @var double $PercentMovementSpeedMod */
	public $PercentMovementSpeedMod;

	/** @var double $PercentPhysicalDamageMod */
	public $PercentPhysicalDamageMod;

	/** @var double $PercentSpellBlockMod */
	public $PercentSpellBlockMod;

	/** @var double $PercentSpellVampMod */
	public $PercentSpellVampMod;

	/** @var double $rFlatArmorModPerLevel */
	public $rFlatArmorModPerLevel;

	/** @var double $rFlatArmorPenetrationMod */
	public $rFlatArmorPenetrationMod;

	/** @var double $rFlatArmorPenetrationModPerLevel */
	public $rFlatArmorPenetrationModPerLevel;

	/** @var double $rFlatCritChanceModPerLevel */
	public $rFlatCritChanceModPerLevel;

	/** @var double $rFlatCritDamageModPerLevel */
	public $rFlatCritDamageModPerLevel;

	/** @var double $rFlatDodgeMod */
	public $rFlatDodgeMod;

	/** @var double $rFlatDodgeModPerLevel */
	public $rFlatDodgeModPerLevel;

	/** @var double $rFlatEnergyModPerLevel */
	public $rFlatEnergyModPerLevel;

	/** @var double $rFlatEnergyRegenModPerLevel */
	public $rFlatEnergyRegenModPerLevel;

	/** @var double $rFlatGoldPer10Mod */
	public $rFlatGoldPer10Mod;

	/** @var double $rFlatHPModPerLevel */
	public $rFlatHPModPerLevel;

	/** @var double $rFlatHPRegenModPerLevel */
	public $rFlatHPRegenModPerLevel;

	/** @var double $rFlatMPModPerLevel */
	public $rFlatMPModPerLevel;

	/** @var double $rFlatMPRegenModPerLevel */
	public $rFlatMPRegenModPerLevel;

	/** @var double $rFlatMagicDamageModPerLevel */
	public $rFlatMagicDamageModPerLevel;

	/** @var double $rFlatMagicPenetrationMod */
	public $rFlatMagicPenetrationMod;

	/** @var double $rFlatMagicPenetrationModPerLevel */
	public $rFlatMagicPenetrationModPerLevel;

	/** @var double $rFlatMovementSpeedModPerLevel */
	public $rFlatMovementSpeedModPerLevel;

	/** @var double $rFlatPhysicalDamageModPerLevel */
	public $rFlatPhysicalDamageModPerLevel;

	/** @var double $rFlatSpellBlockModPerLevel */
	public $rFlatSpellBlockModPerLevel;

	/** @var double $rFlatTimeDeadMod */
	public $rFlatTimeDeadMod;

	/** @var double $rFlatTimeDeadModPerLevel */
	public $rFlatTimeDeadModPerLevel;

	/** @var double $rPercentArmorPenetrationMod */
	public $rPercentArmorPenetrationMod;

	/** @var double $rPercentArmorPenetrationModPerLevel */
	public $rPercentArmorPenetrationModPerLevel;

	/** @var double $rPercentAttackSpeedModPerLevel */
	public $rPercentAttackSpeedModPerLevel;

	/** @var double $rPercentCooldownMod */
	public $rPercentCooldownMod;

	/** @var double $rPercentCooldownModPerLevel */
	public $rPercentCooldownModPerLevel;

	/** @var double $rPercentMagicPenetrationMod */
	public $rPercentMagicPenetrationMod;

	/** @var double $rPercentMagicPenetrationModPerLevel */
	public $rPercentMagicPenetrationModPerLevel;

	/** @var double $rPercentMovementSpeedModPerLevel */
	public $rPercentMovementSpeedModPerLevel;

	/** @var double $rPercentTimeDeadMod */
	public $rPercentTimeDeadMod;

	/** @var double $rPercentTimeDeadModPerLevel */
	public $rPercentTimeDeadModPerLevel;
}
