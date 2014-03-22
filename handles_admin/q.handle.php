<?php
	if ( isset( $votekick[ $server ][ "user" ][ "id" ] ) && $votekick[ $server ][ "user" ][ "id" ] == $data[ 2 ] ) {
		$votekick[ $server ][ "initiated" ] = ( time() - 120 );
	} //isset( $votekick[ $server ][ "user" ][ "id" ] ) && $votekick[ $server ][ "user" ][ "id" ] == $data[ 2 ]
	unset( $players[ $server ][ $data[ 2 ] ] );
	echo $colors->getColoredString( $server, "black", "light_gray" );
	echo $colors->getColoredString( $data[ 3 ] . " (" . $data[ 1 ] . ") hat den Server verlassen (" . count( $players[ $server ] ) . " / 18)", "black", "red" );
	mysql_unbuffered_query( "DELETE FROM playerlist WHERE guid = '" . $data[ 1 ] . "' AND slotid = '" . $data[ 2 ] . "' AND server = '" . $server . "'" );
    mysql_unbuffered_query("DELETE FROM positions WHERE guid = '".$data[1]."'");
    unset($punkte[$data[1]]);
?>
