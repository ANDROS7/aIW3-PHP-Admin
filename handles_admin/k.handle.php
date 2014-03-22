<?php
    //var_dump($data);
    
    
	if ( !isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ] ) {
		mysql_unbuffered_query( "DELETE FROM playerlist WHERE guid = '" . $data[ 1 ] . "' AND server = '" . $server . "'" );
		unset( $players[ $server ][ $data[ 2 ] ] );
		$players[ $server ][ $data[ 2 ] ] = mysql_fetch_array( mysql_query( "SELECT * FROM players WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" ) );
		mysql_unbuffered_query( "INSERT INTO playerlist (server, guid, slotid) VALUES ('" . $server . "', '" . $data[ 1 ] . "', '" . $data[ 2 ] . "')" );
	} //!isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ]

	if ( $players[ $server ][ $data[ 6 ] ][ "banned" ] == TRUE ) {
		$bandata = mysql_fetch_array( mysql_query( "SELECT reason FROM bans WHERE guid = '" . $data[ 5 ] . "'" ) );
		rcon_command( "clientkick " . $data[ 6 ] . " \"You are banned. Reason: " . $bandata[ "reason" ] . "\"" );
		unset( $bandata );
	} //$players[ $server ][ $data[ 2 ] ][ "banned" ] == TRUE
    
    if(!empty($data[5]) && $data[9] != "NONE" && $data[6] != $data[2]) {
        $fetch[$data[5]]["weapon"][$data[9]]["kills"]++;
        $fetch[$data[5]]["weapon"][$data[9]]["damage"] += $data[10];
        $fetch[$data[5]]["weapon"][$data[9]]["bullets"]++;
        
        $fetch[$data[5]]["kills"]++;
        $fetch[$data[5]]["bullets"]++;
        $fetch[$data[5]]["damage"] += $data[10];
    }
    

    $fetch[$data[1]]["deaths"]++;
        
	$players[ $server ][ $data[ 6 ] ][ "ks" ]++;
	if ( $players[ $server ][ $data[ 6 ] ][ "ks" ] != 0 && $players[ $server ][ $data[ 6 ] ][ "ks" ] % 7 == 0 ) {
		rcon_command( "say ^4" . $data[ 8 ] . "^0 is on a killstreak with ^4" . $players[ $server ][ $data[ 6 ] ][ "ks" ] . "^0 kills!" );
	} //$players[ $server ][ $data[ 6 ] ][ "ks" ] != 0 && $players[ $server ][ $data[ 6 ] ][ "ks" ] % 7 == 0
	if ( $players[ $server ][ $data[ 2 ] ][ "ks" ] >= 7 ) {
		if ( $data[ 8 ] == $data[ 4 ] ) {
			rcon_command( "say ^4" . $data[ 8 ] . "^0 ended his own killstreak! ^4NOOB^0!" );
		} //$data[ 8 ] == $data[ 4 ]
		else {
			rcon_command( "say ^4" . $data[ 8 ] . "^0 ended ^4" . $data[ 4 ] . "^0's killstreak! (^4" . $players[ $server ][ $data[ 2 ] ][ "ks" ] . " Kills^0)" );
		}
	} //$players[ $server ][ $data[ 2 ] ][ "ks" ] >= 7
    
	$players[ $server ][ $data[ 2 ] ][ "ks" ] = 0;
	echo $colors->getColoredString( $server, "black", "light_gray" );
	echo $colors->getColoredString( $data[ 8 ] . " (" . $data[ 6 ] . ") > ", "black", "cyan" );
	echo $colors->getColoredString( strtoupper( $data[ 9 ] ), "light_red", "cyan" );
	echo $colors->getColoredString( " < " . $data[ 4 ] . " (" . $data[ 2 ] . ")", "black", "cyan" );
	if ( $data[ 2 ] == $data[ 6 ] && strtoupper( $data[ 9 ] ) != "NONE" ) {
		rcon_command( "say \"^4" . $data[ 8 ] . " just killed himself. Nooooob!\"" );
	} //$data[ 2 ] == $data[ 6 ] && strtoupper( $data[ 9 ] ) != "NONE"
	if ( $data[ 7 ] == $data[ 3 ] && $data[ 1 ] != $data[ 5 ] && $data[ 2 ] != $data[ 6 ] && $server != "fiesta" && $server != "RSE" ) {
		$players[ $server ][ $data[ 6 ] ][ "tk" ]++;
        rcon_command( "tell " . $data[ 6 ] . " ^1Stop teamkilling! (" . $players[ $server ][ $data[ 6 ] ][ "tk" ] . " / 5)" );
		if ( $players[ $server ][ $data[ 6 ] ][ "tk" ] >= 5 ) {
            rcon_command("freeze ".$data[6]);
            sleep(3);
			rcon_command( "clientkick " . $data[ 6 ] . " \"Too many teamkills\"" );
		}
	} //$data[ 7 ] == $data[ 3 ] && $data[ 1 ] != $data[ 5 ] && $data[ 2 ] != $data[ 6 ] && $server != "fiesta"
	if ( strtoupper( $data[ 9 ] ) == "AIRDROP_MARKER_MP" ) {
		$players[ $server ][ $data[ 6 ] ][ "airdropk" ]++;
		if ( $players[ $server ][ $data[ 6 ] ][ "airdropk" ] >= 3 ) {
			rcon_command( "clientkick " . $data[ 6 ] . " \"Too many airdrop marker kills\"" );
		} //$players[ $server ][ $data[ 6 ] ][ "airdropk" ] >= 3
		else {
			rcon_command( "tell " . $data[ 6 ] . " ^1NO CAREPACKAGE RUNNING! (" . $players[ $server ][ $data[ 6 ] ][ "airdropk" ] . " / 3)" );
		}
	} //strtoupper( $data[ 9 ] ) == "AIRDROP_MARKER_MP"
?>