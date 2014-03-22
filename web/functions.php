<?
    session_start();
    if(isset($_GET["deleteall"])){
        unset($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_USER"]);
        exit();
    }
	if (!isset($_SERVER['PHP_AUTH_PW']) || !isset($_SERVER['PHP_AUTH_USER'])) {
		if(!headers_sent()){
			header('WWW-Authenticate: Basic realm="Login"');
			header('HTTP/1.0 401 Unauthorized');
		}else{
			exit;
		}

	} elseif ($_SERVER['PHP_AUTH_PW'] != 'myawesomepassword'){
			if(!headers_sent()){
				header('HTTP/1.0 401 Unauthorized');
			}
			exit();
	}
    
    mysql_connect("127.0.0.1", "root", "");
    mysql_select_db("database");

    function alert($type, $msg) {
        echo "<div class=\"alert alert-".$type."\">";
        echo $msg;
        echo "</div>";
    }
    
    function server($name) {
        $server = mysql_query("SELECT port FROM servers WHERE name = '".$name."'");
        if(mysql_num_rows($server) != 1) {
            return null;
        }
        $server = mysql_fetch_array($server);
        return $server["port"];
    }
	function rcon_command( $cmd , $port = "", $pausebetween = "150000")
	{
        global $server;
        if($port == "") {
            $port = server($server);
        }
        $server_addr = "udp://5.199.133.184";
        $server_rconpass = "i7r32lww";
        $server_timeout = "1";
        $server_buffer_cur = 32768;
        
        $connect = @fsockopen($server_addr, $port, $re, $errstr, $server_timeout);
        if (! $connect)
            { echo("connection error"); return; }

        @socket_set_timeout ($connect, $server_timeout); //some servers block this command, silently ignore exception
        
        $send = "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" '.$cmd;
		//$send = "\xff\xff\xff\xff" . 'rcon i7r32lww;rcon ' . $cmd;

		fwrite( $connect, $send );
		if ( $server_buffer_cur < 64 )
		{
			$server_buffer_cur = 32768;
		} //$server_buffer_cur < 64
		$output = '';
		$t      = time();
		do
		{
			usleep( 5000 );
			$buf = @fread( $connect, $server_buffer_cur );
			$output .= $buf;
			if ( strpos( $buf, "\x0A\x00" ) !== false )
			{
				break;
			} //strpos( $buf, "\x0A\x00" ) !== false
		} while ( time() - $t < $server_timeout );
		$t = strpos( $output, "\x0A\x00" );
		if ( $t !== false )
		{
			$output = substr( $output, 0, $t );
		} //$t !== false
		// 		file_put_contents('oo.bin',$output);
        usleep($pausebetween);
		return $output;
	}
?>