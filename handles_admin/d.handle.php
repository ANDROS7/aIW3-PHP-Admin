<?php
    /** 
    1 => DAMAGED GUID
    2 => DAMAGED SLOT ID
    3 => DAMAGED TEAM
    4 => DAMAGED NAME
    5 => ATTACKER GUID
    6 => ATTACKER SLOT ID
    7 => ATTACKER TEAM
    8 => ATTACKER NAME
    9 => ATTACKER WEAPON
    10 => ATTACKER DAMAGE
    11 => TYPE
    12 => DAMAGED HITPOS
    **/
    /**
	mysql_unbuffered_query( "INSERT INTO player_stats (guid, bullets) VALUES ('" . $data[ 5 ] . "', 1)
                                            ON DUPLICATE KEY UPDATE bullets = bullets + 1" );
                                            
	mysql_unbuffered_query( "INSERT INTO player_stats (guid, damage) VALUES ('" . $data[ 5 ] . "', ".$data[10].")
                                            ON DUPLICATE KEY UPDATE damage = damage + ".$data[10] );
                           
    if(!empty($data[5]) && $data[9] != "NONE" && $data[10] <= 100) {
        $q = mysql_query("SELECT * FROM weapon_player_stats WHERE guid = '".$data[5]."' AND weapon = '".strtoupper($data[9])."' LIMIT 1");
        if($q && mysql_num_rows($q) == 1){
            // eintrag vorhanden, updaten
            mysql_unbuffered_query("UPDATE weapon_player_stats SET damage = damage + ".$data[10].", bullets = bullets + 1 WHERE guid = '".$data[5]."' AND weapon = '".strtoupper($data[9])."'");
            if(mysql_error()) {
                echo mysql_error().PHP_EOL;
            }
        } else {
            // neuen eintrag erstellen
            mysql_unbuffered_query("INSERT INTO weapon_player_stats (guid, weapon, damage, kills, bullets) VALUES
                                    ('".$data[5]."', '".strtoupper($data[9])."', '".$data[10]."', '0', '1')");
        }
    }
    **/
	if ( !isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ] ) {
		mysql_unbuffered_query( "DELETE FROM playerlist WHERE guid = '" . $data[ 1 ] . "' AND server = '" . $server . "'" );
		unset( $players[ $server ][ $data[ 2 ] ] );
		$players[ $server ][ $data[ 2 ] ] = mysql_fetch_array( mysql_query( "SELECT * FROM players WHERE guid = '" . $data[ 1 ] . "' LIMIT 1" ) );
        $punkte[$data[ 1 ]] = $players[ $server ][ $data[ 2 ] ]["score"];
		mysql_unbuffered_query( "INSERT INTO playerlist (server, guid, slotid) VALUES ('" . $server . "', '" . $data[ 1 ] . "', '" . $data[ 2 ] . "')" );
	} //!isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ]
 
	if ( !isset( $players[ $server ][ $data[ 6 ] ] ) || $players[ $server ][ $data[ 6 ] ][ "guid" ] != $data[ 5 ] ) {
		mysql_unbuffered_query( "DELETE FROM playerlist WHERE guid = '" . $data[ 5 ] . "' AND server = '" . $server . "'" );
		unset( $players[ $server ][ $data[ 6 ] ] );
		$players[ $server ][ $data[ 6 ] ] = mysql_fetch_array( mysql_query( "SELECT * FROM players WHERE guid = '" . $data[ 5 ] . "' LIMIT 1" ) );
        $punkte[$data[ 5 ]] = $players[ $server ][ $data[ 6 ] ]["score"];
		mysql_unbuffered_query( "INSERT INTO playerlist (server, guid, slotid) VALUES ('" . $server . "', '" . $data[ 5 ] . "', '" . $data[ 6 ] . "')" );
	} //!isset( $players[ $server ][ $data[ 2 ] ] ) || $players[ $server ][ $data[ 2 ] ][ "guid" ] != $data[ 1 ]
  
    if(!empty($data[5]) && $data[9] != "NONE" && $data[6] != $data[2]) {
        $fetch[$data[5]]["weapon"][$data[9]]["damage"] += $data[10];
        $fetch[$data[5]]["weapon"][$data[9]]["bullets"]++;
        $fetch[$data[5]]["bullets"]++;
        $fetch[$data[5]]["damage"] += $data[10];
    }
    
    ?>