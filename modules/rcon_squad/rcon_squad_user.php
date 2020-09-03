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
<script src="modules/rcon_squad/include/js/sorttable.js"></script>
<script>
function searchFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchBox");
  filter = input.value.toUpperCase();
  table = document.getElementById("playerList");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
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

				echo '<table><thead><tr >';
				echo '<th><p>';
				print_lang('SELECTED_SERVER');
				echo '</p></th>';
				
				$check_map = $server->nextMap();
				if(!$check_map)
				{
					$check_map = 'Неизвестно';
				}
				if($check_map == '/Game/Maps/TransitionMap')
				{
					$check_map = 'Смена карты';
				}
				
				$current_map = $server->currentMap();
				$players = $server->listPlayers();
				$player_count = count($players);
				echo '<th><p>';
				print_lang('PLAYERS');
				echo '</p></th>';
				echo '<th><p>';
				print_lang('MAP_NOW');
				echo '</p></th>';
				echo '<th><p>';
				print_lang('NEXT_MAP');
				echo '</p></th>';
				echo '</thead></tr>';
				
				echo "<tbody>";
				echo "<tr>";
				echo "<td>{$server_home['home_name']}</td>";
				echo "<td>{$player_count}</td>";
				echo "<td>{$current_map}</td>";
				echo "<td>{$check_map}</td>";
				echo "</tr>";
				echo "</tbody>";
				echo "</table>";
				
				
				echo "<center><p>";
				print_lang('LIST_PLAYERS'); 
				echo "<p></center>";
				echo "<input type='text' id='searchBox' onkeyup='searchFunction()' placeholder='";
				print_lang('SEARCH'); 
				echo "'>";
				echo "<table id= 'playerList' class='sortable'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>NAME</th>";
				echo "<th>STEAM_ID</th>";
				//echo "<th>СТОРОНА</th>";
				//echo "<th>КОММАНДА</th>";
				echo "<th>";
				print_lang('ACTION');
				echo "</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tbody>";
				foreach($players as $player)
				{
					$player_id = $reader($player,'id');
					$player_name = $reader($player,'name');
					$steam_id = $reader($player,'steamId');
					
					echo "<tr>";
					echo "<td><center>{$player_id}</center></td>";
					echo "<td>{$player_name}</td>";
									
					//$steam_url = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=F2CB385DD44216ACDD63AFD84E8DC527&steamids=';
					//$content = file_get_contents($steam_url . $steam_id);
									
					//$data = (array)json_decode($content)->response->players[0];
					echo "<td>{$steam_id}</td>"; //{$data['profileurl']}</td>";
					
					/*
					$team = $reader($player,'team');
					if($team == NULL)
					{
						$team = "НЕТ";
					}
					echo "<td><center>{$team}</center></td>"; 
					$squad = $reader($player,'squad');
					if($squad == NULL)
					{
						$squad = "НЕТ";
					}
					
					echo "<td><center>{$squad}</center></td>";
					*/
					echo "<td>";
					
					echo '<form method="post">';
					
					echo '<select name="squadUserAction">';
					echo '<option value="">';
					print_lang('ACTION_CHOOSE');
					echo '</option>';
					echo "<option value='warn_{$steam_id}'>";
					print_lang('ACTION_WARN');
					echo "</option>";
					echo "<option value='kick_{$steam_id}'>";
					print_lang('ACTION_KICK');
					echo "</option>";
					echo "<option value='ban_{$steam_id}'>";
					print_lang('ACTION_BAN');
					echo "</option>";

					echo '</select>';
					
					echo "<input size='20' type='text' name='reason' value='{$player_name}'>";
					$time_now_for_datetime = gmdate('Y-m-d\TH:i:s');
					echo "<input id='datetime' name='datetime' type='datetime-local' value='{$time_now_for_datetime}' step='1'>";
					
					//echo '<input type=submit name=selectedUserAction value="Выбрать">';
					echo "<input width='50px' type=submit name=selectedUserAction value='";
					print_lang('SELECT');
					echo "'>";

					echo '</form>';			
					echo "</td>";
									
					echo "</tr>";
				
				}
				echo "</tbody>";	
				echo "</table>";
				
				echo "<br>";
				echo "<p>";
				print_lang('LIST_5_MIN_INFO');
				echo "<p>";
				
				echo "<table class='disconnected_players'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>NAME</th>";
				echo "<th>STEAM_ID</th>";
				echo "<th>";
				print_lang('DISCONNECTED_TIME_AGO');
				echo "</th>";
				//echo "<th>ДЕЙСТВИЕ</th>";
				echo "</tr>";
				echo "</thead>";
				
				$get_disconnected_players = $server->listDisconnectedPlayers();
				foreach($get_disconnected_players as $player)
				{
					$player_id = $reader($player,'id');
					$player_name = $reader($player,'name');
					$steam_id = $reader($player,'steamId');
					
					echo "<tbody>";
					echo "<tr>";
					echo "<td>{$player_id}</td>";
					echo "<td>{$player_name}</td>";
					echo "<td>{$steam_id}</td>";
					
					$time_disconnect = gmdate("H:i:s", $reader($player,'disconnectedSince'));
					echo "<td>{$time_disconnect}</td>";
					/*
					echo "<td>";
					echo '<form method="post">';
					echo '<select name="squadUserAction">';
					echo '<option value=""> Выберите Действие... </option>';
					echo "<option value='warn_{$steam_id}'> Предупредить </option>";
					echo "<option value='kick_{$steam_id}'> Кикнуть </option>";
					echo "<option value='ban_{$steam_id}'> Бан </option>";
					echo '</select>';
					echo "<input type='text' name='reason' value='{$player_name}'>";
					$time_now_for_datetime = gmdate('Y-m-d\TH:i:s');
					echo "<input id='datetime' name='datetime' type='datetime-local' value='{$time_now_for_datetime}' step='1'>";
					echo '<input type=submit name=selectedUserAction value="Выбрать">';
					echo '</form>';
					echo "</td>";
					*/
					echo "</tr>";
					echo "</tbody>";
					
				}

				echo "</table>";
			}
		}
	}
}

if(isset($_POST['squadChangeMap']))
{
	$server->endMatch();
}

if(isset($_POST['squadUserAction']))
{
	$action = $_POST['squadUserAction'];
	$reason = $_POST['reason'];
	$datetime =  $_POST['datetime'];
	
	$steamid = explode("_", $action);
	
	$time_now_utc = gmdate('Y-m-d H:i:s');
	$time_selected_utc = strftime('%Y-%m-%d %H:%M:%S', strtotime($datetime));

	$start_date = new DateTime( $time_now_utc );
	$end_date = new DateTime( $time_selected_utc );

	$time_for_server =  $end_date->getTimestamp() - $start_date->getTimestamp();

	
	if($action == "") 
	{
		$errorMessage .= "<p>Вы забыли выбрать Действие!</p>";
	}
	else
	{
		$warn = 'warn';
		$kick = 'kick';
		$ban = 'ban';
		
		if (preg_match("/{$warn}/i", $action))
		{
			$server->adminBroadcast($reason);
		}
		if (preg_match("/{$kick}/i", $action))
		{
			$reason_text_kick = 'Кик: ' . ' ' . $reason;
			$server->kick($steamid[1],$reason_text_kick);
		}
		if (preg_match("/{$ban}/i", $action))
		{
			$reason_text_kick = 'Бан-' . 'Причина: '. $reason . ' До: ' . $time_selected_utc;
			$server->ban($steamid[1],$time_for_server,$reason_text_kick);
		}
		
	}
}

echo $errorMessage;

}
?>
<style>
form {display: inline-flex;}
.form-control {width: auto}
th {cursor: pointer;}
#searchBox {
  background-image: url('modules/rcon_squad/include/img/search.png'); /* Add a search icon to input */
  background-position: 10px center; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 25%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}
</style>