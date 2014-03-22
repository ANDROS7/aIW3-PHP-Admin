<?php
	if ( $tick <= ( time() - 1 ) ) {
		//update_logs();
		if ( count( $players[ $server ] ) > 18 ) {
			unset( $players[ $server ] );
			mysql_unbuffered_query( "DELETE FROM playerlist WHERE server = '" . $server . "'" );
		} //count( $players[ $server ] ) > 18
        
        if($getips <= (time() - 30)) {
            foreach($players as $serverr => $tmp) {
                /**
                if(empty($tmp)) {
                    continue;
                }
                if($q = new q3rcon("5.199.133.184", server($serverr), "myRconPassword")) {
                    echo "success".PHP_EOL;
                    $pls = $q->get_players();
                    var_dump($pls);
                    $q->close();
                }
                **/
            }
            unset($tmp, $serverr);
            $getips = time();
        }
        
		if ( $fetchmysql <= ( time() - 60 ) ) {
            update_logs();
			echo $colors->getColoredString( "------------------------------- SAVING DATA -------------------------------", "black", "red" ) . PHP_EOL;
			echo $colors->getColoredString( "---------------------------------------------------------------------------", "black", "red" ) . PHP_EOL;
			foreach ( $fetch as $guid => $data ) {
				if ( strlen( $guid ) < 16 ) {
					unset( $fetch[ $guid ] );
					continue;
				} //strlen( $guid ) < 16
                
                if(is_array($data["weapon"])) {
                    foreach ( $data[ "weapon" ] as $weapon => $ff ) {
                        $q = mysql_query( "SELECT damage FROM weapon_player_stats WHERE weapon = '" . $weapon . "' AND guid = '" . $guid . "' LIMIT 1" );
                        if ( $q && mysql_num_rows( $q ) == 1 ) {
                            mysql_unbuffered_query( "UPDATE weapon_player_stats SET damage = damage + " . $ff[ "damage" ] . ", kills = kills + ".$ff["kills"].", bullets = bullets + " . $ff[ "bullets" ] . " WHERE weapon = '" . $weapon . "' AND guid = '" . $guid . "'" );
                        } //$q && mysql_num_rows( $q ) == 1
                        else {
                            mysql_unbuffered_query( "INSERT INTO weapon_player_stats (guid, weapon, damage, kills, bullets) VALUES ('" . $guid . "', '" . $weapon . "', '" . $ff[ "damage" ] . "', '" . $ff[ "kills" ] . "', '" . $ff[ "bullets" ] . "')" );
                        }
                    } //$data[ "weapon" ] as $weapon => $ff
                }
                $punkte[$guid] += round($fetch[ $pl[ "guid" ] ][ "playtime_sec" ] / 30);
                mysql_unbuffered_query( "INSERT INTO player_stats (guid, kills, deaths, bullets, damage) VALUES ('" . $guid . "', ".$data["kills"].", ".$data["deaths"].", ".$data["bullets"].", ".$data["damage"].")
                                            ON DUPLICATE KEY UPDATE kills = kills + " . $data[ "kills" ] . ", deaths = deaths + " . $data[ "deaths" ] . ", bullets = bullets + ".$data["bullets"].", damage = damage + ".$data["damage"] );
                                            
				//mysql_unbuffered_query( "UPDATE player_stats SET kills = kills + " . $data[ "kills" ] . ", deaths = deaths + " . $data[ "deaths" ] . ", bullets = bullets + ".$data["bullets"].", damage = damage + ".$data["damage"]." WHERE guid = '" . $guid . "'" );
				mysql_unbuffered_query( "UPDATE players SET score = ".$punkte[$guid].", playtime_sec = playtime_sec + " . $data[ "playtime_sec" ] . " WHERE guid = '" . $guid . "'" );
                mysql_unbuffered_query("UPDATE playerlist SET playtime = playtime + ".(int)$data["playtime"]." WHERE guid = '".$guid."'");
                //echo mysql_Error();
                foreach($players as $srv => $playerss) {
                    foreach($playerss as $id => $player) {
                        //var_dump($player);
                    }
                }
                unset($srv, $playerss, $player);
				echo $colors->getColoredString( "--------------------- UPDATED PLAYER " . $guid . " ---------------------", "black", "red" ) . PHP_EOL;
				unset( $fetch[ $guid ] );
			} //$fetch as $guid => $data
			unset( $guid, $data, $weapon, $ff, $q );
			echo $colors->getColoredString( "---------------------------------------------------------------------------", "black", "red" ) . PHP_EOL;
			echo $colors->getColoredString( "------------------------------- DATA SAVED --------------------------------", "black", "red" ) . PHP_EOL;
			$fetchmysql = time();
		} //$fetchmysql <= ( time() - 900 )
        
		if ( $playtime_timer <= ( time() - 1 ) ) {
			$to_to_add = ( time() - $playtime_timer );
			foreach ( $players as $srv => $player ) {
				if ( $to_to_add == 0 )
					break;
				foreach ( $player as $id => $pl ) {
					$countp++;
					$fetch[ $pl[ "guid" ] ][ "playtime_sec" ] += $to_to_add;
                    $fetch[$pl["guid"]]["playtime"] += $to_to_add;
                    $players[ $srv ][ $id ][ "playtime_sec" ] += $to_to_add;
					$total                                    = $fetch[ $pl[ "guid" ] ][ "playtime_sec" ] + $players[ $srv ][ $id ][ "playtime_sec" ];
					if ( $total % 3600 == 0 ) {
						rcon_command( "say \"^1" . $pl[ "name" ] . "^4, thanks for sticking ^0" . floor( $total / 3600 ) . "^4 hour" . ( floor( $total / 3600 ) == 1 ? "" : "s" ) . " with us!\"", server( $srv ) );
						rcon_command( "tell " . $id . " \"^4We hope you enjoy ^0[^4x4^0]! Keep on!\"", server( $srv ) );
					} //$total % 3600 == 0
				} //$player as $id => $pl
			} //$players as $srv => $player
			//echo $colors->getColoredString( $to_to_add . " second" . ( $to_to_add == 1 ? "" : "s" ) . " playtime to " . (int) $countp . " players added", "purple", "white" ) . PHP_EOL;
			unset( $srv, $player, $guids, $pl, $countp, $total );
			$playtime_timer = time();
		} //$playtime_timer <= ( time() - 1 )
		if ( !isset( $last ) ) {
			$last = time();
		} //!isset( $last )
		if ( !$votetype ) {
			$votetype = array( );
		} //!$votetype
		if ( !$votekick ) {
			$votekick = array( );
		} //!$votekick
		foreach ( $votetype as $serverr => $votekickk ) {
			if ( isset( $votekickk[ "initiated" ] ) && $votekickk[ "initiated" ] <= ( time() - 120 ) ) {
				unset( $votetype[ $serverr ], $oldvk[ $serverr ] );
				rcon_command( "b3_message \" ^1Voting failed\"", server( $serverr ) );
				rcon_command( "b3_message1 \" \"", server( $serverr ) );
				sleep( 3 );
				rcon_command( "b3_message \" \"", server( $serverr ) );
			} //isset( $votekickk[ "initiated" ] ) && $votekickk[ "initiated" ] <= ( time() - 120 )
			else {
				if ( $votekickk[ "min" ] <= $votekickk[ "voted_yes" ] && $votekickk[ "voted_no" ] <= $votekickk[ "voted_yes" ] ) {
					rcon_command( "b3_message \" ^2Voting successful\"", server( $serverr ) );
					if ( $votekickk[ "type" ] == "map" ) {
						rcon_command( "b3_message1 \" Changing to " . ucfirst( $votekickk[ "type" ] ) . " " . strtoupper( trim( $votekickk[ "mapname" ][ 1 ] ) ) . " in 10 Seconds\"", server( $serverr ) );
					} //$votekickk[ "type" ] == "map"
					elseif ( $votekickk[ "type" ] == "gametype" ) {
						rcon_command( "b3_message1 \" Changing to " . ucfirst( $votekickk[ "type" ] ) . " " . strtoupper( trim( $votekickk[ "gametype" ][ 1 ] ) ) . " in 10 Seconds\"", server( $serverr ) );
					} //$votekickk[ "type" ] == "gametype"
					sleep( 10 );
					rcon_command( "b3_message \" \"", server( $serverr ) );
					rcon_command( "b3_message1 \" \"", server( $serverr ) );
					mysql_unbuffered_query( "DELETE FROM playerlist WHERE server = '" . $serverr . "'" );
					rcon_command( "g_log \"" . strtolower( $votekickk[ "mapname" ][ 0 ] ) . ".log\"", serrver( $serverr ) );
					update_logs();
					if ( $votekickk[ "type" ] == "map" ) {
						rcon_command( "sv_hostname \"^7[^4x4^7]^0 ^4" . ucfirst( strtolower( trim( $votekickk[ "mapname" ][ 1 ] ) ) ) . " ^024/7\"", server( $serverr ) );
						rcon_command( "sv_mapRotation \"map " . trim( $votekickk[ "mapname" ][ 0 ] ) . "\"", server( $serverr ) );
						rcon_command( "map_rotate", server( $serverr ) );
					} //$votekickk[ "type" ] == "map"
					elseif ( $votekickk[ "type" ] == "gametype" ) {
						rcon_command( "g_gametype \"" . $votekickk[ "gametype" ][ 0 ] . "\"", server( $serverr ) );
						rcon_command( "scr_" . $votekickk[ "gametype" ][ 0 ] . "_timelimit \"30\"", server( $serverr ) );
						rcon_command( "scr_" . $votekickk[ "gametype" ][ 0 ] . "_scorelimit \"0\"", server( $serverr ) );
						rcon_command( "map_restart", server( $serverr ) );
					} //$votekickk[ "type" ] == "gametype"
					$configured[ $serverr ] = true;
					unset( $votetype[ $serverr ], $oldvk[ $serverr ] );
				} //$votekickk[ "min" ] <= $votekickk[ "voted_yes" ] && $votekickk[ "voted_no" ] <= $votekickk[ "voted_yes" ]
				elseif ( !isset( $oldvk[ $serverr ] ) || ( $oldvk[ $serverr ][ "no" ] < $votekickk[ "voted_no" ] || $oldvk[ $serverr ][ "yes" ] < $votekickk[ "voted_yes" ] ) ) {
					if ( $votekickk[ "type" ] == "map" ) {
						rcon_command( "b3_message \" VOTE: Change Map to " . $votekickk[ "mapname" ][ 1 ] . "\"", server( $serverr ) );
					} //$votekickk[ "type" ] == "map"
					elseif ( $votekickk[ "type" ] == "gametype" ) {
						rcon_command( "b3_message \" VOTE: Change Gametype to " . $votekickk[ "gametype" ][ 1 ] . "\"", server( $serverr ) );
					} //$votekickk[ "type" ] == "gametype"
					rcon_command( "b3_message1 \" Type !^2yes^7/^1no^7 | ^2" . (int) $votekickk[ "voted_yes" ] . " ^7/ ^1" . (int) $votekickk[ "voted_no" ] . " ^7(Needed: " . round( ( $votekickk[ "min" ] - $votekickk[ "voted_yes" ] ) ) . ")\"", server( $serverr ) );
					$oldvk[ $serverr ] = array(
						 "no" => (int) $votekickk[ "voted_no" ],
						"yes" => (int) $votekickk[ "voted_yes" ] 
					);
				} //!isset( $oldvk[ $serverr ] ) || ( $oldvk[ $serverr ][ "no" ] < $votekickk[ "voted_no" ] || $oldvk[ $serverr ][ "yes" ] < $votekickk[ "voted_yes" ] )
			}
		} //$votetype as $serverr => $votekickk
		unset( $serverr, $votekickk );
		foreach ( $votekick as $serverr => $votekickk ) {
			if ( $votekickk[ "initiated" ] <= ( time() - 120 ) ) {
				unset( $votekick[ $serverr ], $oldvk[ $serverr ] );
				rcon_command( "b3_message \" ^1Votekick failed\"", server( $serverr ) );
				rcon_command( "b3_message1 \" \"", server( $serverr ) );
				sleep( 3 );
				rcon_command( "b3_message \" \"", server( $serverr ) );
				rcon_command( "b3_message1 \" \"", server( $serverr ) );
			} //$votekickk[ "initiated" ] <= ( time() - 120 )
			else {
				if ( $votekickk[ "min" ] <= $votekickk[ "voted_yes" ] && $votekickk[ "voted_no" ] <= $votekickk[ "voted_yes" ] ) {
					rcon_command( "b3_message \" ^2Votekick successful\"", server( $serverr ) );
					rcon_command( "b3_message1 \" \"", server( $serverr ) );
					rcon_command( "clientkick " . $votekickk[ "user" ][ "id" ] . " \"Votekicked - Reason: " . $votekickk[ "reason" ] . "\"", server( $serverr ) );
					sleep( 3 );
					rcon_command( "b3_message \" \"", server( $serverr ) );
					rcon_command( "b3_message1 \" \"", server( $serverr ) );
					unset( $votekick[ $serverr ], $oldvk[ $serverr ] );
				} //$votekickk[ "min" ] <= $votekickk[ "voted_yes" ] && $votekickk[ "voted_no" ] <= $votekickk[ "voted_yes" ]
				elseif ( !isset( $oldvk[ $serverr ] ) || ( $oldvk[ $serverr ][ "no" ] < $votekickk[ "voted_no" ] || $oldvk[ $serverr ][ "yes" ] < $votekickk[ "voted_yes" ] ) ) {
					rcon_command( "b3_message \" VOTEKICK: " . $votekickk[ "user" ][ "name" ] . " (#" . $votekickk[ "user" ][ "id" ] . ") - Reason: " . $votekickk[ "reason" ] . "\"", server( $serverr ) );
					rcon_command( "b3_message1 \" Type !^2yes^7/^1no^7 | ^2" . (int) $votekickk[ "voted_yes" ] . " ^7/ ^1" . (int) $votekickk[ "voted_no" ] . " ^7(Needed: " . round( ( $votekickk[ "min" ] - $votekickk[ "voted_yes" ] ) ) . ")\"", server( $serverr ) );
					$oldvk[ $serverr ] = array(
						 "no" => (int) $votekickk[ "voted_no" ],
						"yes" => (int) $votekickk[ "voted_yes" ] 
					);
				} //!isset( $oldvk[ $serverr ] ) || ( $oldvk[ $serverr ][ "no" ] < $votekickk[ "voted_no" ] || $oldvk[ $serverr ][ "yes" ] < $votekickk[ "voted_yes" ] )
			}
		} //$votekick as $serverr => $votekickk
		unset( $serverr, $votekickk );
		if ( $last <= ( time() - 60 ) ) {
			echo $colors->getColoredString( "---------------------------------------------------------------------------", "black", "magenta" ) . PHP_EOL;
			$message = $dyk[ mt_rand( 0, ( count( $dyk ) - 1 ) ) ];
			foreach ( $players as $serverr => $d ) {
				rcon_command( "b3_message2 \" " . $message . "\"", server( $serverr ), 0 );
			} //$players as $serverr => $d
			echo $colors->getColoredString( "------------------------- SWITCHED TIMED MESSAGES -------------------------", "black", "magenta" ) . PHP_EOL;
			echo $colors->getColoredString( "---------------------------------------------------------------------------", "black", "magenta" ) . PHP_EOL;
			unset( $serverr, $d, $message );
			$last = time();
		} //$last <= ( time() - 60 )
		$tick = time();
	} //$tick <= ( time() - 1 )
?>