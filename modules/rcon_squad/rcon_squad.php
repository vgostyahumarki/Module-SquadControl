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
require_once('modules/rcon_squad/include/SQUAD_RCON.php');


function exec_ogp_module()
{
	global $db;

    echo "<h2>".get_lang("rcon_command_title")."</h2>\n";

	$server_homes = $db->getIpPorts();

	if ( !$server_homes )
	{
		return;
	}

	//$select_game = "<form method=POST >\n<table class=center >\n\n<tr>\n";

	$i = 0;
	$i2 = 0;
	$colspan = "";
	foreach ( $server_homes as $server_home )
	{
		
	}

    $test = new SquadServer(new ServerConnectionInfo('92.63.110.116', 21114, 'c3qlgpyu'));
    echo $test->currentMap();
}
?>