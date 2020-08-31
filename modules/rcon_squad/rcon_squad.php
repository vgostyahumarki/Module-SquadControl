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
	
	$server = new SquadServer(new ServerConnectionInfo('92.63.110.116', 21114, 'c3qlgpyu'));
	$map = $server->currentMap();
	$players = $server->listPlayers();
	
	
	
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


echo '<form method="post">';
echo '<select name="squadServerSelect">';
echo '<option value="">';
print_lang('SELECT_SERVER');
echo '</option>';

$errorMessage = '';

foreach ( $server_homes as $server_home )
{
		echo "<option value='{$server_home['remote_server_id']}'>{$server_home['home_name']}</option>";
		//$server_id = $server_home['remote_server_id'];
		//$echo = print_r ("<br>" . $server_id);
		//$echo = print_r ($server_home['home_name']);
		//$echo = "<br>";
		//$echo = print_r ($server_home['agent_ip']);
		//$echo = "<br>";
		//$echo = print_r ($server_home['control_password']);
		//$echo = "<br>";
}

echo '</select>';
echo "<input type=submit name=selectedSquadServer value='";
print_lang('SELECT');
echo "'>";
echo '</form>';

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
				echo "<p>Выбранный сервер: {$server_home['home_name']}</p>";
				
			}
		}
	}
}


echo $errorMessage;

}
?>