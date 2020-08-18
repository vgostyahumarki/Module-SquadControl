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
	
	
	$select_game = "<form method=POST >\n<table class=center >\n\n<tr>\n";

	$i = 0;
	$i2 = 0;
	$colspan = "";
	foreach ( $server_homes as $server_home )
	{
		$server_xml = read_server_config(SERVER_CONFIG_LOCATION."/".$server_home['home_cfg_file']);
		$remote = new OGPRemoteLibrary($server_home['agent_ip'],$server_home['agent_port'],$server_home['encryption_key'],$server_home['timeout']);
		$screen_running = $remote->is_screen_running(OGP_SCREEN_TYPE_HOME,$server_home['home_id']) === 1;
		if( ( $server_xml->control_protocol == 'rcon' OR $server_xml->control_protocol == 'rcon2' OR 
		   @$server_xml->gameq_query_name == "minecraft" OR $server_xml->control_protocol == 'lcon' ) AND $screen_running )
		{

			$i2++;
			if ( count( $server_homes ) == $i2 )
			{
				$i = 0;
			}
			$control = ( $i == 0 ) ?  "</td>\n" : "</td>\n</tr>\n<tr>\n";
			$display_ip = checkDisplayPublicIP($server_home['display_public_ip'],$server_home['ip'] != $server_home['agent_ip'] ? $server_home['ip'] : $server_home['agent_ip']);
			$select_game .= "<td class=left ><input type=checkbox name='action-". $server_home['ip'] . "-" . $server_home['port'] .
							"' value='". $server_home['home_id'] . "-" . $server_home['mod_id'] . "-" . $server_home['ip'] .
							"-" . $server_home['port'] . "' />" . $server_home['home_name'] . " - " . $display_ip .
							":" . $server_home['port'] . $control;

			$i = ( $control == "</td>\n" ) ? 1 : 0;
		}
	}
	$select_game .= '<input type="button" name="check-all" id="check-all" value="'.get_lang('check-all').'">'.
					'<input type="button" name="uncheck-all" id="uncheck-all" value="'.get_lang('uncheck-all').'">'.
					"</table>\n<table class='center rcon' ><tr><td>".get_lang('rcon_command_title').
					"</td>\n<td>\n<input class=rcon type=text name=rcon_command size=200 style='width:550px;' />\n</td>\n".
					"<td><input type=submit name=remote_send_rcon_command value='".get_lang('send_command')."' />\n</td>\n".
					"</table>\n</form>\n";
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
echo '<option value=""> Выберите сервер... </option>';

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
echo '<input type=submit name=selectedSquadServer value="Выбрать">';
echo '</form>';

if(!isset($_POST['selectedSquadServer'])) 
{
  $errorMessage .= "<h1>You forgot to select your Server!</h1>";
}

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
				$check_map = $server->nextMap();
				if(!$check_map)
				{
					$check_map = 'Неизвестно';
				}
				if($check_map == '/Game/Maps/TransitionMap')
				{
					$check_map = 'Смена карты';
				}
				echo "<p>Текущая карта: {$server->currentMap()}, Следующая карта: {$check_map}</p>";
				$players = $server->listPlayers();
				$player_count = count($players);
				echo "<p>Игроки: {$player_count}</p>";
				$squads = $server->serverPopulation();
				//echo "<br>";
				//var_dump($players);
				//echo "<br>";
				
				
				foreach($squads as $team)
				{
					echo "<details>";
					echo "<summary>Сторона: {$reader($team,'name')} </summary>";
					
					$squads = $reader($team,'squads');
					foreach($squads as $squad)
					{
						
						$locked_squad = $reader($squad,'locked');
						$locked_text = "Открытый";
						if($locked_squad)
						{
							$locked_text = 'Закрытый';
						}
						
						$players_in_squad = $reader($squad,'players');
						
						echo "<details>";
						echo "<summary>Отряд: {$reader($squad,'name')}, Размер: {$reader($squad,'size')}, Статус: {$locked_text}</summary>";
						
						echo "<details>";
						echo "<summary>Игроки</summary>";
						
						echo "<table class='squad_table'>";
						echo "<thead>";
						echo "<tr>";
						echo "<th class='tg-0lax'>ID</th>";
						echo "<th class='tg-0lax'>NAME</th>";
						echo "<th class='tg-0lax'>STEAM_ID</th>";
						echo "</tr>";
						echo "</thead>";
						
						
						foreach($players_in_squad as $player)
						{
							echo "<tbody>";
							echo "<tr>";
							echo "<td class='tg-0lax'>{$reader($player,'id')}</td>";
							echo "<td class='tg-0lax'>{$reader($player,'name')}</td>";
							
							//$steam_url = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=F2CB385DD44216ACDD63AFD84E8DC527&steamids=';
							$steam_id = $reader($player,'steamId');
							//$content = file_get_contents($steam_url . $steam_id);
							
							//$data = (array)json_decode($content)->response->players[0];
							echo "<td class='tg-0lax'>{$steam_id}</td>"; //{$data['profileurl']}</td>";
							echo "</tr>";
							echo "</tbody>";
						}
						
						echo "</table>";
						echo "</details>";
						echo "</details>";
						
					}
					
					echo "</details>";
				}
				
				//var_dump($players);
			}
		}
	}
}

echo $errorMessage;
$server->disconnect();
?>

<?php
	echo $select_game;
	
	if(isset($_POST['remote_send_rcon_command']) AND $_POST['rcon_command'] != "" )
	{
		$rconCommand = $_POST['rcon_command'];
		foreach($_POST as $key => $value)
		{
			$return = "";
			if( preg_match( "/^action/", $key ) )
			{
				list($home_id,$mod_id,$ip,$port) = explode("-", $value);
				$home_info = $db->getGameHome($home_id);
				$remote = new OGPRemoteLibrary($home_info['agent_ip'],$home_info['agent_port'],$home_info['encryption_key'],$home_info['timeout']);
				$server_xml = read_server_config(SERVER_CONFIG_LOCATION."/".$home_info['home_cfg_file']);
				$control_type = isset($server_xml->control_protocol_type) ? $server_xml->control_protocol_type : "";
				
				if ( isset($server_xml->gameq_query_name) and  $server_xml->gameq_query_name == "minecraft" )
				{
					require_once("modules/gamemanager/MinecraftRcon.class.php");
					$rcon_port = $port+10;
					$rcon = new MinecraftRcon;
					if( $rcon->Connect($ip, $rcon_port, $home_info['control_password']) )
					{
						$return = $rcon->Command($rconCommand);
						if ($return);
							echo "<div class='bloc' ><h4>".get_lang('rcon_command_title').": [".$rconCommand."] ".
								  get_lang('has_sent_to')." ". $home_info['home_name']."</h4><xmp style='overflow:scroll;' >$return</xmp></div>";
								  
						$rcon->Disconnect();
						
					}
					else
					{
						echo "".get_lang('need_set_remote_pass')." ".$home_info['home_name']." ".get_lang('before_sending_rcon_com')."<br>";
					}
				}
				else
				{
					$remote_retval = $remote->remote_send_rcon_command( $home_id, $ip, $port, $server_xml->control_protocol, $home_info['control_password'],$control_type,$rconCommand,$return);
					
					if ( $remote_retval === -1 )
					{
						print_failure(get_lang("agent_offline"));
					}
					elseif ( $remote_retval === 1 )
					{
							echo "<div class='bloc' ><h4>".get_lang('rcon_command_title').": [".$rconCommand."] ".
								  get_lang('has_sent_to')." ". $home_info['home_name']."</h4><xmp style='overflow:scroll;' >$return</xmp></div>";
					}
					elseif ( $remote_retval === -10 )
					{
						echo "".get_lang('need_set_remote_pass')." ".$home_info['home_name']." ".get_lang('before_sending_rcon_com')."<br>";
					}
				}
			}
		}
	}
?>
<script type="text/javascript">
$('#check-all').click(function() {
    $('input:checkbox').attr('checked', true).prop('checked', true);
    return false;
});
$('#uncheck-all').click(function() {
    $('input:checkbox').attr('checked', false).prop('checked', false);
    return false;
});
</script>
<?php
}
?>