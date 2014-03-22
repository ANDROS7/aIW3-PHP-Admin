<?php
	if ( !isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ] ) {
		mysql_unbuffered_query( "DELETE FROM playerlist WHERE guid = '" . $data[ 1 ] . "' AND server = '" . $server . "'" );
		unset( $players[ $server ][ $data[ 2 ] ] );
		$players[ $server ][ $data[ 2 ] ] = mysql_fetch_array( mysql_query( "SELECT * FROM players WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" ) );
		mysql_unbuffered_query( "INSERT INTO playerlist (server, guid, slotid) VALUES ('" . $server . "', '" . $data[ 1 ] . "', '" . $data[ 2 ] . "')" );
	} //!isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ]
	$data[ 4 ] = trim( $data[ 4 ] );
	$data[ 4 ] = preg_replace( '/\x15/', '', $data[ 4 ] );
	$ersetzen  = array(
		 ";",
		"\"",
		"'",
		"rcon" 
	);
	$data[ 4 ] = str_replace( $ersetzen, "", $data[ 4 ] );
	echo $colors->getColoredString( $server, "black", "light_gray" );
	echo $colors->getColoredString( $data[ 3 ] . " (" . $data[ 2 ] . ") > " . $data[ 4 ], "black", "yellow" );
	mysql_unbuffered_query( "INSERT INTO chat_logs (guid, name, server, team, message) VALUES ('" . $data[ 1 ] . "', '" . mysql_real_escape_string( $data[ 3 ] ) . "', '" . $server . "', 'all', '" . mysql_real_escape_string( $data[ 4 ] ) . "')" );
	if ( ( str_contains( strtolower( $data[ 4 ] ), "hack" ) || str_contains( strtolower( $data[ 4 ] ), "cheat" ) || str_contains( strtolower( $data[ 4 ] ), "aimbot" ) ) && $players[ $server ][ $data[ 2 ] ][ "rank" ] <= 2 ) {
		rcon_command( "tell " . $data[ 2 ] . " ^4If you think he is a hacker, votekick him. ^0(^4!vk <Name> <Reason>^0)" );
		rcon_command( "tell " . $data[ 2 ] . " ^1If you are sure, call an Admin with !gm" );
	} //( str_contains( strtolower( $data[ 4 ] ), "hack" ) || str_contains( strtolower( $data[ 4 ] ), "cheat" ) || str_contains( strtolower( $data[ 4 ] ), "aimbot" ) ) && $players[ $server ][ $data[ 2 ] ][ "rank" ] <= 2
	if ( strtolower( $data[ 4 ] ) == "hello" || strtolower( $data[ 4 ] ) == "hi" || strtolower( $data[ 4 ] ) == "hey" ) {
		switch ( mt_rand( 0, 2 ) ) {
			case 0:
				rcon_command( "say Hi there, ^4" . $data[ 3 ] );
				break;
			case 1:
				rcon_command( "say Hey there, ^4" . $data[ 3 ] );
				break;
			case 2:
				rcon_command( "say Sup ^4" . $data[ 3 ] );
				break;
		} //mt_rand( 0, 2 )
	} //strtolower( $data[ 4 ] ) == "hello" || strtolower( $data[ 4 ] ) == "hi" || strtolower( $data[ 4 ] ) == "hey"
    if(strstr($data[4], "let me Introduce death.") || strstr($data[4], "got FUCKED UP from Distance :")) {
        rcon_command("clientkick ".$data[2]." \"^4Kicked by Anti-Cheat\"");
        rcon_command("say \"^4".$data[3]." got kicked for hacking\"");
    }
	if ( $data[ 4 ][ 0 ] == "!" ) {
		$command = substr( $data[ 4 ], 1 );
		$command = explode( " ", $command );
		$command[ 0 ] == strtolower( $command[ 0 ] );
		switch ( $command[ 0 ] ) {
			case "help":
			case "cmdlist":
			case "commands": {
				$commands = array(
					 "help" => "0,View available commands",
					"commands" => "0,View available commands",
					"cmdlist" => "0,View available commands",
					"kick" => "2,Kicks a Player out of the Server - Syntax: <SlotID/PartOfName> <Reason>",
					"ban" => "2,Bans a Player globally out of the Servers - Syntax: <SlotID/PartOfName> <Reason>",
					"fps" => "0,Turns on/off FPS-Boost",
					"list" => "0,Shows a List of Players with their SlotID",
					"players" => "0,Shows a List of Players with their SlotID",
					"stats" => "0,Shows Statistics by you on this Server",
					"freeze" => "2,Freezes a Player - Syntax: <SlotID/PartOfName>",
					"lookup" => "2,Searches a Player in the Database - Syntax: <Part of Name / GUID>",
					"l" => "2,Searches a Player in the Database - Syntax: <Part of Name / GUID>",
					"ooc" => "0,Sends a global message across all x4-Servers - Syntax: <Message>",
					"rlist" => "0,Gets playerlist from Server. eg: !rlist terminal - Syntax: <Server>",
					"time" => "0,Shows the current date",
					"math" => "0,Do math. - eg.: !math 5*9",
					"vk" => "0,Votekicks a Player - Syntax: !vk <SlotID/PartOfName> <Reason>",
					"veto" => "2,Cancels an votekick",
					"restart" => "2,Restarts the bot (fix delay)",
					"rkick" => "2,Remotekicks somebody - Syntax: !rk <Server> <SlotID/PartOfName> <Reason>",
					"warn" => "2,Warns a User - Syntax: !warn <SlotID/PartOfName> <Reason>",
					"gm" => "0,Sends a message to all admin's phones. Misuse will be banned - Syntax: !gm <Message>",
					"pm" => "0,Sends a private message to the User - Syntax: !pm <SlotID/PartOfName>",
					"riot" => "2,Riots a User - Syntax: !riot <SlotID/PartOfName>",
					"votegametype" => "0,Votes a Gametype Change - Syntax: !votegt <Gametype>",
					"votemap" => "0,Votes a Map change - Syntax: !votemap <Map name>",
					"maplist" => "0,Shows all available maps",
					"gtlist" => "0,Shows all available gametypes" 
				);
				if ( isset( $command[ 1 ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " __^4" . $command[ 1 ] . "^0__" );
					$desc = explode( ",", $commands[ $command[ 1 ] ] );
					rcon_command( "tell " . $data[ 2 ] . " ^4" . $desc[ 1 ] );
					unset( $desc );
				} //isset( $command[ 1 ] )
				else {
					$x = 0;
					foreach ( $commands as $cmd => $txt ) {
						$level = explode( ",", $txt );
						$desc  = explode( " - ", $level[ 1 ] );
						$level = $txt[ 0 ];
						@$syntax = $desc[ 1 ];
						$desc = $desc[ 0 ];
						if ( (int)$players[ $server ][ $data[ 2 ] ][ "rank" ] >= $level ) {
							$cstring .= "!" . $cmd . " ";
							$x++;
						} //$players[ $server ][ $data[ 2 ] ][ "rank" ] >= $level
						if ( $x == 15 ) {
							rcon_command( "tell " . $data[ 2 ] . " " . $cstring );
							$cstring = "";
							$x       = 0;
						} //$x == 15
					} //$commands as $cmd => $txt
					rcon_command( "tell " . $data[ 2 ] . " " . $cstring );
					rcon_command( "tell " . $data[ 2 ] . " For more help, type ^4!help <command>" );
				}
				break;
			}
			case "map_restart": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] == "1337" ) {
					rcon_command( "map_restart" );
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] == "1337"
			}
			case "restart": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					rcon_command( "tell " . $data[ 2 ] . " ^4Restarting..." );
					mysql_unbuffered_query( "DELETE FROM playerlist" );
                    mysql_unbuffered_query( "DELETE FROM positions" );
					exit( "RESTARTING!" );
					die( "RESTARTING!" );
					rcon_command( "tell " . $data[ 2 ] . " ^1Restart failed" );
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				break;
			}
			case "saveplayers": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] == 1337 ) {
					$fetchmysql = ( time() - 900 );
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] == 1337
				break;
			}
			case "ooc": {
				if ( empty( $command[ 1 ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " \"^4Syntax: !ooc <Message>\"" );
					break;
				} //empty( $command[ 1 ] )
				$message = "";
				foreach ( $command as $k => $v ) {
					if ( $k >= 1 ) {
						$message .= $v . " ";
					} //$k >= 1
				} //$command as $k => $v
				foreach ( $players as $serverr => $f ) {
					if ( count( $players[ $serverr ] ) > 0 ) {
						rcon_command( "sv_sayName \"^0[^3!OOC^0 | ^3" . strtoupper( $server ) . "^0] ^7" . $data[ 3 ] . "\"", server( $serverr ) );
						rcon_command( "say " . $message, server( $serverr ) );
						rcon_command( "sv_sayName \"^7[^4x4^7] ^0Boss\"", server( $serverr ) );
					} //count( $players[ $serverr ] ) > 0
				} //$players as $serverr => $f
				unset( $serverr, $message, $f );
				break;
			}
			case "rkick":
			case "kick": {
				if ( $command[ 0 ] == "rkick" && server( strtolower( $command[ 1 ] ) ) != NULL ) {
					$srvv         = strtolower( $command[ 1 ] );
					$command[ 1 ] = $command[ 2 ];
					$command[ 2 ] = $command[ 3 ];
					foreach ( $command as $k => $v ) {
						if ( $k >= 3 ) {
							$message .= $v . " ";
						} //$k >= 3
					} //$command as $k => $v
				} //$command[ 0 ] == "rkick" && server( strtolower( $command[ 1 ] ) ) != NULL
				else {
					$srvv = $server;
					foreach ( $command as $k => $v ) {
						if ( $k >= 2 ) {
							$message .= $v . " ";
						} //$k >= 2
					} //$command as $k => $v
				}
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					if ( !is_integer2( $command[ 1 ] ) ) {
						foreach ( $players[ $srvv ] as $slot => $pl ) {
							if ( str_contains( $pl[ "name" ], $command[ 1 ], true ) ) {
								$command[ 1 ] = $slot;
								break;
							} //str_contains( $pl[ "name" ], $command[ 1 ], true )
						} //$players[ $srvv ] as $slot => $pl
					} //!is_integer2( $command[ 1 ] )
					if ( isset( $players[ $srvv ][ $command[ 1 ] ] ) ) {
						if ( !isset( $command[ 2 ] ) || empty( $command[ 2 ] ) ) {
							rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !kick <SlotID/PartOfName> <Reason>" );
							break;
						} //!isset( $command[ 2 ] ) || empty( $command[ 2 ] )
						else {
							rcon_command( "say " . $players[ $srvv ][ $command[ 1 ] ][ "name" ] . " was kicked. Reason: " . $message, server( $srvv ) );
							rcon_command( "clientkick " . $command[ 1 ] . " \"Kicked by " . $data[ 3 ] . ". Reason: " . $message . "\"", server( $srvv ) );
							unset( $message, $srvv );
						}
					} //isset( $players[ $srvv ][ $command[ 1 ] ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1Player not found" );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access, " . $players[ $server ][ $data[ 2 ] ][ "name" ] . ". (You are Level " . (int) $players[ $server ][ $data[ 2 ] ][ "rank" ] . ")" );
				}
				break;
			}
			case "veto": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] >= 2 ) {
					if ( isset( $votekick[ $server ] ) || isset( $votetype[ $server ] ) ) {
						if ( isset( $votekick[ $server ] ) ) {
							$votekick[ $server ][ "initiated" ] = ( time() - 120 );
						} //isset( $votekick[ $server ] )
						if ( isset( $votetype[ $server ] ) ) {
							$votetype[ $server ][ "initiated" ] = ( time() - 120 );
						} //isset( $votetype[ $server ] )
					} //isset( $votekick[ $server ] ) || isset( $votetype[ $server ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " No running Votekick" );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] >= 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No access" );
				}
				break;
			}
			case "gtlist":
			case "gametypelist": {
				if ( !is_array( $gametypes ) ) {
					$gametypes = explode( "\n", $gametypes );
				} //!is_array( $gametypes )
				foreach ( $gametypes as $gametype ) {
					$tmp = explode( "-", $gametype );
					$gametypess .= $tmp[ 1 ] . " (^0" . $tmp[ 0 ] . "^4) ";
					if ( strlen( $gametypess ) > 100 ) {
						rcon_command( "tell " . $data[ 2 ] . " ^4" . $gametypess, NULL, 1000000 );
						unset( $gametypess );
					} //strlen( $gametypess ) > 100
					unset( $tmp );
				} //$gametypes as $gametype
				rcon_command( "tell " . $data[ 2 ] . " ^4" . $gametypess );
				rcon_command( "tell " . $data[ 2 ] . " ^4To vote, type !votegt <Full name/Mode name>" );
				unset( $gametypess, $tmp );
				break;
			}
			case "maplist": {
				if ( !is_array( $maps ) ) {
					$maps = explode( "\n", $maps );
				} //!is_array( $maps )
				foreach ( $maps as $map ) {
					$tmp = explode( "-", $map );
					$mapss .= $tmp[ 1 ] . " (^0" . $tmp[ 0 ] . "^4) ";
					if ( strlen( $mapss ) > 100 ) {
						rcon_command( "tell " . $data[ 2 ] . " ^4" . $mapss, NULL, 1000000 );
						unset( $mapss );
					} //strlen( $mapss ) > 100
					unset( $tmp );
				} //$maps as $map
				rcon_command( "tell " . $data[ 2 ] . " ^4" . $mapss );
				rcon_command( "tell " . $data[ 2 ] . " ^4To vote, type !votemap <Full name/Map name>" );
				unset( $mapss, $tmp );
				break;
			}
			case "vtype": {
				if ( !isset( $votetype[ $server ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " ^1No vote running" );
					break;
				} //!isset( $votetype[ $server ] )
				if ( is_array( $votetype[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votetype[ $server ][ "users" ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " ^1You already voted." );
					break;
				} //is_array( $votetype[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votetype[ $server ][ "users" ] )
				if ( $command[ 1 ] == "yes" ) {
					$votetype[ $server ][ "voted_yes" ]++;
					rcon_command( "tell " . $data[ 2 ] . " ^4Voted yes." );
					$votetype[ $server ][ "users" ][ ] = $data[ 1 ];
				} //$command[ 1 ] == "yes"
				elseif ( $command[ 1 ] == "no" ) {
					$votetype[ $server ][ "voted_no" ]++;
					rcon_command( "tell " . $data[ 2 ] . " ^4Voted no." );
					$votetype[ $server ][ "users" ][ ] = $data[ 1 ];
				} //$command[ 1 ] == "no"
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !vtype <yes/no>" );
				}
				break;
			}
            case "no":
			case "yes": {
                if(isset($votetype[$server])) {
                    if ( is_array( $votetype[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votetype[ $server ][ "users" ] ) ) {
                        rcon_command( "tell " . $data[ 2 ] . " ^1You already voted." );
                        break;
                    } //is_array( $votetype[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votetype[ $server ][ "users" ] )

                    $votetype[ $server ][ "voted_".$command[0] ]++;
                    rcon_command( "tell " . $data[ 2 ] . " ^4Voted ".$command[0]."." );
                    $votetype[ $server ][ "users" ][ ] = $data[ 1 ];
                    
				} elseif(isset($votekick[$server])) {
                    if ( is_array( $votekick[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votekick[ $server ][ "users" ] ) ) {
                        rcon_command( "tell " . $data[ 2 ] . " ^1You already voted." );
                        break;
                    } //is_array( $votetype[ $server ][ "users" ] ) && in_array( $data[ 1 ], $votetype[ $server ][ "users" ] )
                    $votekick[ $server ][ "voted_".$command[0] ]++;
                    rcon_command( "tell " . $data[ 2 ] . " ^4Voted ".$command[0]."." );
                    $votekick[ $server ][ "users" ][ ] = $data[ 1 ];
                } else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No vote running" );
					break;
                }
				break;
			}
			case "votegt":
			case "votegametype":
			case "votemap": {
				if ( isset( $votekick[ $server ][ "initiated" ] ) || isset( $votetype[ $server ][ "initiated" ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " ^1Theres already a vote ongoing." );
					break;
				} //isset( $votekick[ $server ][ "initiated" ] ) || isset( $votetype[ $server ][ "initiated" ] )
				if ( str_contains( $command[ 0 ], "gametype" ) || str_contains( $command[ 0 ], "gt" ) ) {
					if ( isset( $command[ 1 ] ) ) {
						if ( !is_array( $gametypes ) ) {
							$gametypes = explode( "\n", $maps );
						} //!is_array( $gametypes )
						foreach ( $gametypes as $gametype ) {
							$gtdata = explode( "-", $gametype );
							if ( str_contains( strtolower( $gtdata[ 1 ] ), strtolower( $command[ 1 ] ) ) || strtolower( $gtdata[ 0 ] ) == strtolower( $command[ 1 ] ) ) {
								$votetype[ $server ][ "initiated" ] = time();
								$votetype[ $server ][ "gametype" ]  = array(
									 $gtdata[ 0 ],
									$gtdata[ 1 ] 
								);
								$votetype[ $server ][ "voted_yes" ] = 1;
								$votetype[ $server ][ "voted_no" ]  = 0;
								$votetype[ $server ][ "min" ]       = ( count( $players[ $server ] ) / 2 );
								$votetype[ $server ][ "type" ]      = "gametype";
								rcon_command( "say ^1!!! GAMETYPEVOTE !!!" );
								rcon_command( "say ^4Gametype: " . $gtdata[ 1 ] . " (" . $gtdata[ 0 ] . ")" );
								rcon_command( "say ^4Type ^1!yes^4 or ^1!no" );
								break;
							} //str_contains( strtolower( $gtdata[ 1 ] ), strtolower( $command[ 1 ] ) ) || strtolower( $gtdata[ 0 ] ) == strtolower( $command[ 1 ] )
						} //$gametypes as $gametype
						if ( !$votetype[ $server ] )
							rcon_command( "tell " . $data[ 2 ] . " ^1Gametype not found. For a list of valid maps, type !gtlist" );
					} //isset( $command[ 1 ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !votegt <Gametype>" );
						rcon_command( "tell " . $data[ 2 ] . " ^4For a list of valid gametypes, type !gtlist" );
					}
				} //str_contains( $command[ 0 ], "gametype" ) || str_contains( $command[ 0 ], "gt" )
				elseif ( str_contains( $command[ 0 ], "map" ) ) {
					if ( isset( $command[ 1 ] ) ) {
						if ( !is_array( $maps ) ) {
							$maps = explode( "\n", $maps );
						} //!is_array( $maps )
						foreach ( $maps as $map ) {
							$mapdata = explode( "-", $map );
							if ( str_contains( strtolower( $mapdata[ 1 ] ), strtolower( $command[ 1 ] ) ) || strtolower( $mapdata[ 0 ] ) == strtolower( $command[ 1 ] ) ) {
								$votetype[ $server ][ "initiated" ] = time();
								$votetype[ $server ][ "map" ]       = $mapdata[ 0 ] . " - " . $mapdata[ 1 ];
								$votetype[ $server ][ "mapname" ]   = array(
									 $mapdata[ 0 ],
									$mapdata[ 1 ] 
								);
								$votetype[ $server ][ "voted_yes" ] = 1;
								$votetype[ $server ][ "voted_no" ]  = 0;
								$votetype[ $server ][ "min" ]       = ( count( $players[ $server ] ) / 2 );
								$votetype[ $server ][ "type" ]      = "map";
								rcon_command( "say ^1!!! MAPVOTE !!!" );
								rcon_command( "say ^4Map: " . $mapdata[ 1 ] . " (" . $mapdata[ 0 ] . ")" );
								rcon_command( "say ^4Type ^1!yes^4 or ^1!no" );
								break;
							} //str_contains( strtolower( $mapdata[ 1 ] ), strtolower( $command[ 1 ] ) ) || strtolower( $mapdata[ 0 ] ) == strtolower( $command[ 1 ] )
						} //$maps as $map
						if ( !$votetype[ $server ] )
							rcon_command( "tell " . $data[ 2 ] . " ^1Map not found. For a list of valid maps, type !maplist" );
					} //isset( $command[ 1 ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !votemap <Map>" );
						rcon_command( "tell " . $data[ 2 ] . " ^4For a list of valid maps, type !maplist" );
					}
				} //str_contains( $command[ 0 ], "map" )
				break;
			}
			case "vk":
			case "votekick":
			case "vote": {
				if ( $command[ 1 ] == "yes" || $command[ 1 ] == "no" && isset( $votekick[ $server ][ "initiated" ] ) && !isset( $votetype[ $server ] ) ) {
					if ( !in_array( $data[ 1 ], $votekick[ $server ][ "users" ] ) ) {
						$votekick[ $server ][ "voted_" . $command[ 1 ] ]++;
						$votekick[ $server ][ "users" ][ ] = $data[ 1 ];
					} //!in_array( $data[ 1 ], $votekick[ $server ][ "users" ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1You already voted!" );
					}
				} //$command[ 1 ] == "yes" || $command[ 1 ] == "no" && isset( $votekick[ $server ][ "initiated" ] ) && !isset( $votetype[ $server ] )
				else {
					if ( isset( $command[ 2 ] ) && isset( $command[ 2 ] ) && !empty( $command[ 2 ] ) ) {
						if ( !is_integer2( $command[ 1 ] ) ) {
							echo PHP_EOL;
							foreach ( $players[ $server ] as $slot => $pl ) {
								if ( in_string( strtolower( $command[ 1 ] ), strtolower( $pl[ "name" ] ), true ) ) {
									echo "Player found: " . $pl[ "name" ] . " - " . $slot . PHP_EOL;
									$command[ 1 ] = $slot;
									break;
								} //in_string( strtolower( $command[ 1 ] ), strtolower( $pl[ "name" ] ), true )
								else {
									echo "Player " . $pl[ "name" ] . " does not match" . PHP_EOL;
								}
							} //$players[ $server ] as $slot => $pl
							if ( !isset( $command[ 1 ] ) ) {
								$command[ 1 ] = 999;
							} //!isset( $command[ 1 ] )
						} //!is_integer2( $command[ 1 ] )
						if ( isset( $players[ $server ][ $command[ 1 ] ] ) ) {
							if ( isset( $votekick[ $server ] ) || isset( $votetype[ $server ] ) ) {
								rcon_command( "tell " . $data[ 2 ] . " \"^1There's already a votekick. Try again in a minute\"" );
							} //isset( $votekick[ $server ] ) || isset( $votetype[ $server ] )
							else {
								foreach ( $command as $k => $v ) {
									if ( $k >= 2 ) {
										$message .= $v;
									} //$k >= 2
								} //$command as $k => $v
								$votekick[ $server ][ "initiated" ]      = time();
								$votekick[ $server ][ "user" ][ "id" ]   = $command[ 1 ];
								$votekick[ $server ][ "user" ][ "name" ] = $players[ $server ][ $command[ 1 ] ][ "name" ];
								$votekick[ $server ][ "reason" ]         = $message;
								$votekick[ $server ][ "voted_no" ]       = 0;
								$votekick[ $server ][ "voted_yes" ]      = 1;
								$votekick[ $server ][ "users" ][ ]       = $data[ 1 ];
								$votekick[ $server ][ "min" ]            = ( count( $players[ $server ] ) / 2 );
								rcon_command( "say \"^1!!!VOTEKICK!!!\"" );
								rcon_command( "say \"Kick Player " . $votekick[ $server ][ "user" ][ "name" ] . " for " . $votekick[ $server ][ "reason" ] . "\"" );
								rcon_command( "say \"^4Type ^1!yes^4 or ^1!no\"" );
							}
						} //isset( $players[ $server ][ $command[ 1 ] ] )
						else {
							rcon_command( "tell " . $data[ 2 ] . " ^1Player not found." );
						}
					} //isset( $command[ 2 ] ) && isset( $command[ 2 ] ) && !empty( $command[ 2 ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !vk <SlotID/PartOfName> <Reason>" );
					}
				}
				unset( $message, $k, $v );
				break;
			}
			case "ban": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					if ( !is_integer2( $command[ 1 ] ) ) {
						foreach ( $players[ $server ] as $slot => $pl ) {
							if ( str_contains( $pl[ "name" ], $command[ 1 ], true ) ) {
								$command[ 1 ] = $slot;
								break;
							} //str_contains( $pl[ "name" ], $command[ 1 ], true )
						} //$players[ $server ] as $slot => $pl
					} //!is_integer2( $command[ 1 ] )
					if ( isset( $players[ $server ][ $command[ 1 ] ] ) || $command[ 1 ][ 0 ] == "@" ) {
						if ( !isset( $command[ 2 ] ) || empty( $command[ 2 ] ) ) {
							rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !ban <SlotID/PartOfName/@LookupID> <Reason>" );
						} //!isset( $command[ 2 ] ) || empty( $command[ 2 ] )
						else {
							foreach ( $command as $k => $v ) {
								if ( $k >= 2 ) {
									$message .= $v . " ";
								} //$k >= 2
							} //$command as $k => $v
							if ( $command[ 1 ][ 0 ] == "@" ) {
								$command[ 1 ] = str_replace( "@", "", $command[ 1 ] );
								$getdata      = mysql_query( "SELECT name FROM players WHERE id = '" . $command[ 1 ] . "'" );
								if ( mysql_num_rows( $getdata ) == 0 ) {
									unset( $getdata );
									rcon_command( "tell " . $data[ 2 ] . " ^1User not found" );
								} //mysql_num_rows( $getdata ) == 0
								else {
									$q = mysql_query( "UPDATE players SET banned = TRUE WHERE id = '" . $command[ 1 ] . "'" );
									if ( $q ) {
										$q = mysql_fetch_array( $getdata );
										foreach ( $players as $serverr => $d ) {
											rcon_command( "say " . $q[ "name" ] . " was banned. Reason: " . $message, server( $serverr ) );
										} //$players as $serverr => $d
										rcon_command( "tell " . $data[ 2 ] . " ^2Banned for " . $message );
									} //$q
									else {
										rcon_command( "tell " . $data[ 2 ] . " ^1Ban failed: " . mysql_error() );
									}
								}
								unset( $getdata, $q, $serverr, $d, $message );
							} //$command[ 1 ][ 0 ] == "@"
							else {
								foreach ( $players as $serverr => $d ) {
									rcon_command( "say " . $players[ $server ][ $command[ 1 ] ][ "name" ] . " was banned. Reason: " . $message, server( $serverr ) );
								} //$players as $serverr => $d
								mysql_unbuffered_query( "UPDATE players SET banned = TRUE WHERE guid = '" . $players[ $server ][ $command[ 1 ] ][ "guid" ] . "'" );
								mysql_unbuffered_query( "INSERT INTO bans (guid, name, reason, banned_by) VALUES ('" . $players[ $server ][ $command[ 1 ] ][ "guid" ] . "', '" . $players[ $server ][ $command[ 1 ] ][ "name" ] . "', '" . mysql_real_escape_string( $message ) . "', '" . $data[ 3 ] . "')" );
								rcon_command( "clientkick " . $command[ 1 ] . " \"Banned by " . $data[ 3 ] . ". Reason: " . $message . "\"" );
								push( "Userban", $data[ 3 ] . " (" . $server . ") banned " . $players[ $server ][ $command[ 1 ] ][ "name" ] . ". Reason: " . $message );
								unset( $message, $serverr, $d );
							}
						}
					} //isset( $players[ $server ][ $command[ 1 ] ] ) || $command[ 1 ][ 0 ] == "@"
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1Player not found" );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "rcon": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] >= "1337" ) {
					foreach ( $command as $k => $v ) {
						if ( $k >= 1 ) {
							$message .= $v . " ";
						} //$k >= 1
					} //$command as $k => $v
					rcon_command( "tell " . $data[ 2 ] . " \"^4Executed: " . $message . "\"" );
					$r = rcon_command( $message );
					rcon_command( "tell " . $data[ 2 ] . " \"^4Reply: " . $r . "\"" );
					unset( $message, $k, $v, $m );
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] >= "1337"
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No access." );
				}
				break;
			}
			case "reset_ks": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] >= "1337" ) {
					$killstreaks[ $server ] = array(
						 "ks" => 0,
						"guid" => 0 
					);
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] >= "1337"
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "warn": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] >= "2" ) {
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] >= "2"
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "gm": {
				if ( !isset( $command[ 1 ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " ^1Syntax: !gm <Message>" );
					rcon_command( "tell " . $data[ 2 ] . " ^1Enter as many information as you can (Name, Type of Hack, etc)" );
					rcon_command( "tell " . $data[ 2 ] . " ^1Please remember: A misuse will result in a ban!" );
					break;
				} //!isset( $command[ 1 ] )
				foreach ( $command as $k => $v ) {
					if ( $k >= 1 ) {
						$message .= $v . " ";
					} //$k >= 1
				} //$command as $k => $v
				push( "GAMEMASTER", $data[ 3 ] . " (" . $server . " | " . $data[ 1 ] . "): " . $message, 2 );
				rcon_command( "tell " . $data[ 2 ] . " ^2Message sent to all Phones." );
				unset( $message );
				break;
			}
			case "lookup":
			case "l": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					if ( !isset( $command[ 1 ] ) ) {
						rcon_command( "tell " . $data[ 2 ] . " ^1Syntax: !l <GUID/Part of Name>" );
					} //!isset( $command[ 1 ] )
					else {
						$users = mysql_query( "SELECT * FROM `players` WHERE `name` LIKE '%" . mysql_real_escape_string( $command[ 1 ] ) . "%'" );
						rcon_command( "tell " . $data[ 2 ] . " ^4Found ^0" . mysql_num_rows( $users ) . " ^4Players" );
						while ( $r = mysql_fetch_array( $users ) ) {
							rcon_command( "tell " . $data[ 2 ] . " \"^4" . $r[ "name" ] . " (" . $r[ "id" ] . ") - " . sprintf( "%02d%s%02d%s%02d", floor( $r[ "playtime_sec" ] / 3600 ), ":", ( $r[ "playtime_sec" ] / 60 ) % 60, ":", $r[ "playtime_sec" ] % 60 ) . " - " . ( $r[ "banned" ] ? "^1banned^4" : "^2not banned^4" ) . "\"" );
						} //$r = mysql_fetch_array( $users )
						unset( $users, $r );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "players":
			case "list":
			case "rlist":
			case "rplayers": {
				$x         = 0;
				$tmpstring = "";
				if ( $command[ 0 ] == "rlist" || $command[ 0 ] == "rplayers" && server( strtolower( $command[ 1 ] ) ) != NULL ) {
					$srvv = strtolower( $command[ 1 ] );
				} //$command[ 0 ] == "rlist" || $command[ 0 ] == "rplayers" && server( strtolower( $command[ 1 ] ) ) != NULL
				else {
					$srvv = $server;
				}
				foreach ( $players[ $srvv ] as $id => $player ) {
					if ( $x == 5 ) {
						rcon_command( "tell " . $data[ 2 ] . " " . $tmpstring, NULL, 1000000 );
						$tmpstring = "";
						$x         = 0;
					} //$x == 5
					$tmpstring .= $player[ "name" ] . " (^4#" . $id . "^0) ";
					$x++;
				} //$players[ $srvv ] as $id => $player
				rcon_command( "tell " . $data[ 2 ] . " " . $tmpstring );
				unset( $tmpstring, $x, $player, $id, $srvv );
				break;
			}
			case "freeze": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					if ( !is_integer2( $command[ 1 ] ) ) {
						foreach ( $players[ $server ] as $slot => $pl ) {
							if ( str_contains( $pl[ "name" ], $command[ 1 ], true ) ) {
								$command[ 1 ] = $slot;
							} //str_contains( $pl[ "name" ], $command[ 1 ], true )
						} //$players[ $server ] as $slot => $pl
					} //!is_integer2( $command[ 1 ] )
					if ( empty( $command[ 1 ] ) ) {
						rcon_command( "tell " . $data[ 2 ] . " \"^4Syntax: !freeze <SlotID/PartOfName>\"" );
						break;
					} //empty( $command[ 1 ] )
					if ( isset( $players[ $server ][ $command[ 1 ] ] ) ) {
						rcon_command( "freeze " . $command[ 1 ] );
					} //isset( $players[ $server ][ $command[ 1 ] ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1Player not found." );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "riot": {
				if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
					if ( !is_integer2( $command[ 1 ] ) ) {
						foreach ( $players[ $server ] as $slot => $pl ) {
							if ( str_contains( $pl[ "name" ], $command[ 1 ], true ) ) {
								$command[ 1 ] = $slot;
								break;
							} //str_contains( $pl[ "name" ], $command[ 1 ], true )
						} //$players[ $server ] as $slot => $pl
					} //!is_integer2( $command[ 1 ] )
					if ( empty( $command[ 1 ] ) ) {
						rcon_command( "tell " . $data[ 2 ] . " \"^4Syntax: !riot <SlotID/PartOfName>\"" );
						break;
					} //empty( $command[ 1 ] )
					if ( isset( $players[ $server ][ $command[ 1 ] ] ) ) {
						rcon_command( "riot " . $command[ 1 ] );
					} //isset( $players[ $server ][ $command[ 1 ] ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1Player not found." );
					}
				} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1No Access." );
				}
				break;
			}
			case "stats": {
				if ( isset( $command[ 1 ] ) && !is_integer2( $command[ 1 ] ) ) {
					$q         = mysql_fetch_array( mysql_query( "SELECT guid, name FROM players WHERE name LIKE '%" . mysql_real_escape_string( $command[ 1 ] ) . "%' LIMIT 1" ) );
					$data[ 1 ] = $q[ "guid" ];
					$name      = $q[ "name" ];
					$q         = mysql_query( "SELECT * FROM player_stats WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" );
					$q2        = mysql_query( "SELECT * FROM weapon_player_stats WHERE guid = '" . $data[ 1 ] . "' ORDER BY kills DESC LIMIT 1" );
				} //isset( $command[ 1 ] ) && !is_integer2( $command[ 1 ] )
				else {
					$name = $data[ 3 ];
					$q    = mysql_query( "SELECT * FROM player_stats WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" );
					$q2   = mysql_query( "SELECT * FROM weapon_player_stats WHERE guid = '" . $data[ 1 ] . "' ORDER BY kills DESC LIMIT 1" );
				}
				if ( $q && mysql_num_rows( $q ) == 1 && $q2 && mysql_num_Rows( $q2 ) == 1 ) {
					$data_this  = mysql_fetch_array( $q );
					$data_this2 = mysql_fetch_array( $q2 );
					rcon_command( "tell " . $data[ 2 ] . " ^0Stats - " . $name . "__", NULL, 100000 );
					rcon_command( "tell " . $data[ 2 ] . " ^0Kills: ^4" . $data_this[ "kills" ]." ^0| Deaths: ^4".$data_this["deaths"], NULL, 100000 );
					rcon_command( "tell " . $data[ 2 ] . " ^0KDR: ^4" . round( ( $data_this[ "kills" ] / $data_this[ "deaths" ] ), 2 ), NULL, 100000 );
					rcon_command( "tell " . $data[ 2 ] . " ^0Weapon: ^4" . strtoupper( $data_this2[ "weapon" ] ) . " ^0(^4" . $data_this2[ "bullets" ] . "^0 Bullets hit) - Damage done: ^4" . $data_this2[ "damage" ] . "HP^0 - Kills: ^4" . $data_this2[ "kills" ], NULL, 100000 );
				} //$q && mysql_num_rows( $q ) == 1 && $q2 && mysql_num_Rows( $q2 ) == 1
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^1Nothing found" );
				}
				unset( $data_this, $q, $q2, $name );
				break;
			}
			case "pm": {
				if ( isset( $command[ 1 ] ) ) {
					if ( empty( $command[ 2 ] ) || empty( $command[ 1 ] ) ) {
						rcon_command( "tell " . $data[ 2 ] . " \"^4Syntax: !pm <SlotID/PartOfName> <Message>\"" );
						break;
					} //empty( $command[ 2 ] ) || empty( $command[ 1 ] )
					if ( !is_integer2( $command[ 1 ] ) ) {
						foreach ( $players[ $server ] as $slot => $pl ) {
							if ( str_contains( $pl[ "name" ], $command[ 1 ], true ) ) {
								$command[ 1 ] = $slot;
								break;
							} //str_contains( $pl[ "name" ], $command[ 1 ], true )
						} //$players[ $server ] as $slot => $pl
					} //!is_integer2( $command[ 1 ] )
					if ( isset( $players[ $server ][ $command[ 1 ] ] ) ) {
						foreach ( $command as $k => $v ) {
							if ( $k >= 2 ) {
								$message .= $v . " ";
							} //$k >= 2
						} //$command as $k => $v
						$r = rcon_command( "tell " . $command[ 1 ] . " \"^3PM^4 from " . $data[ 3 ] . " > ^7" . $message . "\"" );
						var_dump( $message );
						var_dump( $command );
						var_dump( $r );
						unset( $message, $k, $v );
						rcon_command( "tell " . $data[ 2 ] . " \"^2Message sent to " . $players[ $server ][ $command[ 1 ] ][ "name" ] . "\"" );
					} //isset( $players[ $server ][ $command[ 1 ] ] )
					else {
						rcon_command( "tell " . $data[ 2 ] . " ^1Player not found." );
					}
				} //isset( $command[ 1 ] )
				else {
					rcon_command( "tell " . $data[ 2 ] . " ^4Syntax: !pm <SlotID/PartOfName> <Message>" );
				}
				break;
			}
			case "fpsboost":
			case "boost":
			case "fps": {
				rcon_command( "fps " . $data[ 2 ] );
				if ( $players[ $server ][ $data[ 2 ] ][ "fpsboost" ] == TRUE ) {
					mysql_unbuffered_query( "UPDATE players SET fpsboost = FALSE WHERE guid = '" . $data[ 1 ] . "'" );
					$players[ $server ][ $data[ 2 ] ][ "fpsboost" ] = FALSE;
				} //$players[ $server ][ $data[ 2 ] ][ "fpsboost" ] == TRUE
				else {
					mysql_unbuffered_query( "UPDATE players SET fpsboost = TRUE WHERE guid = '" . $data[ 1 ] . "'" );
					$players[ $server ][ $data[ 2 ] ][ "fpsboost" ] = TRUE;
				}
				break;
			}
			case "math": {
				if ( empty( $command[ 1 ] ) ) {
					rcon_command( "tell " . $data[ 2 ] . " \"^4Syntax: !math <Function>\"" );
					break;
				} //empty( $command[ 1 ] )
				if ( preg_match( '/^[\d\+\-\/\*\s]+$/', $command[ 1 ] ) ) {
					@eval( '$result = (' . $command[ 1 ] . ');' );
				} //preg_match( '/^[\d\+\-\/\*\s]+$/', $command[ 1 ] )
				if ( isset( $result ) && !empty( $result ) ) {
					rcon_command( "tell " . $data[ 2 ] . " \"^4I think " . $command[ 1 ] . " is " . $result . "\"" );
				} //isset( $result ) && !empty( $result )
				else {
					rcon_command( "tell " . $data[ 2 ] . " \"^4I can't do that, sorry.\"" );
				}
				unset( $result );
				break;
			}
			case "time": {
				rcon_command( "tell " . $data[ 2 ] . " " . date( "F j, Y, g:i a" ) );
				break;
			}
			default:
				rcon_command( "tell " . $data[ 2 ] . " ^1Command not found. For a list of commands, type !cmdlist" );
				break;
		} //$command[ 0 ]
	} //$data[ 4 ][ 0 ] == "!"
	else {
		foreach ( $players as $serverr => $slot ) {
			foreach ( $slot as $id => $useless ) {
				if ( isset( $players[ $serverr ][ $id ] ) && $players[ $serverr ][ $id ][ "rank" ] > 2 && $server != $serverr ) {
					rcon_command( "tell " . $id . " ^7" . $data[ 3 ] . " > " . $data[ 4 ] . " (" . $server . ")", server( $serverr ) );
				} //isset( $players[ $serverr ][ $id ] ) && $players[ $serverr ][ $id ][ "rank" ] > 2 && $server != $serverr
			} //$slot as $id => $useless
		} //$players as $serverr => $slot
		unset( $serverr, $slot, $id, $useless );
	}
?>