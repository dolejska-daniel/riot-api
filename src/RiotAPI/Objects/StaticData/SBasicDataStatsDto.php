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
 *   Class SBasicDataStatsDto
 * This object contains basic data stats.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1055/3621
 *     @link https://developer.riotgames.com/api/methods#!/1055/3627
 *     @link https://developer.riotgames.com/api/methods#!/1055/3623
 *     @link https://developer.riotgames.com/api/methods#!/1055/3629
 */
class SBasicDataStatsDto extends ApiObject
{
    /** @var float $FlatArmorMod */
    public $FlatArmorMod;

    /** @var float $FlatAttackSpeedMod */
    public $FlatAttackSpeedMod;

    /** @var float $FlatBlockMod */
    public $FlatBlockMod;

    /** @var float $FlatCritChanceMod */
    public $FlatCritChanceMod;

    /** @var float $FlatCritDamageMod */
    public $FlatCritDamageMod;

    /** @var float $FlatEXPBonus */
    public $FlatEXPBonus;

    /** @var float $FlatEnergyPoolMod */
    public $FlatEnergyPoolMod;

    /** @var float $FlatEnergyRegenMod */
    public $FlatEnergyRegenMod;

    /** @var float $FlatHPPoolMod */
    public $FlatHPPoolMod;

    /** @var float $FlatHPRegenMod */
    public $FlatHPRegenMod;

    /** @var float $FlatMPPoolMod */
    public $FlatMPPoolMod;

    /** @var float $FlatMPRegenMod */
    public $FlatMPRegenMod;

    /** @var float $FlatMagicDamageMod */
    public $FlatMagicDamageMod;

    /** @var float $FlatMovementSpeedMod */
    public $FlatMovementSpeedMod;

    /** @var float $FlatPhysicalDamageMod */
    public $FlatPhysicalDamageMod;

    /** @var float $FlatSpellBlockMod */
    public $FlatSpellBlockMod;

    /** @var float $PercentArmorMod */
    public $PercentArmorMod;

    /** @var float $PercentAttackSpeedMod */
    public $PercentAttackSpeedMod;

    /** @var float $PercentBlockMod */
    public $PercentBlockMod;

    /** @var float $PercentCritChanceMod */
    public $PercentCritChanceMod;

    /** @var float $PercentCritDamageMod */
    public $PercentCritDamageMod;

    /** @var float $PercentDodgeMod */
    public $PercentDodgeMod;

    /** @var float $PercentEXPBonus */
    public $PercentEXPBonus;

    /** @var float $PercentHPPoolMod */
    public $PercentHPPoolMod;

    /** @var float $PercentHPRegenMod */
    public $PercentHPRegenMod;

    /** @var float $PercentLifeStealMod */
    public $PercentLifeStealMod;

    /** @var float $PercentMPPoolMod */
    public $PercentMPPoolMod;

    /** @var float $PercentMPRegenMod */
    public $PercentMPRegenMod;

    /** @var float $PercentMagicDamageMod */
    public $PercentMagicDamageMod;

    /** @var float $PercentMovementSpeedMod */
    public $PercentMovementSpeedMod;

    /** @var float $PercentPhysicalDamageMod */
    public $PercentPhysicalDamageMod;

    /** @var float $PercentSpellBlockMod */
    public $PercentSpellBlockMod;

    /** @var float $PercentSpellVampMod */
    public $PercentSpellVampMod;

    /** @var float $rFlatArmorModPerLevel */
    public $rFlatArmorModPerLevel;

    /** @var float $rFlatArmorPenetrationMod */
    public $rFlatArmorPenetrationMod;

    /** @var float $rFlatArmorPenetrationModPerLevel */
    public $rFlatArmorPenetrationModPerLevel;

    /** @var float $rFlatCritChanceModPerLevel */
    public $rFlatCritChanceModPerLevel;

    /** @var float $rFlatCritDamageModPerLevel */
    public $rFlatCritDamageModPerLevel;

    /** @var float $rFlatDodgeMod */
    public $rFlatDodgeMod;

    /** @var float $rFlatDodgeModPerLevel */
    public $rFlatDodgeModPerLevel;

    /** @var float $rFlatEnergyModPerLevel */
    public $rFlatEnergyModPerLevel;

    /** @var float $rFlatEnergyRegenModPerLevel */
    public $rFlatEnergyRegenModPerLevel;

    /** @var float $rFlatGoldPer10Mod */
    public $rFlatGoldPer10Mod;

    /** @var float $rFlatHPModPerLevel */
    public $rFlatHPModPerLevel;

    /** @var float $rFlatHPRegenModPerLevel */
    public $rFlatHPRegenModPerLevel;

    /** @var float $rFlatMPModPerLevel */
    public $rFlatMPModPerLevel;

    /** @var float $rFlatMPRegenModPerLevel */
    public $rFlatMPRegenModPerLevel;

    /** @var float $rFlatMagicDamageModPerLevel */
    public $rFlatMagicDamageModPerLevel;

    /** @var float $rFlatMagicPenetrationMod */
    public $rFlatMagicPenetrationMod;

    /** @var float $rFlatMagicPenetrationModPerLevel */
    public $rFlatMagicPenetrationModPerLevel;

    /** @var float $rFlatMovementSpeedModPerLevel */
    public $rFlatMovementSpeedModPerLevel;

    /** @var float $rFlatPhysicalDamageModPerLevel */
    public $rFlatPhysicalDamageModPerLevel;

    /** @var float $rFlatSpellBlockModPerLevel */
    public $rFlatSpellBlockModPerLevel;

    /** @var float $rFlatTimeDeadMod */
    public $rFlatTimeDeadMod;

    /** @var float $rFlatTimeDeadModPerLevel */
    public $rFlatTimeDeadModPerLevel;

    /** @var float $rPercentArmorPenetrationMod */
    public $rPercentArmorPenetrationMod;

    /** @var float $rPercentArmorPenetrationModPerLevel */
    public $rPercentArmorPenetrationModPerLevel;

    /** @var float $rPercentAttackSpeedModPerLevel */
    public $rPercentAttackSpeedModPerLevel;

    /** @var float $rPercentCooldownMod */
    public $rPercentCooldownMod;

    /** @var float $rPercentCooldownModPerLevel */
    public $rPercentCooldownModPerLevel;

    /** @var float $rPercentMagicPenetrationMod */
    public $rPercentMagicPenetrationMod;

    /** @var float $rPercentMagicPenetrationModPerLevel */
    public $rPercentMagicPenetrationModPerLevel;

    /** @var float $rPercentMovementSpeedModPerLevel */
    public $rPercentMovementSpeedModPerLevel;

    /** @var float $rPercentTimeDeadMod */
    public $rPercentTimeDeadMod;

    /** @var float $rPercentTimeDeadModPerLevel */
    public $rPercentTimeDeadModPerLevel;
}
