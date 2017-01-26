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

namespace RiotAPI\Objects;

/**
 *   Class ParticipantStats
 * This object contains participant statistics information.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class ParticipantStats extends ApiObject
{
    /**
     *   Number of assists.
     *
     * @var int
     */
    public $assists;

    /**
     *   Champion level achieved.
     *
     * @var int
     */
    public $champLevel;

    /**
     *   If game was a dominion game, player's combat score, otherwise 0.
     *
     * @var int
     */
    public $combatPlayerScore;

    /**
     *   Number of deaths.
     *
     * @var int
     */
    public $deaths;

    /**
     *   Number of double kills.
     *
     * @var int
     */
    public $doubleKills;

    /**
     *   Flag indicating if participant got an assist on first blood.
     *
     * @var bool
     */
    public $firstBloodAssist;

    /**
     *   Flag indicating if participant got first blood.
     *
     * @var bool
     */
    public $firstBloodKill;

    /**
     *   Flag indicating if participant got an assist on the first inhibitor.
     *
     * @var bool
     */
    public $firstInhibitorAssist;

    /**
     *   Flag indicating if participant destroyed the first inhibitor.
     *
     * @var bool
     */
    public $firstInhibitorKill;

    /**
     *   Flag indicating if participant got an assist on the first tower.
     *
     * @var bool
     */
    public $firstTowerAssist;

    /**
     *   Flag indicating if participant destroyed the first tower.
     *
     * @var bool
     */
    public $firstTowerKill;

    /**
     *   Gold earned.
     *
     * @var int
     */
    public $goldEarned;

    /**
     *   Gold spent.
     *
     * @var int
     */
    public $goldSpent;

    /**
     *   Number of inhibitor kills.
     *
     * @var int
     */
    public $inhibitorKills;

    /**
     *   First item ID.
     *
     * @var int
     */
    public $item0;

    /**
     *   Second item ID.
     *
     * @var int
     */
    public $item1;

    /**
     *   Third item ID.
     *
     * @var int
     */
    public $item2;

    /**
     *   Fourth item ID.
     *
     * @var int
     */
    public $item3;

    /**
     *   Fifth item ID.
     *
     * @var int
     */
    public $item4;

    /**
     *   Sixth item ID.
     *
     * @var int
     */
    public $item5;

    /**
     *   Seventh item ID.
     *
     * @var int
     */
    public $item6;

    /**
     *   Number of killing sprees.
     *
     * @var int
     */
    public $killingSprees;

    /**
     *   Number of kills.
     *
     * @var int
     */
    public $kills;

    /**
     *   Largest critical strike.
     *
     * @var int
     */
    public $largestCriticalStrike;

    /**
     *   Largest killing spree.
     *
     * @var int
     */
    public $largestKillingSpree;

    /**
     *   Largest multi kill.
     *
     * @var int
     */
    public $largestMultiKill;

    /**
     *   Magical damage dealt.
     *
     * @var int
     */
    public $magicDamageDealt;

    /**
     *   Magical damage dealt to champions.
     *
     * @var int
     */
    public $magicDamageDealtToChampions;

    /**
     *   Magic damage taken.
     *
     * @var int
     */
    public $magicDamageTaken;

    /**
     *   Minions killed.
     *
     * @var int
     */
    public $minionsKilled;

    /**
     *   Neutral minions killed.
     *
     * @var int
     */
    public $neutralMinionsKilled;

    /**
     *   Neutral jungle minions killed in the enemy team's jungle.
     *
     * @var int
     */
    public $neutralMinionsKilledEnemyJungle;

    /**
     *   Neutral jungle minions killed in your team's jungle.
     *
     * @var int
     */
    public $neutralMinionsKilledTeamJungle;

    /**
     *   If game was a dominion game, number of node captures.
     *
     * @var int
     */
    public $nodeCapture;

    /**
     *   If game was a dominion game, number of node capture assists.
     *
     * @var int
     */
    public $nodeCaptureAssist;

    /**
     *   If game was a dominion game, number of node neutralizations.
     *
     * @var int
     */
    public $nodeNeutralize;

    /**
     *   If game was a dominion game, number of node neutralization assists.
     *
     * @var int
     */
    public $nodeNeutralizeAssist;

    /**
     *   If game was a dominion game, player's objectives score, otherwise 0.
     *
     * @var int
     */
    public $objectivePlayerScore;

    /**
     *   Number of penta kills.
     *
     * @var int
     */
    public $pentaKills;

    /**
     *   Physical damage dealt.
     *
     * @var int
     */
    public $physicalDamageDealt;

    /**
     *   Physical damage dealt to champions.
     *
     * @var int
     */
    public $physicalDamageDealtToChampions;

    /**
     *   Physical damage taken.
     *
     * @var int
     */
    public $physicalDamageTaken;

    /**
     *   Number of quadra kills.
     *
     * @var int
     */
    public $quadraKills;

    /**
     *   Sight wards purchased.
     *
     * @var int
     */
    public $sightWardsBoughtInGame;

    /**
     *   If game was a dominion game, number of completed team objectives (i.e.,
     * quests).
     *
     * @var int
     */
    public $teamObjective;

    /**
     *   Total damage dealt.
     *
     * @var int
     */
    public $totalDamageDealt;

    /**
     *   Total damage dealt to champions.
     *
     * @var int
     */
    public $totalDamageDealtToChampions;

    /**
     *   Total damage taken.
     *
     * @var int
     */
    public $totalDamageTaken;

    /**
     *   Total heal amount.
     *
     * @var int
     */
    public $totalHeal;

    /**
     *   If game was a dominion game, player's total score, otherwise 0.
     *
     * @var int
     */
    public $totalPlayerScore;

    /**
     *   If game was a dominion game, team rank of the player's total score (e.g.,
     * 1-5).
     *
     * @var int
     */
    public $totalScoreRank;

    /**
     *   Total dealt crowd control time.
     *
     * @var int
     */
    public $totalTimeCrowdControlDealt;

    /**
     *   Total units healed.
     *
     * @var int
     */
    public $totalUnitsHealed;

    /**
     *   Number of tower kills.
     *
     * @var int
     */
    public $towerKills;

    /**
     *   Number of triple kills.
     *
     * @var int
     */
    public $tripleKills;

    /**
     *   True damage dealt.
     *
     * @var int
     */
    public $trueDamageDealt;

    /**
     *   True damage dealt to champions.
     *
     * @var int
     */
    public $trueDamageDealtToChampions;

    /**
     *   True damage taken.
     *
     * @var int
     */
    public $trueDamageTaken;

    /**
     *   Number of unreal kills.
     *
     * @var int
     */
    public $unrealKills;

    /**
     *   Vision wards purchased.
     *
     * @var int
     */
    public $visionWardsBoughtInGame;

    /**
     *   Number of wards killed.
     *
     * @var int
     */
    public $wardsKilled;

    /**
     *   Number of wards placed.
     *
     * @var int
     */
    public $wardsPlaced;

    /**
     *   Flag indicating whether or not the participant won.
     *
     * @var bool
     */
    public $winner;
}
