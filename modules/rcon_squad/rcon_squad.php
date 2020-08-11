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

require_once("modules/config_games/server_config_parser.php");
require_once('includes/lib_remote.php');

require_once('modules/rcon_squad/SquadServer/SquadServer.php');

function exec_ogp_module()
{
	global $db;
	$server_homes = $db->getIpPorts();

	if ( !$server_homes )
	{
		return;
	}

	foreach ( $server_homes as $server_home )
	{
		$server = new SquadServer(new ServerConnectionInfo($server_home['agent_ip'], 21114, $server_home['control_password']));
		$players = $server->serverPopulation();

		echo '<table><tr><td>PREFIX</td><td>Part</td>File</td></tr>';
		foreach ($players as $line)
		{
			$pieces = explode(";", $line);
			$count=count($pieces);

			echo '<tr>';
			for ($counter=0; $counter <$count; $counter++)
			{
				echo '<td>'.$pieces[$counter].'<td>';
			} 
		echo '</tr>';
		}
	}
}
?>
