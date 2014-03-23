<?php
    define("FILE_DIR", str_replace( "handles_admin", "", dirname( __FILE__ ) ));
    require_once(FILE_DIR."/classes/functions.php");
    require_once(FILE_DIR."/classes/rcon.class.php");
    
    //** Command prompt colors - maybe remove that later, only support in *nix **//
    require_once(FILE_DIR."/classes/colors.class.php");
    $colors = new colors();
    //**                                                                       **//
    
    require_once(FILE_DIR."/classes/Config/Lite.php");
    $config = new Config_Lite(FILE_DIR."/settings.conf", LOCK_EX);
    
    if(!file_exists(FILE_DIR."/settings.conf")) {
        if(!$config->hasSection("main")) {
            $config->setSection("main", array(  "rcon_host_ip" => "127.0.0.1",
                                                "rcon_password" => "miawesomrc0n",
                                                "votekick"  => "no",
                                                "votemap"  => "no",
                                                "votegametype"  => "no",
                                                "ooc" => "no"));
            echo "created default Configration".PHP_EOL;
        }
        if(!$config->hasSection("mysql")) {
            $config->setSection("mysql", array( "host"      => "localhost",
                                                "username"  => "root",
                                                "password"  => "password",
                                                "database"  => "database"));
            echo "created default MySQL-Section".PHP_EOL;
        }
        if(!$config->hasSection("server_1")) {
            $config->setSection("server_1", array("logfile" => "path_to_log1.log", "port" => 0, "alias" => "Server name"));
            echo "created default Server 1".PHP_EOL;
        }
        if(!$config->hasSection("server_2")) {
            $config->setSection("server_2", array("logfile" => "path_to_log2.log", "port" => 0, "alias" => "Server name"));
            echo "created default Server 2".PHP_EOL;
        }
        $config->save();
        exit("Created default configuration. Please edit the configuration for your needs. (settings.conf)").PHP_EOL;
    } else {
        require_once(FILE_DIR."/classes/mysql.php"); // establish a connection after default values are there
    }
    
    if(empty($logs)) {
        echo "No servers available.. aborting start".PHP_EOL;
        exit();
    }
    
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
    
    // ** empty all log files before continuing ** //
    foreach ( $logs as $log ) {
        if ( !str_contains( $log["log"], "console_mp" ) ) {
            $h = fopen( $log["log"], "w" );
            ftruncate( $h, 0 );
            fclose( $h );
        } //!str_contains( $log, "console_mp" )
    } //$logs as $log
    // **                                        ** //
    
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