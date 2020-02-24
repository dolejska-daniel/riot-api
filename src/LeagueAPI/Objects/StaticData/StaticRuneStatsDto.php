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
 *   Class StaticRuneStatsDto
 * This object contains stats for runes.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticRuneStatsDto extends ApiObject
{
	/** @var double $PercentTimeDeadModPerLevel */
	public $PercentTimeDeadModPerLevel;

	/** @var double $PercentArmorPenetrationModPerLevel */
	public $PercentArmorPenetrationModPerLevel;

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

	/** @var double $FlatEnergyRegenModPerLevel */
	public $FlatEnergyRegenModPerLevel;

	/** @var double $FlatEnergyPoolMod */
	public $FlatEnergyPoolMod;

	/** @var double $FlatMagicPenetrationModPerLevel */
	public $FlatMagicPenetrationModPerLevel;

	/** @var double $PercentLifeStealMod */
	public $PercentLifeStealMod;

	/** @var double $FlatMPPoolMod */
	public $FlatMPPoolMod;

	/** @var double $PercentCooldownMod */
	public $PercentCooldownMod;

	/** @var double $PercentMagicPenetrationMod */
	public $PercentMagicPenetrationMod;

	/** @var double $FlatArmorPenetrationModPerLevel */
	public $FlatArmorPenetrationModPerLevel;

	/** @var double $FlatMovementSpeedMod */
	public $FlatMovementSpeedMod;

	/** @var double $FlatTimeDeadModPerLevel */
	public $FlatTimeDeadModPerLevel;

	/** @var double $FlatArmorModPerLevel */
	public $FlatArmorModPerLevel;

	/** @var double $PercentAttackSpeedMod */
	public $PercentAttackSpeedMod;

	/** @var double $FlatDodgeModPerLevel */
	public $FlatDodgeModPerLevel;

	/** @var double $PercentMagicDamageMod */
	public $PercentMagicDamageMod;

	/** @var double $PercentBlockMod */
	public $PercentBlockMod;

	/** @var double $FlatDodgeMod */
	public $FlatDodgeMod;

	/** @var double $FlatEnergyRegenMod */
	public $FlatEnergyRegenMod;

	/** @var double $FlatHPModPerLevel */
	public $FlatHPModPerLevel;

	/** @var double $PercentAttackSpeedModPerLevel */
	public $PercentAttackSpeedModPerLevel;

	/** @var double $PercentSpellVampMod */
	public $PercentSpellVampMod;

	/** @var double $FlatMPRegenMod */
	public $FlatMPRegenMod;

	/** @var double $PercentHPPoolMod */
	public $PercentHPPoolMod;

	/** @var double $PercentDodgeMod */
	public $PercentDodgeMod;

	/** @var double $FlatAttackSpeedMod */
	public $FlatAttackSpeedMod;

	/** @var double $FlatArmorMod */
	public $FlatArmorMod;

	/** @var double $FlatMagicDamageModPerLevel */
	public $FlatMagicDamageModPerLevel;

	/** @var double $FlatHPRegenMod */
	public $FlatHPRegenMod;

	/** @var double $PercentPhysicalDamageMod */
	public $PercentPhysicalDamageMod;

	/** @var double $FlatCritChanceModPerLevel */
	public $FlatCritChanceModPerLevel;

	/** @var double $FlatSpellBlockModPerLevel */
	public $FlatSpellBlockModPerLevel;

	/** @var double $PercentTimeDeadMod */
	public $PercentTimeDeadMod;

	/** @var double $FlatBlockMod */
	public $FlatBlockMod;

	/** @var double $PercentMPPoolMod */
	public $PercentMPPoolMod;

	/** @var double $FlatMagicDamageMod */
	public $FlatMagicDamageMod;

	/** @var double $PercentMPRegenMod */
	public $PercentMPRegenMod;

	/** @var double $PercentMovementSpeedModPerLevel */
	public $PercentMovementSpeedModPerLevel;

	/** @var double $PercentCooldownModPerLevel */
	public $PercentCooldownModPerLevel;

	/** @var double $FlatMPModPerLevel */
	public $FlatMPModPerLevel;

	/** @var double $FlatEnergyModPerLevel */
	public $FlatEnergyModPerLevel;

	/** @var double $FlatPhysicalDamageMod */
	public $FlatPhysicalDamageMod;

	/** @var double $FlatHPRegenModPerLevel */
	public $FlatHPRegenModPerLevel;

	/** @var double $FlatCritDamageMod */
	public $FlatCritDamageMod;

	/** @var double $PercentArmorMod */
	public $PercentArmorMod;

	/** @var double $FlatMagicPenetrationMod */
	public $FlatMagicPenetrationMod;

	/** @var double $PercentCritChanceMod */
	public $PercentCritChanceMod;

	/** @var double $FlatPhysicalDamageModPerLevel */
	public $FlatPhysicalDamageModPerLevel;

	/** @var double $PercentArmorPenetrationMod */
	public $PercentArmorPenetrationMod;

	/** @var double $PercentEXPBonus */
	public $PercentEXPBonus;

	/** @var double $FlatMPRegenModPerLevel */
	public $FlatMPRegenModPerLevel;

	/** @var double $PercentMagicPenetrationModPerLevel */
	public $PercentMagicPenetrationModPerLevel;

	/** @var double $FlatTimeDeadMod */
	public $FlatTimeDeadMod;

	/** @var double $FlatMovementSpeedModPerLevel */
	public $FlatMovementSpeedModPerLevel;

	/** @var double $FlatGoldPer10Mod */
	public $FlatGoldPer10Mod;

	/** @var double $FlatArmorPenetrationMod */
	public $FlatArmorPenetrationMod;

	/** @var double $FlatCritDamageModPerLevel */
	public $FlatCritDamageModPerLevel;

	/** @var double $FlatHPPoolMod */
	public $FlatHPPoolMod;

	/** @var double $FlatCritChanceMod */
	public $FlatCritChanceMod;

	/** @var double $FlatEXPBonus */
	public $FlatEXPBonus;
}
