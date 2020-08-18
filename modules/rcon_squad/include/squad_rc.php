<?php

class Team
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var Squad[]
     */
    private array $squads = [];

    /**
     * @var Player[]
     */
    private array $players = [];

    function __construct(int $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * Get the ID of this Team instance.
     * 
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the Name of this Team instance.
     * 
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the Squads of this Team instance.
     * 
     * @return Squad[]
     */
    public function getSquads() : array
    {
        return $this->squads;
    }

    /**
     * Adds an Squad to this Team instance.
     *
     * @param Squad $squad
     * @return void
     */
    public function addSquad(Squad $squad) : void
    {
        $this->squads[$squad->getId()] = $squad;
    }

    /**
     * Get the Players of this Team instance.
     * 
     * @return Player[]
     */
    public function getPlayers() : array
    {
        return $this->players;
    }

    /**
     * Adds an Player to this Team instance.
     *
     * @param Player $player
     * @return void
     */
    public function addPlayer(Player $player) : void
    {
        $this->players[] = $player;
    }
}

class Squad
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $size;

    /**
     * @var bool
     */
    private bool $locked;

    /**
     * @var Team
     */
    private Team $team;

    /**
     * @var Player[]
     */
    private array $players = [];

    function __construct(int $id, string $name, int $size, bool $locked, Team $team)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->size   = $size;
        $this->locked = $locked;
        $this->team   = $team;
    }

    /**
     * Get the ID of this Squad instance.
     * 
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the name of this Squad instance.
     * 
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the Size of this Squad instance.
     * 
     * @return int
     */
    public function getSize() : int
    {
        return $this->size;
    }

    /**
     * Get the Lock status of this Squad instance.
     * 
     * @return bool
     */
    public function isLocked() : bool
    {
        return $this->locked;
    }

    /**
     * Get the Team of this Squad instance.
     * 
     * @return Team
     */
    public function getTeam() : Team
    {
        return $this->team;
    }

    /**
     * Get the Players of this Squad instance.
     * 
     * @return Player[]
     */
    public function getPlayers() : array
    {
        return $this->players;
    }

    /**
     * Adds an Player to this Squad instance.
     * Also References the Squad on the Player.
     *
     * @param Player $player
     * @return void
     */
    public function addPlayer(Player $player) : void
    {
        $this->players[] = $player;
        $player->setSquad($this);
    }
}

class ServerConnectionInfo {
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 3;

    /**
     * Host of the Server.
     * 
     * @var string
     */
    public string $host;

    /**
     * (RCon) Port of the Server.
     * 
     * @var int
     */
    public int $port;

    /**
     * (RCon) Password of the Server.
     * 
     * @var string
     */
    public string $password;

    /**
     * Timeout for the RCon connection.
     * 
     * @var int
     */
    public int $timeout;

    function __construct(string $host, int $port, string $password, int $timeout = self::SQUAD_SOCKET_TIMEOUT_SECONDS)
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->password = $password;

        if ($timeout <= 0) {
            throw new \InvalidArgumentException('Timeout must be greater or equal to 1');
        }
        $this->timeout  = $timeout;
    }
}

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

class RConException extends \Exception
{
    //
}

interface ServerCommandRunner {
    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listSquads() : string;

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @param array $teams
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listPlayers() : string;

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : string;

    /**
     * AdmiNkick command.
     * Kick a Player by Name or Steam64ID
     * 
     * @param string $nameOrSteamId
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKick(string $nameOrSteamId, string $reason = '') : bool;

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKickById(int $id, string $reason = '') : bool;

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBan(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool;

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBanById(int $id, string $duration = '1d', string $reason = '') : bool;

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function showNextMap() : string;

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool;

    /**
     * ChatToAdmin command.
     * Restarts the current match.
     *
     * @return boolean
     */
    public function adminRestartMatch() : bool;

    /**
     * AdminEndMatch command.
     * Ends the current Match.
     *
     * @return boolean
     */
    public function adminEndMatch() : bool;

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetMaxNumPlayers(int $slots) : bool;

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetServerPassword(string $password) : bool;

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool;

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool;

    /**
     * Disconnects the runner from any squad server instance.
     *
     * @return void
     */
    public function disconnect() : void;
}

class SquadRconRunner implements ServerCommandRunner {
    private SourceQuery $sourceQuery;

    /**
     * SquadServer constructor.
     * @param ServerConnectionInfo $info
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function __construct(ServerConnectionInfo $info)
    {
        /* Initialize the Query class */
        $this->sourceQuery = new SourceQuery();

        /* Connect to the server */
        $this->sourceQuery->Connect($info->host, $info->port, $info->timeout, SourceQuery::SQUAD);

        /* Authenticated with rcon password */
        $this->sourceQuery->SetRconPassword($info->password);
    }
    
    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listSquads() : string
    {
        return $this->sourceQuery->Rcon('ListSquads');
    }

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listPlayers() : string
    {
        /* Execute the ListPlayers command and get the response */
        return $this->sourceQuery->Rcon('ListPlayers');
    }

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : string
    {
        return $this->sourceQuery->Rcon('AdminListDisconnectedPlayers');
    }

    /**
     * AdmiNkick command.
     * Kick a Player by Name or Steam64ID
     * 
     * @param string $nameOrSteamId
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKick(string $nameOrSteamId, string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminKick', $nameOrSteamId . ' ' . $reason, 'Kicked player ');
    }

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKickById(int $id, string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminKickById', $id . ' ' . $reason, 'Kicked player ');
    }

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBan(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminBan', $nameOrSteamId . ' ' . $duration . ' ' . $reason, 'Banned player ');
    }

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBanById(int $id, string $duration = '1d', string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminBanById', $id . ' ' . $duration . ' ' . $reason, 'Banned player ');
    }

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function showNextMap() : string
    {
        return $this->sourceQuery->Rcon('ShowNextMap');
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool
    {
        return $this->_consoleCommand('AdminBroadcast', $msg, 'Message broadcasted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    public function adminRestartMatch() : bool
    {
        return $this->_consoleCommand('AdminRestartMatch', '', 'Game restarted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    public function adminEndMatch() : bool
    {
        return $this->_consoleCommand('AdminEndMatch', '', 'Match ended');
    }

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetMaxNumPlayers(int $slots) : bool
    {
        return $this->_consoleCommand('AdminSetMaxNumPlayers', $slots, 'Set MaxNumPlayers to ' . $slots);
    }

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetServerPassword(string $password) : bool
    {
        return $this->_consoleCommand('AdminSetServerPassword', $password, 'Set server password to ' . $password);
    }

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminChangeMap', $map, 'Changed map to');
    }

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminSetNextMap', $map, 'Set next map to');
    }

    /**
     * Helper method to run Console commands with an expected response.
     * 
     * @param string $cmd
     * @param string $param
     * @param string $expected
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _consoleCommand(string $cmd, string $param, string $expected) : bool
    {
        $response = $this->sourceQuery->Rcon($cmd . ' ' . $param);
        return substr($response, 0, strlen($expected)) == $expected;
    }

    /**
     * Disconnects the runner from any squad server instance.
     *
     * @return void
     */
    public function disconnect() : void
    {
        if ($this->sourceQuery) {
            $this->sourceQuery->disconnect();
        }
    }
}

class SquadServer
{
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 0.5;

    /** @var ServerCommandRunner */
    private ServerCommandRunner $runner;

    /**
     * SquadServer constructor.
     * @param $host
     * @param $port
     * @param $password
     * @param float $timeout
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function __construct(ServerConnectionInfo $serverConnectionInfo, ServerCommandRunner $runner = null)
    {
        /* Initialize the default Runner if none is specified */
        if (!$runner) {
            $runner = new SquadRconRunner($serverConnectionInfo);
        }

        $this->runner = $runner;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect() : void
    {
        $this->runner->disconnect();
    }

    /**
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function serverPopulation() : array
    {
        /* Get the current Teams and their Squads */
        $teams = $this->listSquads();

        /* Get the currently connected players, feed listSquads output to reference Teams/Squads */
        $this->currentPlayers($teams);

        return $teams;
    }

    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listSquads() : array
    {
        /** @var Team[] $teams */
        $teams = [];

        /** @var Squad[] $squads */
        $squads = [];

        /* Get the SquadList from the Server */
        $response = $this->runner->listSquads();

        /** @var Team The current team */
        $currentTeam = null;
        foreach (explode("\n", $response) as $lineSquad) {
            $matches = [];
            if (preg_match('/^Team ID: ([1|2]) \((.*)\)/', $lineSquad, $matches) > 0) {
                /* Initialize a new Team */
                $team = new Team(intval($matches[1]), $matches[2]);

                /* Add to the lookup */
                $teams[$team->getId()] = $team;
                
                /* Initialize squad lookup array */
                $squads[$team->getId()] = [];

                /* Set as current team */
                $currentTeam = $team;
            } else if (preg_match('/^ID: (\d{1,}) \| Name: (.*?) \| Size: (\d) \| Locked: (True|False)/', $lineSquad, $matches) > 0) {
                /* Initialize a new Squad */
                $squad = new Squad(intval($matches[1]), $matches[2], intval($matches[3]), $matches[4] === 'True', $currentTeam);
                
                /* Reference Team */
                $currentTeam->addSquad($squad);

                /* Add to the squads lookup */
                $squads[$currentTeam->getId()][$squad->getId()] = $squad;
            }
        }

        return $teams;
    }

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @param array $teams
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     * @deprecated 0.1.3 Use listPlayers instead
     */
    public function currentPlayers(array &$teams = null) : array
    {
        return $this->listPlayers($teams);
    }

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @param array $teams
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listPlayers(array &$teams = null) : array
    {
        /* Initialize an empty output array */
        $players = [];

        /* Execute the ListPlayers command and get the response */
        $response = $this->runner->listPlayers();

        /* Process each individual line */
        foreach (explode("\n", $response) as $line) {
            /* Initialize an empty array and try to get info form line */
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Name: (.*?) \| Team ID: (1|2|N\/A) \| Squad ID: (\d{1,}|N\/A)/', $line, $matches)) {
                /* Initialize new Player instance */
                $player = new Player(intval($matches[1]), $matches[2], $matches[3]);

                /* Set Team and Squad references if ListSquads output is provided */
                if ($teams && count($teams) && $matches[4] !== 'N/A' && array_key_exists($matches[4], $teams)) {
                    /* Get the Team */
                    $player->setTeam($teams[$matches[4]]);

                    if (count($player->getTeam()->getSquads()) && $matches[5] !== 'N/A' && array_key_exists($matches[5], $player->getTeam()->getSquads())) {
                        /* Get the Squad */
                        $squad = $player->getTeam()->getSquads()[$matches[5]];

                        /* Add the Player to the Squad */
                        $squad->addPlayer($player);
                    } else {
                        /* Add as unassigned Player to the Team instance */
                        $player->getTeam()->addPlayer($player);
                    }
                }

                /* Add to the output */
                $players[] = $player;
            } else if (preg_match('/^-{5} Recently Disconnected Players \[Max of 15\] -{5}/', $line)) {
                /* Notihing of interest, break the loop */
                break;
            }
        }

        return $players;
    }

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : array
    {
        /* Initialize an empty output array */
        $players = [];

        /* Execute the ListPlayers command and get the response */
        $response = $this->runner->listPlayers();

        /* Process each individual line */
        foreach (explode("\n", $response) as $line) {
            /* Initialize an empty array and try to get info form line */
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Since Disconnect: (\d{2,})m.(\d{2})s \| Name: (.*?)$/', $line, $matches)) {
                /* Initialize new Player instance */
                $player = new Player(intval($matches[1]), $matches[2], $matches[5]);

                /* Set the disconnected since time */
                $player->setDisconnectedSince(intval($matches[3]) * 60 + intval($matches[4]));

                /* Add to the output */
                $players[] = $player;
            }
        }

        return $players;
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function kick(string $nameOrSteamId, string $reason = '') : bool
    {
        return $this->runner->adminKick($nameOrSteamId, $reason);
    }

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function kickById(int $id, string $reason = '') : bool
    {
        return $this->runner->adminKickById($id, $reason);
    }

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function ban(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool
    {
        return $this->runner->adminBan($nameOrSteamId, $duration, $reason);
    }

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function banById(int $id, string $duration = '1d', string $reason = '') : bool
    {
        return $this->runner->adminBanById($id, $duration, $reason);
    }

    /**
     * Gets the current map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function currentMap() : string
    {
        return $this->currentMaps()['current'];
    }

    /**
     * Gets the current next map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function nextMap() : string
    {
        return $this->currentMaps()['next'];
    }

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function currentMaps() : array
    {
        /* Initialize the output */
        $maps = [
            'current' => null,
            'next' => null
        ];

        /* Run the ShowNextMap Command and get response */
        $response = $this->runner->showNextMap("ShowNextMap");

        /* Parse response */
        $arr = explode(', Next map is ', $response);
        if (count($arr) > 1) {
            $next = trim($arr[1]);
            $curr = substr($arr[0], strlen('Current map is '));
            $maps['current'] = $curr;
            $maps['next'] = $next;
        }

        return $maps;
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool
    {
        return $this->runner->adminBroadcast($msg);
    }

    /**
     * ChatToAdmin command.
     * Restarts the current match.
     *
     * @return boolean
     */
    public function restartMatch() : bool
    {
        return $this->runner->adminRestartMatch();
    }

    /**
     * AdminEndMatch command.
     * Ends the current Match.
     *
     * @return boolean
     */
    public function endMatch() : bool
    {
        return $this->runner->adminEndMatch();
    }

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function setSlots(int $slots = 78) : bool
    {
        return $this->runner->adminSetMaxNumPlayers($slots);
    }

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function setPassword(string $password) : bool
    {
        return $this->runner->adminSetServerPassword($password);
    }

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool
    {
        return $this->runner->adminChangeMap($map);
    }

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool
    {
        return $this->runner->adminSetNextMap($map);
    }
}

class SquadRcon
{
    /**
     * Points to socket class
     */
    private BaseSocket $Socket;
    
    /** @var resource */
    private $RconSocket;
    private int $RconRequestId = 0;
    private bool $IsMulti = false;
    private string $CalledCommand = '';
    
    public function __construct( BaseSocket $Socket )
    {
        $this->Socket = $Socket;
    }
    
    public function Close( ) : void
    {
        if( $this->RconSocket )
        {
            FClose( $this->RconSocket );
            
            $this->RconSocket = null;
        }
        
        $this->RconRequestId = 0;
    }
    
    public function Open( ) : void
    {
        if( !$this->RconSocket )
        {
            $this->RconSocket = @FSockOpen( $this->Socket->Address, $this->Socket->Port, $ErrNo, $ErrStr, $this->Socket->Timeout );
            
            if( $ErrNo || !$this->RconSocket )
            {
                throw new SocketException( 'Can\'t connect to RCON server: ' . $ErrStr, SocketException::CONNECTION_FAILED );
            }
            
            Stream_Set_Timeout( $this->RconSocket, $this->Socket->Timeout );
            Stream_Set_Blocking( $this->RconSocket, true );
        }
    }
    
    public function Write( int $Header, string $String = '' ) : bool
    {
        // Pack the packet together
        $Command = Pack( 'VV', ++$this->RconRequestId, $Header ) . $String . "\x00\x00"; 
        
        // Prepend packet length
        $Command = Pack( 'V', StrLen( $Command ) ) . $Command;
        $Length  = StrLen( $Command );
        
        return $Length === FWrite( $this->RconSocket, $Command, $Length );
    }
    
    public function Read( ) : ?Buffer
    {
        $Buffer = new Buffer( );
        $Buffer->Set( FRead( $this->RconSocket, 4 ) );
        
        if( $Buffer->Remaining( ) < 4 )
        {
            if (!$this->IsMulti)
            {
                throw new InvalidPacketException( 'Rcon read: Failed to read any data from socket', InvalidPacketException::BUFFER_EMPTY );
            } else {
                return null;
            }
        }

        $Encoding = 'ASCII';
        
        $PacketSize = $Buffer->GetLong( );
        $PacketData = FRead( $this->RconSocket, $PacketSize );

        if ($this->CalledCommand === 'listplayers' || substr( $this->CalledCommand, 0, 8 ) === 'adminban' || substr( $this->CalledCommand, 0, 9 ) === 'adminkick')
        {
            if ( (strlen(rtrim(substr($PacketData, 8), '\0'))) > (strlen(str_replace('\0','',substr($PacketData, 8)))) )
            {
                $PacketData .= FRead( $this->RconSocket, ( $PacketSize - 9 ) );

                $PacketSize += $PacketSize - 9;

                $Encoding = 'UTF-8';
            }
        }
        
        $Buffer->Set( $PacketData );
        
        $Data = $Buffer->Get( );
        
        $Remaining = $PacketSize - StrLen( $Data );
        
        while( $Remaining > 0 )
        {
            $Data2 = FRead( $this->RconSocket, $Remaining );
            
            $PacketSize = StrLen( $Data2 );
            
            if( $PacketSize === 0 )
            {
                throw new InvalidPacketException( 'Read ' . strlen( $Data ) . ' bytes from socket, ' . $Remaining . ' remaining', InvalidPacketException::BUFFER_EMPTY );
                
                break;
            }
            
            $Data .= $Data2;
            $Remaining -= $PacketSize;
        }

        if ($Encoding === 'UTF-8')
        {
            $n = 8;
            $CountUTF = 0;
            $BodyEncode = '';
            while (StrLen(substr($Data, 8)) > $n - 8)
            {

                $TwoBytes=bin2hex(substr($Data,($n),2));

                if (($TwoBytes == '0000') || ($TwoBytes == '00'))
                {
                    $BodyEncode .= "\0";
                } 
                else
                {
                    $BodyEncode .= html_entity_decode(("&#x" . substr($TwoBytes,2,2) . substr($TwoBytes,0,2) . ";"), ENT_COMPAT, 'UTF-8');
                }

                if ((substr($TwoBytes,2,2) !== '00') && (substr($TwoBytes,0,2) !== '00'))
                {
                    $CountUTF = $CountUTF + 1;
                }
                else
                {
                    //
                }

                $n = $n + 2;
            }
            //Replace single quotes html encoding with actual single quotes
            $BodyEncode = str_replace('&#x0027;', '\'', $BodyEncode);
            $Data = substr($Data, 0, 8) . $BodyEncode;
        }
        
        $Buffer->Set( $Data );
        
        return $Buffer;
    }
    
    public function Command( string $Command ) : string
    {
        $this->CalledCommand = strtolower($Command);
        $this->IsMulti = false;

        $this->Write( SourceQuery::SERVERDATA_EXECCOMMAND, $Command );
        $Buffer = $this->Read( );
        
        $Buffer->GetLong( ); // RequestID
        
        $Type = $Buffer->GetLong( );
        
        if( $Type === SourceQuery::SERVERDATA_AUTH_RESPONSE )
        {
            throw new AuthenticationException( 'Bad rcon_password.', AuthenticationException::BAD_PASSWORD );
        }
        else if( $Type !== SourceQuery::SERVERDATA_RESPONSE_VALUE )
        {
            throw new InvalidPacketException( 'Invalid rcon response.', InvalidPacketException::PACKET_HEADER_MISMATCH );
        }
        
        $Data = $Buffer->Get( );
        
        // We do this stupid hack to handle split packets
        // See https://developer.valvesoftware.com/wiki/Source_RCON_Protocol#Multiple-packet_Responses
        if( mb_strlen( $Data ) >= 4000 )
        {
            $this->IsMulti = true;

            $this->Write( SourceQuery::SERVERDATA_RESPONSE_VALUE );
            
            do
            {	
                $Buffer = $this->Read( );

                if (!$Buffer) {
                    break;
                }
                
                $Buffer->GetLong( ); // RequestID
                
                if( $Buffer->GetLong( ) !== SourceQuery::SERVERDATA_RESPONSE_VALUE )
                {
                    break;
                }
                
                $Data2 = $Buffer->Get( );
                
                if( $Data2 === "\x00\x01\x00\x00\x00\x00" )
                {
                    break;
                }
                
                $Data .= $Data2;

                if(mb_strlen( $Data2 ) < 4000)
                {
                    break;
                }
            }
            while( true );
        }

        return rtrim( $Data, '\0' );
    }
    
    public function Authorize( string $Password ) : void
    {
        $this->Write( SourceQuery::SERVERDATA_AUTH, $Password );
        $Buffer = $this->Read( );
        
        $RequestID = $Buffer->GetLong( );
        $Type      = $Buffer->GetLong( );
        
        // If we receive SERVERDATA_RESPONSE_VALUE, then we need to read again
        // More info: https://developer.valvesoftware.com/wiki/Source_RCON_Protocol#Additional_Comments
        
        if( $Type === SourceQuery::SERVERDATA_RESPONSE_VALUE )
        {
            $Buffer = $this->Read( );
            
            $RequestID = $Buffer->GetLong( );
            $Type      = $Buffer->GetLong( );
        }
        
        if( $RequestID === -1 || $Type !== SourceQuery::SERVERDATA_AUTH_RESPONSE )
        {
            throw new AuthenticationException( 'RCON authorization failed.', AuthenticationException::BAD_PASSWORD );
        }
    }
}

class SourceQuery
	{
		/**
		 * Engines
		 */
		const GOLDSOURCE = 0;
		const SOURCE     = 1;
		const SQUAD      = 2;
		
		/**
		 * Packets sent
		 */
		const A2S_PING      = 0x69;
		const A2S_INFO      = 0x54;
		const A2S_PLAYER    = 0x55;
		const A2S_RULES     = 0x56;
		const A2S_SERVERQUERY_GETCHALLENGE = 0x57;
		
		/**
		 * Packets received
		 */
		const S2A_PING      = 0x6A;
		const S2A_CHALLENGE = 0x41;
		const S2A_INFO      = 0x49;
		const S2A_INFO_OLD  = 0x6D; // Old GoldSource, HLTV uses it
		const S2A_PLAYER    = 0x44;
		const S2A_RULES     = 0x45;
		const S2A_RCON      = 0x6C;
		
		/**
		 * Source rcon sent
		 */
		const SERVERDATA_EXECCOMMAND    = 2;
		const SERVERDATA_AUTH           = 3;
		
		/**
		 * Source rcon received
		 */
		const SERVERDATA_RESPONSE_VALUE = 0;
		const SERVERDATA_AUTH_RESPONSE  = 2;
		
		/**
		 * Points to rcon class
		 * 
		 * @var SourceRcon|GoldSourceRcon|SquadRcon|null
		 */
		private $Rcon;
		
		/**
		 * Points to socket class
		 */
		private BaseSocket $Socket;
		
		/**
		 * True if connection is open, false if not
		 */
		private bool $Connected = false;
		
		/**
		 * Contains challenge
		 */
		private string $Challenge = '';
		
		/**
		 * Use old method for getting challenge number
		 */
		private bool $UseOldGetChallengeMethod = false;
		
		public function __construct( BaseSocket $Socket = null )
		{
			$this->Socket = $Socket ?: new Socket( );
		}
		
		public function __destruct( )
		{
			$this->Disconnect( );
		}
		
		/**
		 * Opens connection to server
		 *
		 * @param string $Address Server ip
		 * @param int $Port Server port
		 * @param int $Timeout Timeout period
		 * @param int $Engine Engine the server runs on (goldsource, source)
		 *
		 * @throws InvalidArgumentException
		 * @throws SocketException
		 */
		public function Connect( string $Address, int $Port, int $Timeout = 3, int $Engine = self::SOURCE ) : void
		{
			$this->Disconnect( );
			
			if( $Timeout < 0 )
			{
				throw new InvalidArgumentException( 'Timeout must be a positive integer.', InvalidArgumentException::TIMEOUT_NOT_INTEGER );
			}
			
			$this->Socket->Open( $Address, $Port, $Timeout, $Engine );
			
			$this->Connected = true;
		}
		
		/**
		 * Forces GetChallenge to use old method for challenge retrieval because some games use outdated protocol (e.g Starbound)
		 *
		 * @param bool $Value Set to true to force old method
		 *
		 * @returns bool Previous value
		 */
		public function SetUseOldGetChallengeMethod( bool $Value ) : bool
		{
			$Previous = $this->UseOldGetChallengeMethod;
			
			$this->UseOldGetChallengeMethod = $Value === true;
			
			return $Previous;
		}
		
		/**
		 * Closes all open connections
		 */
		public function Disconnect( ) : void
		{
			$this->Connected = false;
			$this->Challenge = '';
			
			$this->Socket->Close( );
			
			if( $this->Rcon )
			{
				$this->Rcon->Close( );
				
				$this->Rcon = null;
			}
		}
		
		/**
		 * Sends ping packet to the server
		 * NOTE: This may not work on some games (TF2 for example)
		 *
		 * @throws InvalidPacketException
		 * @throws SocketException
		 *
		 * @return bool True on success, false on failure
		 */
		public function Ping( ) : bool
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			$this->Socket->Write( self::A2S_PING );
			$Buffer = $this->Socket->Read( );
			
			return $Buffer->GetByte( ) === self::S2A_PING;
		}
		
		/**
		 * Get server information
		 *
		 * @throws InvalidPacketException
		 * @throws SocketException
		 *
		 * @return array Returns an array with information on success
		 */
		public function GetInfo( ) : array
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			$this->Socket->Write( self::A2S_INFO, "Source Engine Query\0" );
			$Buffer = $this->Socket->Read( );
			
			$Type = $Buffer->GetByte( );
			$Server = [];
			
			// Old GoldSource protocol, HLTV still uses it
			if( $Type === self::S2A_INFO_OLD && $this->Socket->Engine === self::GOLDSOURCE )
			{
				/**
				 * If we try to read data again, and we get the result with type S2A_INFO (0x49)
				 * That means this server is running dproto,
				 * Because it sends answer for both protocols
				 */
				
				$Server[ 'Address' ]    = $Buffer->GetString( );
				$Server[ 'HostName' ]   = $Buffer->GetString( );
				$Server[ 'Map' ]        = $Buffer->GetString( );
				$Server[ 'ModDir' ]     = $Buffer->GetString( );
				$Server[ 'ModDesc' ]    = $Buffer->GetString( );
				$Server[ 'Players' ]    = $Buffer->GetByte( );
				$Server[ 'MaxPlayers' ] = $Buffer->GetByte( );
				$Server[ 'Protocol' ]   = $Buffer->GetByte( );
				$Server[ 'Dedicated' ]  = Chr( $Buffer->GetByte( ) );
				$Server[ 'Os' ]         = Chr( $Buffer->GetByte( ) );
				$Server[ 'Password' ]   = $Buffer->GetByte( ) === 1;
				$Server[ 'IsMod' ]      = $Buffer->GetByte( ) === 1;
				
				if( $Server[ 'IsMod' ] )
				{
					$Mod = [];
					$Mod[ 'Url' ]        = $Buffer->GetString( );
					$Mod[ 'Download' ]   = $Buffer->GetString( );
					$Buffer->Get( 1 ); // NULL byte
					$Mod[ 'Version' ]    = $Buffer->GetLong( );
					$Mod[ 'Size' ]       = $Buffer->GetLong( );
					$Mod[ 'ServerSide' ] = $Buffer->GetByte( ) === 1;
					$Mod[ 'CustomDLL' ]  = $Buffer->GetByte( ) === 1;
					$Server[ 'Mod' ] = $Mod;
				}
				
				$Server[ 'Secure' ]   = $Buffer->GetByte( ) === 1;
				$Server[ 'Bots' ]     = $Buffer->GetByte( );
				
				return $Server;
			}
			
			if( $Type !== self::S2A_INFO )
			{
				throw new InvalidPacketException( 'GetInfo: Packet header mismatch. (0x' . DecHex( $Type ) . ')', InvalidPacketException::PACKET_HEADER_MISMATCH );
			}
			
			$Server[ 'Protocol' ]   = $Buffer->GetByte( );
			$Server[ 'HostName' ]   = $Buffer->GetString( );
			$Server[ 'Map' ]        = $Buffer->GetString( );
			$Server[ 'ModDir' ]     = $Buffer->GetString( );
			$Server[ 'ModDesc' ]    = $Buffer->GetString( );
			$Server[ 'AppID' ]      = $Buffer->GetShort( );
			$Server[ 'Players' ]    = $Buffer->GetByte( );
			$Server[ 'MaxPlayers' ] = $Buffer->GetByte( );
			$Server[ 'Bots' ]       = $Buffer->GetByte( );
			$Server[ 'Dedicated' ]  = Chr( $Buffer->GetByte( ) );
			$Server[ 'Os' ]         = Chr( $Buffer->GetByte( ) );
			$Server[ 'Password' ]   = $Buffer->GetByte( ) === 1;
			$Server[ 'Secure' ]     = $Buffer->GetByte( ) === 1;
			
			// The Ship (they violate query protocol spec by modifying the response)
			if( $Server[ 'AppID' ] === 2400 )
			{
				$Server[ 'GameMode' ]     = $Buffer->GetByte( );
				$Server[ 'WitnessCount' ] = $Buffer->GetByte( );
				$Server[ 'WitnessTime' ]  = $Buffer->GetByte( );
			}
			
			$Server[ 'Version' ] = $Buffer->GetString( );
			
			// Extra Data Flags
			if( $Buffer->Remaining( ) > 0 )
			{
				$Server[ 'ExtraDataFlags' ] = $Flags = $Buffer->GetByte( );
				
				// S2A_EXTRA_DATA_HAS_GAME_PORT - Next 2 bytes include the game port.
				if( $Flags & 0x80 )
				{
					$Server[ 'GamePort' ] = $Buffer->GetShort( );
				}
				
				// S2A_EXTRA_DATA_HAS_STEAMID - Next 8 bytes are the steamID
				// Want to play around with this?
				// You can use https://github.com/xPaw/SteamID.php
				if( $Flags & 0x10 )
				{
					$SteamIDLower    = $Buffer->GetUnsignedLong( );
					$SteamIDInstance = $Buffer->GetUnsignedLong( ); // This gets shifted by 32 bits, which should be steamid instance
					$SteamID = 0;
					
					if( PHP_INT_SIZE === 4 )
					{
						if( extension_loaded( 'gmp' ) )
						{
							$SteamIDLower    = gmp_abs( $SteamIDLower );
							$SteamIDInstance = gmp_abs( $SteamIDInstance );
							$SteamID         = gmp_strval( gmp_or( $SteamIDLower, gmp_mul( $SteamIDInstance, gmp_pow( 2, 32 ) ) ) );
						}
						else
						{
							throw new \RuntimeException( 'Either 64-bit PHP installation or "gmp" module is required to correctly parse server\'s steamid.' );
						}
					}
					else
					{
						$SteamID = $SteamIDLower | ( $SteamIDInstance << 32 );
					}
					
					$Server[ 'SteamID' ] = $SteamID;
					
					unset( $SteamIDLower, $SteamIDInstance, $SteamID );
				}
				
				// S2A_EXTRA_DATA_HAS_SPECTATOR_DATA - Next 2 bytes include the spectator port, then the spectator server name.
				if( $Flags & 0x40 )
				{
					$Server[ 'SpecPort' ] = $Buffer->GetShort( );
					$Server[ 'SpecName' ] = $Buffer->GetString( );
				}
				
				// S2A_EXTRA_DATA_HAS_GAMETAG_DATA - Next bytes are the game tag string
				if( $Flags & 0x20 )
				{
					$Server[ 'GameTags' ] = $Buffer->GetString( );
				}
				
				// S2A_EXTRA_DATA_GAMEID - Next 8 bytes are the gameID of the server
				if( $Flags & 0x01 )
				{
					$Server[ 'GameID' ] = $Buffer->GetUnsignedLong( ) | ( $Buffer->GetUnsignedLong( ) << 32 ); 
				}
				
				if( $Buffer->Remaining( ) > 0 )
				{
					throw new InvalidPacketException( 'GetInfo: unread data? ' . $Buffer->Remaining( ) . ' bytes remaining in the buffer. Please report it to the library developer.',
						InvalidPacketException::BUFFER_NOT_EMPTY );
				}
			}
			
			return $Server;
		}
		
		/**
		 * Get players on the server
		 *
		 * @throws InvalidPacketException
		 * @throws SocketException
		 * 
		 * @return array Returns an array with players on success
		 */
		public function GetPlayers( ) : array
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			$this->GetChallenge( self::A2S_PLAYER, self::S2A_PLAYER );
			
			$this->Socket->Write( self::A2S_PLAYER, $this->Challenge );
			$Buffer = $this->Socket->Read( 14000 ); // Moronic Arma 3 developers do not split their packets, so we have to read more data
			// This violates the protocol spec, and they probably should fix it: https://developer.valvesoftware.com/wiki/Server_queries#Protocol
			
			$Type = $Buffer->GetByte( );
			
			if( $Type !== self::S2A_PLAYER )
			{
				throw new InvalidPacketException( 'GetPlayers: Packet header mismatch. (0x' . DecHex( $Type ) . ')', InvalidPacketException::PACKET_HEADER_MISMATCH );
			}
			
			$Players = [];
			$Count   = $Buffer->GetByte( );
			
			while( $Count-- > 0 && $Buffer->Remaining( ) > 0 )
			{
				$Player = [];
				$Player[ 'Id' ]    = $Buffer->GetByte( ); // PlayerID, is it just always 0?
				$Player[ 'Name' ]  = $Buffer->GetString( );
				$Player[ 'Frags' ] = $Buffer->GetLong( );
				$Player[ 'Time' ]  = (int)$Buffer->GetFloat( );
				$Player[ 'TimeF' ] = GMDate( ( $Player[ 'Time' ] > 3600 ? "H:i:s" : "i:s" ), $Player[ 'Time' ] );
				
				$Players[ ] = $Player;
			}
			
			return $Players;
		}
		
		/**
		 * Get rules (cvars) from the server
		 *
		 * @throws InvalidPacketException
		 * @throws SocketException
		 *
		 * @return array Returns an array with rules on success
		 */
		public function GetRules( ) : array
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			$this->GetChallenge( self::A2S_RULES, self::S2A_RULES );
			
			$this->Socket->Write( self::A2S_RULES, $this->Challenge );
			$Buffer = $this->Socket->Read( );
			
			$Type = $Buffer->GetByte( );
			
			if( $Type !== self::S2A_RULES )
			{
				throw new InvalidPacketException( 'GetRules: Packet header mismatch. (0x' . DecHex( $Type ) . ')', InvalidPacketException::PACKET_HEADER_MISMATCH );
			}
			
			$Rules = [];
			$Count = $Buffer->GetShort( );
			
			while( $Count-- > 0 && $Buffer->Remaining( ) > 0 )
			{
				$Rule  = $Buffer->GetString( );
				$Value = $Buffer->GetString( );
				
				if( !Empty( $Rule ) )
				{
					$Rules[ $Rule ] = $Value;
				}
			}
			
			return $Rules;
		}
		
		/**
		 * Get challenge (used for players/rules packets)
		 *
		 * @throws InvalidPacketException
		 */
		private function GetChallenge( int $Header, int $ExpectedResult ) : void
		{
			if( $this->Challenge )
			{
				return;
			}
			
			if( $this->UseOldGetChallengeMethod )
			{
				$Header = self::A2S_SERVERQUERY_GETCHALLENGE;
			}
			
			$this->Socket->Write( $Header, "\xFF\xFF\xFF\xFF" );
			$Buffer = $this->Socket->Read( );
			
			$Type = $Buffer->GetByte( );
			
			switch( $Type )
			{
				case self::S2A_CHALLENGE:
				{
					$this->Challenge = $Buffer->Get( 4 );
					
					return;
				}
				case $ExpectedResult:
				{
					// Goldsource (HLTV)
					
					return;
				}
				case 0:
				{
					throw new InvalidPacketException( 'GetChallenge: Failed to get challenge.' );
				}
				default:
				{
					throw new InvalidPacketException( 'GetChallenge: Packet header mismatch. (0x' . DecHex( $Type ) . ')', InvalidPacketException::PACKET_HEADER_MISMATCH );
				}
			}
		}
		
		/**
		 * Sets rcon password, for future use in Rcon()
		 *
		 * @param string $Password Rcon Password
		 *
		 * @throws AuthenticationException
		 * @throws InvalidPacketException
		 * @throws SocketException
		 */
		public function SetRconPassword( string $Password ) : void
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			switch( $this->Socket->Engine )
			{
				case SourceQuery::GOLDSOURCE:
				{
					$this->Rcon = new GoldSourceRcon( $this->Socket );
					
					break;
				}
				case SourceQuery::SOURCE:
				{
					$this->Rcon = new SourceRcon( $this->Socket );
					
					break;
				}
				case SourceQuery::SQUAD:
				{
					$this->Rcon = new SquadRcon( $this->Socket );
					
					break;
				}
				default:
				{
					throw new SocketException( 'Unknown engine.', SocketException::INVALID_ENGINE );
				}
			}
			
			$this->Rcon->Open( );
			$this->Rcon->Authorize( $Password );
		}
		
		/**
		 * Sends a command to the server for execution.
		 *
		 * @param string $Command Command to execute
		 *
		 * @throws AuthenticationException
		 * @throws InvalidPacketException
		 * @throws SocketException
		 *
		 * @return string Answer from server in string
		 */
		public function Rcon( string $Command ) : string
		{
			if( !$this->Connected )
			{
				throw new SocketException( 'Not connected.', SocketException::NOT_CONNECTED );
			}
			
			if( $this->Rcon === null )
			{
				throw new SocketException( 'You must set a RCON password before trying to execute a RCON command.', SocketException::NOT_CONNECTED );
			}
			
			return $this->Rcon->Command( $Command );
		}
	}

    abstract class BaseSocket
	{
		/** @var resource */
		public $Socket;
		public int $Engine;
		
		public string $Address;
		public int $Port;
		public int $Timeout;
		
		public function __destruct( )
		{
			$this->Close( );
		}
		
		abstract public function Close( ) : void;
		abstract public function Open( string $Address, int $Port, int $Timeout, int $Engine ) : void;
		abstract public function Write( int $Header, string $String = '' ) : bool;
		abstract public function Read( int $Length = 1400 ) : Buffer;
		
		protected function ReadInternal( Buffer $Buffer, int $Length, callable $SherlockFunction ) : Buffer
		{
			if( $Buffer->Remaining( ) === 0 )
			{
				throw new InvalidPacketException( 'Failed to read any data from socket', InvalidPacketException::BUFFER_EMPTY );
			}
			
			$Header = $Buffer->GetLong( );
			
			if( $Header === -1 ) // Single packet
			{
				// We don't have to do anything
			}
			else if( $Header === -2 ) // Split packet
			{
				$Packets      = [];
				$IsCompressed = false;
				$ReadMore     = false;
				$PacketChecksum = null;
				
				do
				{
					$RequestID = $Buffer->GetLong( );
					
					switch( $this->Engine )
					{
						case SourceQuery::GOLDSOURCE:
						{
							$PacketCountAndNumber = $Buffer->GetByte( );
							$PacketCount          = $PacketCountAndNumber & 0xF;
							$PacketNumber         = $PacketCountAndNumber >> 4;
							
							break;
						}
						case SourceQuery::SOURCE:
						{
							$IsCompressed         = ( $RequestID & 0x80000000 ) !== 0;
							$PacketCount          = $Buffer->GetByte( );
							$PacketNumber         = $Buffer->GetByte( ) + 1;
							
							if( $IsCompressed )
							{
								$Buffer->GetLong( ); // Split size
								
								$PacketChecksum = $Buffer->GetUnsignedLong( );
							}
							else
							{
								$Buffer->GetShort( ); // Split size
							}
							
							break;
						}
						default:
						{
							throw new SocketException( 'Unknown engine.', SocketException::INVALID_ENGINE );
						}
					}
					
					$Packets[ $PacketNumber ] = $Buffer->Get( );
					
					$ReadMore = $PacketCount > sizeof( $Packets );
				}
				while( $ReadMore && $SherlockFunction( $Buffer, $Length ) );
				
				$Data = Implode( $Packets );
				
				// TODO: Test this
				if( $IsCompressed )
				{
					// Let's make sure this function exists, it's not included in PHP by default
					if( !Function_Exists( 'bzdecompress' ) )
					{
						throw new \RuntimeException( 'Received compressed packet, PHP doesn\'t have Bzip2 library installed, can\'t decompress.' );
					}
					
					$Data = bzdecompress( $Data );
					
					if( !is_string( $Data ) || CRC32( $Data ) !== $PacketChecksum )
					{
						throw new InvalidPacketException( 'CRC32 checksum mismatch of uncompressed packet data.', InvalidPacketException::CHECKSUM_MISMATCH );
					}
				}
				
				$Buffer->Set( SubStr( $Data, 4 ) );
			}
			else
			{
				throw new InvalidPacketException( 'Socket read: Raw packet header mismatch. (0x' . DecHex( $Header ) . ')', InvalidPacketException::PACKET_HEADER_MISMATCH );
			}
			
			return $Buffer;
		}
	}

    class Socket extends BaseSocket
	{
		public function Close( ) : void
		{
			if( $this->Socket !== null )
			{
				FClose( $this->Socket );
				
				$this->Socket = null;
			}
		}
		
		public function Open( string $Address, int $Port, int $Timeout, int $Engine ) : void
		{
			$this->Timeout = $Timeout;
			$this->Engine  = $Engine;
			$this->Port    = $Port;
			$this->Address = $Address;
			
			$this->Socket = @FSockOpen( 'udp://' . $Address, $Port, $ErrNo, $ErrStr, $Timeout );
			
			if( $ErrNo || $this->Socket === false )
			{
				throw new SocketException( 'Could not create socket: ' . $ErrStr, SocketException::COULD_NOT_CREATE_SOCKET );
			}
			
			Stream_Set_Timeout( $this->Socket, $Timeout );
			Stream_Set_Blocking( $this->Socket, true );
		}
		
		public function Write( int $Header, string $String = '' ) : bool
		{
			$Command = Pack( 'ccccca*', 0xFF, 0xFF, 0xFF, 0xFF, $Header, $String );
			$Length  = StrLen( $Command );
			
			return $Length === FWrite( $this->Socket, $Command, $Length );
		}
		
		/**
		 * Reads from socket and returns Buffer.
		 *
		 * @throws InvalidPacketException
		 *
		 * @return Buffer Buffer
		 */
		public function Read( int $Length = 1400 ) : Buffer
		{
			$Buffer = new Buffer( );
			$Buffer->Set( FRead( $this->Socket, $Length ) );
			
			$this->ReadInternal( $Buffer, $Length, [ $this, 'Sherlock' ] );
			
			return $Buffer;
		}
		
		public function Sherlock( Buffer $Buffer, int $Length ) : bool
		{
			$Data = FRead( $this->Socket, $Length );
			
			if( StrLen( $Data ) < 4 )
			{
				return false;
			}
			
			$Buffer->Set( $Data );
			
			return $Buffer->GetLong( ) === -2;
		}
    }
    
    class SocketException extends SourceQueryException
	{
		const COULD_NOT_CREATE_SOCKET = 1;
		const NOT_CONNECTED = 2;
		const CONNECTION_FAILED = 3;
		const INVALID_ENGINE = 3;
	}

    abstract class SourceQueryException extends \Exception
	{
		// Base exception class
    }
    
    class Buffer
	{
		/**
		 * Buffer
		 */
		private string $Buffer = '';
		
		/**
		 * Buffer length
		 */
		private int $Length = 0;
		
		/**
		 * Current position in buffer
		 */
		private int $Position = 0;
		
		/**
		 * Sets buffer
		 */
		public function Set( string $Buffer ) : void
		{
			$this->Buffer   = $Buffer;
			$this->Length   = StrLen( $Buffer );
			$this->Position = 0;
		}
		
		/**
		 * Get remaining bytes
		 *
		 * @return int Remaining bytes in buffer
		 */
		public function Remaining( ) : int
		{
			return $this->Length - $this->Position;
		}
		
		/**
		 * Gets data from buffer
		 *
		 * @param int $Length Bytes to read
		 */
		public function Get( int $Length = -1 ) : string
		{
			if( $Length === 0 )
			{
				return '';
			}
			
			$Remaining = $this->Remaining( );
			
			if( $Length === -1 )
			{
				$Length = $Remaining;
			}
			else if( $Length > $Remaining )
			{
				return '';
			}
			
			$Data = SubStr( $this->Buffer, $this->Position, $Length );
			
			$this->Position += $Length;
			
			return $Data;
		}
		
		/**
		 * Get byte from buffer
		 */
		public function GetByte( ) : int
		{
			return Ord( $this->Get( 1 ) );
		}
		
		/**
		 * Get short from buffer
		 */
		public function GetShort( ) : int
		{
			if( $this->Remaining( ) < 2 )
			{
				throw new InvalidPacketException( 'Not enough data to unpack a short.', InvalidPacketException::BUFFER_EMPTY );
			}
			
			$Data = UnPack( 'v', $this->Get( 2 ) );
			
			return (int)$Data[ 1 ];
		}
		
		/**
		 * Get long from buffer
		 */
		public function GetLong( ) : int
		{
			if( $this->Remaining( ) < 4 )
			{
				throw new InvalidPacketException( 'Not enough data to unpack a long.', InvalidPacketException::BUFFER_EMPTY );
			}
			
			$Data = UnPack( 'l', $this->Get( 4 ) );
			
			return (int)$Data[ 1 ];
		}
		
		/**
		 * Get float from buffer
		 */
		public function GetFloat( ) : float
		{
			if( $this->Remaining( ) < 4 )
			{
				throw new InvalidPacketException( 'Not enough data to unpack a float.', InvalidPacketException::BUFFER_EMPTY );
			}
			
			$Data = UnPack( 'f', $this->Get( 4 ) );
			
			return (float)$Data[ 1 ];
		}
		
		/**
		 * Get unsigned long from buffer
		 */
		public function GetUnsignedLong( ) : int
		{
			if( $this->Remaining( ) < 4 )
			{
				throw new InvalidPacketException( 'Not enough data to unpack an usigned long.', InvalidPacketException::BUFFER_EMPTY );
			}
			
			$Data = UnPack( 'V', $this->Get( 4 ) );
			
			return (int)$Data[ 1 ];
		}
		
		/**
		 * Read one string from buffer ending with null byte
		 */
		public function GetString( ) : string
		{
			$ZeroBytePosition = StrPos( $this->Buffer, "\0", $this->Position );
			
			if( $ZeroBytePosition === false )
			{
				return '';
			}
			
			$String = $this->Get( $ZeroBytePosition - $this->Position );
			
			$this->Position++;
			
			return $String;
		}
	}

    class InvalidPacketException extends SourceQueryException
	{
		const PACKET_HEADER_MISMATCH = 1;
		const BUFFER_EMPTY = 2;
		const BUFFER_NOT_EMPTY = 3;
		const CHECKSUM_MISMATCH = 4;
	}
