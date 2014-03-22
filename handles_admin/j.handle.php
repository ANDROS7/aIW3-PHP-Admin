<?php
	unset( $players[ $server ][ $data[ 2 ] ] );
	echo $colors->getColoredString( $server, "black", "light_gray" );
	echo $colors->getColoredString( $data[ 3 ] . " (" . $data[ 1 ] . ") hat den Server betreten (" . ( count( $players[ $server ] ) + 1 ) . " / 18)", "black", "green" );
    mysql_unbuffered_query( "INSERT INTO players (guid, name, connections) VALUES ('" . $data[ 1 ] . "', '" . mysql_real_escape_string( $data[ 3 ] ) . "', '0')
                             ON DUPLICATE KEY UPDATE connections = connections + 1, name = '" . $data[ 3 ] . "'" );

	$q1 = mysql_query( "SELECT key FROM player_stats WHERE guid = '" . $data[ 1 ] . "' AND server = '" . $server . "' LIMIT 1" );
	if ( !$q1 || mysql_num_rows( $q1 ) == 0 ) {
		mysql_unbuffered_query( "INSERT INTO player_stats (guid, kills, deaths, server) VALUES ('" . $data[ 1 ] . "', '0', '0', '" . $server . "')" );
	} //!$q1 || mysql_num_rows( $q1 ) == 0
	mysql_unbuffered_query( "INSERT INTO playerlist (slotid, guid, server) VALUES ('" . $data[ 2 ] . "', '" . $data[ 1 ] . "', '" . $server . "')" );
    if($server == "RSE") {
        if(strstr($data[3], "Thymia")) {
            foreach($players[$server] as $slotid => $playerd) {
                if(!strstr($playerd["name"], "bot")) {
                    rcon_command("clientkick ".$slotid." \"VIP-Kick\"");
                }
            }
        }
        
        foreach($players[$server] as $slotid => $playerd) {
            if(strstr($playerd["name"], "Thymia") && !strstr($data[3], "bot")) {
                rcon_command("clientkick ".$data[2]." \"Server is full\"");
            }
        }
        unset($slotid, $playerd);
    }
	$players[ $server ][ $data[ 2 ] ]                   = mysql_fetch_array( mysql_query( "SELECT * FROM players WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" ) );
	$players[ $server ][ $data[ 2 ] ][ "playtime_sec" ] = (int) $players[ $server ][ $data[ 2 ] ][ "playtime_sec" ];
    
    
    $punkte[$data[1]] = $players[ $server ][ $data[ 2 ] ]["score"];
	if ( !$players[ $server ][ $data[ 2 ] ][ "banned" ] ) {
		if ( $players[ $server ][ $data[ 2 ] ][ "playtime_sec" ] <= 0 ) {
			rcon_command( "say Welcome ^4" . $data[ 3 ] . "^0! :)" );
		} //$players[ $server ][ $data[ 2 ] ][ "playtime_sec" ] <= 0
		else {
			rcon_command( "say \"Welcome back, ^4" . $data[ 3 ] . "^0! :)\"" );
			rcon_command( "tell " . $data[ 2 ] . " \"Your last connection was " . diff_time( strtotime( $players[ $server ][ $data[ 2 ] ][ "last" ] ) ) . "\"" );
		}
        
		/**
        if ( $players[ $server ][ $data[ 2 ] ][ "rank" ] > 2 ) {
			rcon_command( "say \"^4Gamemaster ^7" . $data[ 3 ] . "^4 has joined the Game!\"", server( $server ) );
		} //$players[ $server ][ $data[ 2 ] ][ "rank" ] > 2
        **/
	} //!$players[ $server ][ $data[ 2 ] ][ "banned" ] && !$players[ $server ][ $data[ 2 ] ][ "rank" ] >= 2
	if ( $players[ $server ][ $data[ 2 ] ][ "fpsboost" ] == TRUE ) {
		rcon_command( "fps " . $data[ 2 ] );
		rcon_command( "tell " . $data[ 2 ] . " \"^4FPS-Boost was applied to you. ^0To remove, type !fps\"" );
	} //$players[ $server ][ $data[ 2 ] ][ "fpsboost" ] == TRUE
	mysql_unbuffered_query( "UPDATE players SET last = CURRENT_TIMESTAMP WHERE guid = '" . $data[ 1 ] . "'" );
	if ( $players[ $server ][ $data[ 2 ] ][ "banned" ] == TRUE ) {
		$bandata = mysql_fetch_array( mysql_query( "SELECT reason FROM bans WHERE guid = '" . $data[ 1 ] . "'" ) );
		rcon_command( "clientkick " . $data[ 2 ] . " \"You are banned. Reason: " . $bandata[ "reason" ] . "\"" );
		unset( $bandata );
	} //$players[ $server ][ $data[ 2 ] ][ "banned" ] == TRUE
	if ( $server == "testing" && $players[ $server ][ $data[ 2 ] ][ "rank" ] < 2 ) {
		rcon_command( "clientkick " . $data[ 2 ] . " You are not allowed here" );
	} //$server == "testing" && $players[ $server ][ $data[ 2 ] ][ "rank" ] < 2
?>