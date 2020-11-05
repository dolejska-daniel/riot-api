<?php


namespace RiotAPI\LeagueAPI\Objects;


class LoRPlayerDto extends ApiObject
{
    /** @var string $puuid */
    public $puuid;

    /** @var string $deck_id */
    public $deck_id;

    /**
     *   Code for the deck played. Refer to LOR documentation for details on deck codes.
     *
     * @var string $deck_code
     */
    public $deck_code;

    /** @var string[] $factions */
    public $factions;

    /** @var string $game_outcome */
    public $game_outcome;

    /**
     *   The order in which the players took turns.
     *
     * @var string $order_of_play
     */
    public $order_of_play;
}