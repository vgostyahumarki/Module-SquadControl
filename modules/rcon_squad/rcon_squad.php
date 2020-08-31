<?php
/*
 *
 * OGP - Open Game Panel
 * Copyright (C) 2008 - 2017 The OGP Development Team
 *
 * http://www.opengamepanel.org/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once("modules/rcon_squad/include/squad_rc.php");
require_once("modules/config_games/server_config_parser.php");
require_once('includes/lib_remote.php');

function exec_ogp_module()
{
	global $db;

	$server_homes = $db->getIpPorts();
	
	if ( !$server_homes )
	{
		return;
	}
?>

<h2>
	<?php print_lang('rcon_command_title'); 

	?>
</h2>

<?php

$reader = function & ($object, $property) {
    $value = & Closure::bind(function & () use ($property) {
        return $this->$property;
    }, $object, $object)->__invoke();

    return $value;
};

echo '<table><thead><tr><form method="post">';
echo '<th><select name="squadServerSelect">';
echo '<option value="">';
print_lang('SELECT_SERVER');
echo '</option>';

$errorMessage = '';

foreach ( $server_homes as $server_home )
{
		echo "<option value='{$server_home['remote_server_id']}'>{$server_home['home_name']}</option>";
}

echo '</select></th>';
echo "<th><input type=submit name=selectedSquadServer value='";
print_lang('SELECT');
echo "'></th>";
echo '</form></tr></thead></table>';

if(isset($_POST['selectedSquadServer']))
{
	$serverID = $_POST['squadServerSelect'];

	if($serverID == "") 
	{
		$errorMessage .= "<p>Вы забыли выбрать сервер!</p>";
	}
	else
	{
		foreach ( $server_homes as $server_home )
		{
			if($serverID == $server_home['remote_server_id'])
			{
				$server = new SquadServer(new ServerConnectionInfo($server_home['agent_ip'], 21114, $server_home['control_password']));
				echo "<p>";
				print_lang('SELECTED_SERVER');
				echo "{$server_home['home_name']}</p>";
				
				$check_map = $server->nextMap();
				if(!$check_map)
				{
					$check_map = 'Неизвестно';
				}
				if($check_map == '/Game/Maps/TransitionMap')
				{
					$check_map = 'Смена карты';
				}
				
				$players = $server->listPlayers();
				$player_count = count($players);
				
				echo "<p>";
				print_lang('PLAYERS');
				echo "{$player_count}";
				print_lang('MAP_NOW');
				echo "{$server->currentMap()}";
				print_lang('NEXT_MAP');
				echo "{$check_map}";
				echo "</p>";
			}
		}
	}
}


echo $errorMessage;

}
?>