<?php
    define("FILE_DIR", str_replace( "handles_admin", "", dirname( __FILE__ ) ));

	class Colors
	{
		private $foreground_colors = array( );
		private $background_colors = array( );
		public function __construct( )
		{
			$this->foreground_colors[ 'black' ]        = '0;30';
			$this->foreground_colors[ 'dark_gray' ]    = '1;30';
			$this->foreground_colors[ 'blue' ]         = '0;34';
			$this->foreground_colors[ 'light_blue' ]   = '1;34';
			$this->foreground_colors[ 'green' ]        = '0;32';
			$this->foreground_colors[ 'light_green' ]  = '1;32';
			$this->foreground_colors[ 'cyan' ]         = '0;36';
			$this->foreground_colors[ 'light_cyan' ]   = '1;36';
			$this->foreground_colors[ 'red' ]          = '0;31';
			$this->foreground_colors[ 'light_red' ]    = '1;31';
			$this->foreground_colors[ 'purple' ]       = '0;35';
			$this->foreground_colors[ 'light_purple' ] = '1;35';
			$this->foreground_colors[ 'brown' ]        = '0;33';
			$this->foreground_colors[ 'yellow' ]       = '1;33';
			$this->foreground_colors[ 'light_gray' ]   = '0;37';
			$this->foreground_colors[ 'white' ]        = '1;37';
			$this->background_colors[ 'black' ]        = '40';
			$this->background_colors[ 'red' ]          = '41';
			$this->background_colors[ 'green' ]        = '42';
			$this->background_colors[ 'yellow' ]       = '43';
			$this->background_colors[ 'blue' ]         = '44';
			$this->background_colors[ 'magenta' ]      = '45';
			$this->background_colors[ 'cyan' ]         = '46';
			$this->background_colors[ 'light_gray' ]   = '47';
		}
		public function getColoredString( $string, $foreground_color = null, $background_color = null )
		{
			$colored_string = "";
			if ( isset( $this->foreground_colors[ $foreground_color ] ) ) {
				$colored_string .= "\033[" . $this->foreground_colors[ $foreground_color ] . "m";
			} //isset( $this->foreground_colors[ $foreground_color ] )
			if ( isset( $this->background_colors[ $background_color ] ) ) {
				$colored_string .= "\033[" . $this->background_colors[ $background_color ] . "m";
			} //isset( $this->background_colors[ $background_color ] )
			$colored_string .= $string . "\033[0m";
			return $colored_string;
		}
		public function getForegroundColors( )
		{
			return array_keys( $this->foreground_colors );
		}
		public function getBackgroundColors( )
		{
			return array_keys( $this->background_colors );
		}
	}
	function push( $header, $message, $priority = 0 )
	{
		$fields = array(
			 'apikey' => urlencode( "50d69727b39076ccf70475af70e1f9780b9c598c" ),
			'application' => urlencode( "aIW3 Bot" ),
			'event' => urlencode( $header ),
			'priority' => $priority,
			'description' => urlencode( $message ) 
		);
		foreach ( $fields as $key => $value ) {
			$fields_string .= $key . '=' . $value . '&';
		} //$fields as $key => $value
		rtrim( $fields_string, '&' );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "http://api.prowlapp.com/publicapi/add" );
		curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
		$result = curl_exec( $ch );
		curl_close( $ch );
		$fields        = array(
			 "token" => "ubDHRfDBcBALCBbqjtKfZ5p1MCYoC5",
			"user" => "a3GnedbRnzHY3Ldgr8gjrphMJZ2Dp4",
			"title" => urlencode( $header ),
			"message" => urlencode( $message ) 
		);
		$fields_string = "";
		foreach ( $fields as $key => $value ) {
			$fields_string .= $key . '=' . $value . '&';
		} //$fields as $key => $value
		rtrim( $fields_string, '&' );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "https://api.pushover.net/1/messages.json" );
		curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		$result = curl_exec( $ch );
		curl_close( $ch );
	}
	function diff_time( $differenz, $time = "" )
	{
		if ( $differenz == NULL || empty( $differenz ) || is_null( $differenz ) )
			return " never ";
		if ( $time == "" )
			$time = time();
		$r         = "";
		$differenz = $time - $differenz;
		$tag       = floor( $differenz / ( 3600 * 24 ) );
		$std       = floor( $differenz / 3600 % 24 );
		$min       = floor( $differenz / 60 % 60 );
		$sek       = floor( $differenz % 60 );
		if ( $tag != 0 ) {
			return $tag . " day" . ( $tag != "1" ? "s" : "" ) . " ago ";
		} //$tag != 0
		if ( $std != 0 ) {
			return $std . " hour" . ( $std != "1" ? "s" : "" ) . " ago ";
		} //$std != 0
		if ( $min != 0 ) {
			return $min . " min" . ( $min != "1" ? "s" : "" ) . " ago ";
		} //$min != 0
		if ( $sek != 0 ) {
			return $sek . " sec" . ( $sek != "1" ? "s" : "" ) . " ago ";
		} //$sek != 0
	}
	function is_integer2( $v )
	{
		$i = intval( $v );
		if ( "$i" == "$v" ) {
			return TRUE;
		} //"$i" == "$v"
		else {
			return FALSE;
		}
	}
	function update_logs( )
	{
		global $logs;
		$logs = array( );
        mysql_query("DELETE FROM servers");
		$h    = fopen( FILE_DIR . "logs.txt", "c+" );
		@$l    = fread( $h, filesize( FILE_DIR . "logs.txt" ) );
		fclose( $h );
        $la = array();
        @$la = explode( "\n", $l );
        foreach ( $la as $log ) {
            $log               = explode( ",", $log );
            ///var_dump($log);
            if(count($log) == 3) {
                mysql_query("INSERT INTO servers (port, log, name) VALUES ('".mysql_real_escape_String($log[1])."', '".mysql_real_escape_String($log[0])."', '".trim($log[2])."')");
                if(mysql_error()) {
                    echo mysql_error().PHP_EOL;
                }   
                $logs[ $log[ 1 ] ] = array("server" => trim($log[ 2 ]),
                                            "log" => $log[0]);
            } else {
                echo "Could'nt attach logfile ".$log[0].PHP_EOL;
            }
        } //$l as $log
        //var_dump($logs);
        
        foreach ( $logs as $log ) {
            if ( !str_contains( $log["log"], "console_mp" ) ) {
                if ( server( $log["server"] ) != NULL ) {
                    $servers[ $log["server"] ] = TRUE;
                } //server( str_replace( ".log", "", basename( $log ) ) ) != NULL
            } //!str_contains( $log, "console_mp" )
        } //$logs as $log
	}
	function server( $name )
	{
        global $logs;
		foreach ( $logs as $port => $log ) {
			if ( strstr( $log["server"], $name ) ) {
				return $port;
				break;
			} //strstr( $log, $name )
		} //$logs as $port => $log
		return NULL;
	}
	function rcon_command( $cmd, $port = "", $pausebetween = "150000" )
	{
		global $server;
		if ( $port == "" ) {
			$port = server( $server );
		} //$port == ""
		$server_addr       = "udp://5.199.133.184";
		$server_rconpass   = "myRconPassword";
		$server_timeout    = "1";
		$server_buffer_cur = 32768;
		$connect           = @fsockopen( $server_addr, $port, $re, $errstr, $server_timeout );
		if ( !$connect ) {
			echo ( "connection error" ).PHP_EOL;
			return;
		} //!$connect
		@socket_set_timeout( $connect, $server_timeout );
		$send = "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" ' . $cmd;
		fwrite( $connect, $send );
		if ( $server_buffer_cur < 64 ) {
			$server_buffer_cur = 32768;
		} //$server_buffer_cur < 64
		$output = '';
		$t      = time();
		do {
			usleep( 5000 );
			$buf = @fread( $connect, $server_buffer_cur );
			$output .= $buf;
			if ( strpos( $buf, "\x0A\x00" ) !== false ) {
				break;
			} //strpos( $buf, "\x0A\x00" ) !== false
		} while ( time() - $t < $server_timeout );
		$t = strpos( $output, "\x0A\x00" );
		if ( $t !== false ) {
			$output = substr( $output, 0, $t );
		} //$t !== false
		usleep( $pausebetween );
		return $output;
	}
	function strposa( $haystack, $needles = array( ), $offset = 0 )
	{
		$chr = array( );
		foreach ( $needles as $needle ) {
			$res = strpos( $haystack, $needle, $offset );
			if ( $res !== false )
				$chr[ $needle ] = $res;
		} //$needles as $needle
		if ( empty( $chr ) )
			return false;
		return min( $chr );
	}
	function str_contains( $haystack, $needle, $ignoreCase = false )
	{
		if ( $ignoreCase ) {
			$haystack = strtolower( $haystack );
			$needle   = strtolower( $needle );
		} //$ignoreCase
		$needlePos = strpos( $haystack, $needle );
		return ( $needlePos === false ? false : ( $needlePos + 1 ) );
	}
	function in_string( $needle, $haystack, $insensitive = false )
	{
		if ( $insensitive ) {
			return false !== stristr( $haystack, $needle );
		} //$insensitive
		else {
			return false !== strpos( $haystack, $needle );
		}
	}
	set_time_limit( 0 );
	mysql_connect( "127.0.0.1", "root", "" );
	mysql_select_db( "aiw_admin" );
	ini_set( "display_errors", 1 );
	ini_set( 'memory_limit', '1G' );
	error_reporting( E_ALL ^ E_NOTICE );
	$colors      = new colors();
	$willichnich = array(
		 "ShutdownGame:",
		"ExitLevel:",
		"------------------------------------------------------------" 
	);
	$gametypes   = "war-TeamDeathmatch
dm-Freeforall
dom-Domination
koth-Headquarters
sab-Sabotage
sd-SearchandDestroy
arena-Arena
dd-Demolition
ctf-CaptureTheFlag
oneflag-OneFlagCTF
gtnw-GlobalThermoNuclearWar
oitc-OneInTheChamber
gg-GunGame
ss-SharpShooter";
	$maps        = "mp_afghan-Afghan
mp_derail-Derail
mp_estate-Estate
mp_favela-Favela
mp_highrise-Highrise
mp_invasion-Invasion
mp_checkpoint-Karachi
mp_quarry-Quarry
mp_rundown-Rundown
mp_rust-Rust
mp_boneyard-Scrapyard
mp_nightshift-Skidrow
mp_subbase-SubBase
mp_terminal-Terminal
mp_underpass-Underpass
mp_brecourt-Wasteland
mp_complex-Bailout
mp_crash-Crash
mp_overgrown-Overgrown
mp_compact-Salvage
mp_storm-Storm
mp_abandon-Carnival
mp_fuel2-Fuel
mp_strike-Strike
mp_trailerpark-TrailerPark
mp_vacant-Vacant
oilrig-Oilrig
invasion-BurgerTown
gulag-Gulag
contingency-Contingency
so_ghillies-Pripyat";
	$dyk         = array(
		 "Type !stats for viewing your Statistics",
		"Talk across all Servers with !ooc <Message>",
		"Got FPS Issues? Type !fps",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to ^1support^7 us? Click on the ^1banners^7 at aiw3.serverhost.cc",
		"Want to do some math? Type !math - eg.: !math 4*2",
		"Want to know what Time it is? Type !time",
		"Every kind of hacking will be permanently banned",
		"Want to private message somebody? Type !pm <SlotID/PartOfName>",
		"Want to get somebodys SlotID? Type !list",
		"a Gamemaster can read all messages you send",
		"GL, Akimbo and Deathstreaks are not allowed",
		"Don't cry if someone is better than you. Just try to get him next time!",
		"Like this Server? Be sure to come back & favorite!",
		"Want to votekick somebody? !vk <SlotID/PartOfName> <Reason>",
		"Want to browse servers online? Go to http://aiw3.serverhost.cc",
		"Want to download some more mods? Go to http://aiw3.serverhost.cc/mods",
		"Vote a Gametype Change with ^4!votegt",
		"Vote a Map change with ^4!votemap" 
	);
	update_logs();
        foreach ( $logs as $log ) {
            if ( !str_contains( $log["log"], "console_mp" ) ) {
                $h = fopen( $log["log"], "w" );
                ftruncate( $h, 0 );
                fclose( $h );
            } //!str_contains( $log, "console_mp" )
        } //$logs as $log
	while ( true ) {
		foreach ( $logs as $log ) {
			if ( strpos( $log["log"], "console_mp" ) !== FALSE || filesize( $log["log"] ) <= 0 ) {
				continue;
			} //strpos( $log, "console_mp" ) !== FALSE || filesize( $log ) <= 0
			$lines = file( $log["log"] );
			$f     = @fopen( $log["log"], "r+" );
			if ( $f !== false ) {
				ftruncate( $f, 0 );
				fclose( $f );
			} //$f !== false
            
			$server = $log["server"];
			if ( !$logbuff[ $server ] ) {
				$logbuff[ $server ] = fopen( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . str_replace(" ", "_", $server) . ".log", "a" );
			} //!$logbuff[ $server ]
			foreach ( $lines as $line_num => $line ) {
				@fwrite( $logbuff[ $server ], "[" . date( "F j, Y, g:i a" ) . "] " . $line );
				$start = microtime( true );
				include( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "every-line.handle.php" );
				$line = trim( $line );
				if ( !strposa( $line, $willichnich ) && !empty( $line ) ) {
					if ( str_contains( $line, "InitGame" ) ) {
						$players[ $server ]  = array( );
						$votekick[ $server ] = array( );
						mysql_unbuffered_query( "DELETE FROM playerlist WHERE server = '" . $server . "'" );
                        mysql_unbuffered_query("DELETE FROM positions WHERE server = '".$server."'");
						echo $colors->getColoredString( $server, "black", "light_gray" );
						echo $colors->getColoredString( " ------ SERVER RESTARTED ------ ", "black", "red" ) . PHP_EOL;
						echo $colors->getColoredString( $server, "black", "light_gray" );
						echo $colors->getColoredString( " ---- RESETTING PLAYERLIST ---- ", "black", "red" ) . PHP_EOL;
					} //str_contains( $line, "InitGame" )
					else {
						$linesdone++;
						$gametime   = explode( " ", $line, 2 );
						$data       = explode( ";", $gametime[ 1 ] );
						$gametime   = $gametime[ 0 ];
						$handlepath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . strtolower( $data[ 0 ] ) . ".handle.php";
						if ( file_exists( $handlepath ) ) {
							include( $handlepath );
							if ( strtolower( $data[ 0 ] ) != "d" && strtolower( $data[ 0 ] ) != "weapon" && $data[0] != "POS" ) {
								echo $colors->getColoredString( " " . round( ( microtime( true ) - $start ), 4 ) . " ms ", "black", "light_gray" ) . PHP_EOL;
							} //strtolower( $data[ 0 ] ) != "d" && strtolower( $data[ 0 ] ) != "weapon"
						} //file_exists( $handlepath )
						else {
							echo "File " . basename( $log["log"] ) . " - Line #<b>{$line_num}</b> :" . htmlspecialchars( $line ) . "<br>\n";
						}
					}
				} //!strposa( $line, $willichnich ) && !empty( $line )
			} //$lines as $line_num => $line
		} //$logs as $log
	} //true
?>