<?php
	$data[ 4 ] = preg_replace( '/\x15/', '', $data[ 4 ] );
	echo $colors->getColoredString( $server, "black", "light_gray" );
	echo $colors->getColoredString( $data[ 3 ] . " (" . $data[ 2 ] . ") > " . $data[ 4 ] . " (TEAM)", "black", "yellow" );
	mysql_unbuffered_query( "INSERT INTO chat_logs (guid, name, server, team, message) VALUES ('" . $data[ 1 ] . "', '" . mysql_real_escape_string( $data[ 3 ] ) . "', '" . $server . "', 'team', '" . mysql_real_escape_string( $data[ 4 ] ) . "')" );
	if ( $data[ 4 ][ 1 ] == "!" ) {
		rcon_command( "tell " . $data[ 2 ] . " ^1Commands don't work in team chat. If you want to hide your message, put a / infront of the command." );
	} //$data[ 4 ][ 1 ] == "!"
?>