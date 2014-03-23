<?php
function push($header, $message, $priority = 0)
{
    $fields = array(
        'apikey' => urlencode("50d69727b39076ccf70475af70e1f9780b9c598c"),
        'application' => urlencode("aIW3 Bot"),
        'event' => urlencode($header),
        'priority' => $priority,
        'description' => urlencode($message)
    );
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    } //$fields as $key => $value
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.prowlapp.com/publicapi/add");
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    $result = curl_exec($ch);
    curl_close($ch);
    $fields        = array(
        "token" => "ubDHRfDBcBALCBbqjtKfZ5p1MCYoC5",
        "user" => "a3GnedbRnzHY3Ldgr8gjrphMJZ2Dp4",
        "title" => urlencode($header),
        "message" => urlencode($message)
    );
    $fields_string = "";
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    } //$fields as $key => $value
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.pushover.net/1/messages.json");
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    curl_close($ch);
}
function diff_time($differenz, $time = "")
{
    if ($differenz == NULL || empty($differenz) || is_null($differenz))
        return " never ";
    if ($time == "")
        $time = time();
    $r         = "";
    $differenz = $time - $differenz;
    $tag       = floor($differenz / (3600 * 24));
    $std       = floor($differenz / 3600 % 24);
    $min       = floor($differenz / 60 % 60);
    $sek       = floor($differenz % 60);
    if ($tag != 0) {
        return $tag . " day" . ($tag != "1" ? "s" : "") . " ago ";
    } //$tag != 0
    if ($std != 0) {
        return $std . " hour" . ($std != "1" ? "s" : "") . " ago ";
    } //$std != 0
    if ($min != 0) {
        return $min . " min" . ($min != "1" ? "s" : "") . " ago ";
    } //$min != 0
    if ($sek != 0) {
        return $sek . " sec" . ($sek != "1" ? "s" : "") . " ago ";
    } //$sek != 0
}
function is_integer2($v)
{
    $i = intval($v);
    if ("$i" == "$v") {
        return TRUE;
    } //"$i" == "$v"
    else {
        return FALSE;
    }
}
function update_logs()
{
    global $logs;
    global $config;
    $logs = array();
    mysql_query("DELETE FROM servers");
    
    for($x = 1; $x<=($config->count()-1); $x++) {
        if($config->has("server_".$x, "logfile") && $config->has("server_".$x, "alias") && $config->has("server_".$x, "port")) {
            $data = $config->getSection("server_".$x);
            if(!in_array($data["port"], $logs)) {
                if(!file_exists($data["logfile"])) {
                    echo "Logfile of ".$data["alias"]." isn't accessible".PHP_EOL;
                    continue;
                }
            }
            // set up a new rcon instance
            $rcon[$data["port"]] = new rcon($config->get("main", "rcon_host_ip"), $data["port"], $config->get("main", "rcon_password"), 1);
            if($rcon[$data["port"]]){
                $logs[$data["port"]] = array(   "server"    => $data["alias"],
                                                "log"       => $data["logfile"]);
                                                        
                mysql_query("INSERT INTO servers (port, log, name) VALUES ('".$data["port"]."', '".mysql_real_escape_string($data["logfile"])."', '".mysql_real_escape_string($data["alias"])."')");
                echo "Added server ".$data["alias"]." (N. ".$x.")".PHP_EOL;
            } else {
                echo "Server ".$x." couldnt be added".PHP_EOL;
            }
        } else {
            echo "Server ".$x." couldnt be added".PHP_EOL;
        }
    }
    
    foreach ($logs as $log) {
        if (server($log["server"]) != NULL) {
            $servers[$log["server"]] = TRUE;
        } //server( str_replace( ".log", "", basename( $log ) ) ) != NULL
    } //$logs as $log
}
function server($name)
{
    global $logs;
    foreach ($logs as $port => $log) {
        if (strstr($log["server"], $name)) {
            return $port;
            break;
        } //strstr( $log, $name )
    } //$logs as $port => $log
    return NULL;
}
function rcon_command($cmd, $port = "", $pausebetween = "150000")
{
    global $server;
    if ($port == "") {
        $port = server($server);
    } //$port == ""
    $server_addr       = "udp://5.199.133.184";
    $server_rconpass   = "myRconPassword";
    $server_timeout    = "1";
    $server_buffer_cur = 32768;
    $connect           = @fsockopen($server_addr, $port, $re, $errstr, $server_timeout);
    if (!$connect) {
        echo ("connection error") . PHP_EOL;
        return;
    } //!$connect
    @socket_set_timeout($connect, $server_timeout);
    $send = "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" ' . $cmd;
    fwrite($connect, $send);
    if ($server_buffer_cur < 64) {
        $server_buffer_cur = 32768;
    } //$server_buffer_cur < 64
    $output = '';
    $t      = time();
    do {
        usleep(5000);
        $buf = @fread($connect, $server_buffer_cur);
        $output .= $buf;
        if (strpos($buf, "\x0A\x00") !== false) {
            break;
        } //strpos( $buf, "\x0A\x00" ) !== false
    } while (time() - $t < $server_timeout);
    $t = strpos($output, "\x0A\x00");
    if ($t !== false) {
        $output = substr($output, 0, $t);
    } //$t !== false
    usleep($pausebetween);
    return $output;
}
function strposa($haystack, $needles = array(), $offset = 0)
{
    $chr = array();
    foreach ($needles as $needle) {
        $res = strpos($haystack, $needle, $offset);
        if ($res !== false)
            $chr[$needle] = $res;
    } //$needles as $needle
    if (empty($chr))
        return false;
    return min($chr);
}
function str_contains($haystack, $needle, $ignoreCase = false)
{
    if ($ignoreCase) {
        $haystack = strtolower($haystack);
        $needle   = strtolower($needle);
    } //$ignoreCase
    $needlePos = strpos($haystack, $needle);
    return ($needlePos === false ? false : ($needlePos + 1));
}
function in_string($needle, $haystack, $insensitive = false)
{
    if ($insensitive) {
        return false !== stristr($haystack, $needle);
    } //$insensitive
    else {
        return false !== strpos($haystack, $needle);
    }
}
?>