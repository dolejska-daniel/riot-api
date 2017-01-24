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

namespace RiotAPI\Objects;


/**
 *   Class ParticipantStats
 * This object contains participant statistics information
 *
 * Used in:
 *   match (v2.2)
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 *
 * @package RiotAPI\Objects
 */
class ParticipantStats extends ApiObject
{
	/**
	 *   Number of assists.
	 *
	 * @var int $assists
	 */
	public $assists;

	/**
	 *   Champion level achieved.
	 *
	 * @var int $champLevel
	 */
	public $champLevel;

	/**
	 *   If game was a dominion game, player's combat score, otherwise 0.
	 *
	 * @var int $combatPlayerScore
	 */
	public $combatPlayerScore;

	/**
	 *   Number of deaths.
	 *
	 * @var int $deaths
	 */
	public $deaths;

	/**
	 *   Number of double kills.
	 *
	 * @var int $doubleKills
	 */
	public $doubleKills;

	/**
	 *   Flag indicating if participant got an assist on first blood.
	 *
	 * @var bool $firstBloodAssist
	 */
	public $firstBloodAssist;

	/**
	 *   Flag indicating if participant got first blood.
	 *
	 * @var bool $firstBloodKill
	 */
	public $firstBloodKill;

	/**
	 *   Flag indicating if participant got an assist on the first inhibitor.
	 *
	 * @var bool $firstInhibitorAssist
	 */
	public $firstInhibitorAssist;

	/**
	 *   Flag indicating if participant destroyed the first inhibitor.
	 *
	 * @var bool $firstInhibitorKill
	 */
	public $firstInhibitorKill;

	/**
	 *   Flag indicating if participant got an assist on the first tower.
	 *
	 * @var bool $firstTowerAssist
	 */
	public $firstTowerAssist;

	/**
	 *   Flag indicating if participant destroyed the first tower.
	 *
	 * @var bool $firstTowerKill
	 */
	public $firstTowerKill;

	/**
	 *   Gold earned.
	 *
	 * @var int $goldEarned
	 */
	public $goldEarned;

	/**
	 *   Gold spent.
	 *
	 * @var int $goldSpent
	 */
	public $goldSpent;

	/**
	 *   Number of inhibitor kills.
	 *
	 * @var int $inhibitorKills
	 */
	public $inhibitorKills;

	/**
	 *   First item ID.
	 *
	 * @var int $item0
	 */
	public $item0;

	/**
	 *   Second item ID.
	 *
	 * @var int $item1
	 */
	public $item1;

	/**
	 *   Third item ID.
	 *
	 * @var int $item2
	 */
	public $item2;

	/**
	 *   Fourth item ID.
	 *
	 * @var int $item3
	 */
	public $item3;

	/**
	 *   Fifth item ID.
	 *
	 * @var int $item4
	 */
	public $item4;

	/**
	 *   Sixth item ID.
	 *
	 * @var int $item5
	 */
	public $item5;

	/**
	 *   Seventh item ID.
	 *
	 * @var int $item6
	 */
	public $item6;

	/**
	 *   Number of killing sprees.
	 *
	 * @var int $killingSprees
	 */
	public $killingSprees;

	/**
	 *   Number of kills.
	 *
	 * @var int $kills
	 */
	public $kills;

	/**
	 *   Largest critical strike.
	 *
	 * @var int $largestCriticalStrike
	 */
	public $largestCriticalStrike;

	/**
	 *   Largest killing spree.
	 *
	 * @var int $largestKillingSpree
	 */
	public $largestKillingSpree;

	/**
	 *   Largest multi kill.
	 *
	 * @var int $largestMultiKill
	 */
	public $largestMultiKill;

	/**
	 *   Magical damage dealt.
	 *
	 * @var int $magicDamageDealt
	 */
	public $magicDamageDealt;

	/**
	 *   Magical damage dealt to champions.
	 *
	 * @var int $magicDamageDealtToChampions
	 */
	public $magicDamageDealtToChampions;

	/**
	 *   Magic damage taken.
	 *
	 * @var int $magicDamageTaken
	 */
	public $magicDamageTaken;

	/**
	 *   Minions killed.
	 *
	 * @var int $minionsKilled
	 */
	public $minionsKilled;

	/**
	 *   Neutral minions killed.
	 *
	 * @var int $neutralMinionsKilled
	 */
	public $neutralMinionsKilled;

	/**
	 *   Neutral jungle minions killed in the enemy team's jungle.
	 *
	 * @var int $neutralMinionsKilledEnemyJungle
	 */
	public $neutralMinionsKilledEnemyJungle;

	/**
	 *   Neutral jungle minions killed in your team's jungle.
	 *
	 * @var int $neutralMinionsKilledTeamJungle
	 */
	public $neutralMinionsKilledTeamJungle;

	/**
	 *   If game was a dominion game, number of node captures.
	 *
	 * @var int $nodeCapture
	 */
	public $nodeCapture;

	/**
	 *   If game was a dominion game, number of node capture assists.
	 *
	 * @var int $nodeCaptureAssist
	 */
	public $nodeCaptureAssist;

	/**
	 *   If game was a dominion game, number of node neutralizations.
	 *
	 * @var int $nodeNeutralize
	 */
	public $nodeNeutralize;

	/**
	 *   If game was a dominion game, number of node neutralization assists.
	 *
	 * @var int $nodeNeutralizeAssist
	 */
	public $nodeNeutralizeAssist;

	/**
	 *   If game was a dominion game, player's objectives score, otherwise 0.
	 *
	 * @var int $objectivePlayerScore
	 */
	public $objectivePlayerScore;

	/**
	 *   Number of penta kills.
	 *
	 * @var int $pentaKills
	 */
	public $pentaKills;

	/**
	 *   Physical damage dealt.
	 *
	 * @var int $physicalDamageDealt
	 */
	public $physicalDamageDealt;

	/**
	 *   Physical damage dealt to champions.
	 *
	 * @var int $physicalDamageDealtToChampions
	 */
	public $physicalDamageDealtToChampions;

	/**
	 *   Physical damage taken.
	 *
	 * @var int $physicalDamageTaken
	 */
	public $physicalDamageTaken;

	/**
	 *   Number of quadra kills.
	 *
	 * @var int $quadraKills
	 */
	public $quadraKills;

	/**
	 *   Sight wards purchased.
	 *
	 * @var int $sightWardsBoughtInGame
	 */
	public $sightWardsBoughtInGame;

	/**
	 *   If game was a dominion game, number of completed team objectives (i.e., 
	 * quests).
	 *
	 * @var int $teamObjective
	 */
	public $teamObjective;

	/**
	 *   Total damage dealt.
	 *
	 * @var int $totalDamageDealt
	 */
	public $totalDamageDealt;

	/**
	 *   Total damage dealt to champions.
	 *
	 * @var int $totalDamageDealtToChampions
	 */
	public $totalDamageDealtToChampions;

	/**
	 *   Total damage taken.
	 *
	 * @var int $totalDamageTaken
	 */
	public $totalDamageTaken;

	/**
	 *   Total heal amount.
	 *
	 * @var int $totalHeal
	 */
	public $totalHeal;

	/**
	 *   If game was a dominion game, player's total score, otherwise 0.
	 *
	 * @var int $totalPlayerScore
	 */
	public $totalPlayerScore;

	/**
	 *   If game was a dominion game, team rank of the player's total score (e.g., 
	 * 1-5).
	 *
	 * @var int $totalScoreRank
	 */
	public $totalScoreRank;

	/**
	 *   Total dealt crowd control time.
	 *
	 * @var int $totalTimeCrowdControlDealt
	 */
	public $totalTimeCrowdControlDealt;

	/**
	 *   Total units healed.
	 *
	 * @var int $totalUnitsHealed
	 */
	public $totalUnitsHealed;

	/**
	 *   Number of tower kills.
	 *
	 * @var int $towerKills
	 */
	public $towerKills;

	/**
	 *   Number of triple kills.
	 *
	 * @var int $tripleKills
	 */
	public $tripleKills;

	/**
	 *   True damage dealt.
	 *
	 * @var int $trueDamageDealt
	 */
	public $trueDamageDealt;

	/**
	 *   True damage dealt to champions.
	 *
	 * @var int $trueDamageDealtToChampions
	 */
	public $trueDamageDealtToChampions;

	/**
	 *   True damage taken.
	 *
	 * @var int $trueDamageTaken
	 */
	public $trueDamageTaken;

	/**
	 *   Number of unreal kills.
	 *
	 * @var int $unrealKills
	 */
	public $unrealKills;

	/**
	 *   Vision wards purchased.
	 *
	 * @var int $visionWardsBoughtInGame
	 */
	public $visionWardsBoughtInGame;

	/**
	 *   Number of wards killed.
	 *
	 * @var int $wardsKilled
	 */
	public $wardsKilled;

	/**
	 *   Number of wards placed.
	 *
	 * @var int $wardsPlaced
	 */
	public $wardsPlaced;

	/**
	 *   Flag indicating whether or not the participant won.
	 *
	 * @var bool $winner
	 */
	public $winner;
}
