<?php

namespace DSG\SquadRCON\Data;

class Player
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $steamId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var Team
     */
    private ?Team $team = null;

    /**
     * @var Squad
     */
    private ?Squad $squad = null;

    /**
     * @var int|null
     */
    private ?int $disconnectedSince = null;

    function __construct(int $id, string $steamId, string $name)
    {
        $this->id       = $id;
        $this->steamId  = $steamId;
        $this->name     = $name;
    }

    /**
     * Get the ID of this Player instance.
     * 
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the SteamId of this Player instance.
     * 
     * @return string
     */
    public function getSteamId() : string
    {
        return $this->steamId;
    }

    /**
     * Get the name of this Player instance.
     * 
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the Team this player instance is assigned to.
     * 
     * @return Team|null
     */
    public function getTeam() : ?Team
    {
        return $this->team;
    }

    /**
     * Sets the Team of this Player instance
     *
     * @param Team $team
     * @return void
     */
    public function setTeam(Team $team) : void
    {
        $this->team = $team;
    }

    /**
     * Get the Squad this Player instance is assigned to.
     * 
     * @return Squad|null
     */
    public function getSquad() : ?Squad
    {
        return $this->squad;
    }

    /**
     * Sets the Squad of this Player instance
     *
     * @param Squad $squad
     * @return void
     */
    public function setSquad(Squad $squad) : void
    {
        $this->squad = $squad;
    }

    /**
     * Gets the disconnected since attribute of this Player instance.
     *
     * @return int|null
     */
    public function getDisconnectedSince() : ?int
    {
        return $this->disconnectedSince;
    }

    /**
     * Sets the disconnected since attribute of this Player instance.
     *
     * @param int $disconnectedSince Seconds since disconnect
     * @return void
     */
    public function setDisconnectedSince(int $disconnectedSince) : void
    {
        $this->disconnectedSince = $disconnectedSince;
    }
}