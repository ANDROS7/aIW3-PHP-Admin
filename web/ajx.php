<?php
    require_once(dirname(__FILE__)."\\functions.php");
    ini_set("display_errors", 0);
    if(isset($_GET["chat_message"])) {
        if(empty($_GET["chat_message"])) {
            exit(json_encode(array("status" => "error", "reply" => "no message")));
        }
        
        if(strlen($_GET["chat_message"]) > 40) {
            exit(json_encode(array("status" => "error", "reply" => "too long")));
        }
        
        $servers_playing = mysql_query("SELECT server FROM playerlist");
        while($r = mysql_fetch_object($servers_playing)) {
            if(!in_array($r->server, $done)) {
                rcon_command("say \"^0[^3".strtoupper($_SERVER["PHP_AUTH_USER"])."^0]^7: ".$_GET["chat_message"]."\"", server($r->server));
                $done[] = $r->server;
            }
        }
        exit(json_encode(array("status" => "done", "reply" => "Message sent")));
    } elseif(isset($_GET["chat"])) {
        $lastchat = mysql_query("SELECT server, message, time, guid, name, team FROM chat_logs ORDER BY time DESC LIMIT 50");
        while($r = mysql_fetch_array($lastchat)) {
            $r["rank"] = mysql_fetch_array(mysql_query("SELECT rank FROM players WHERE guid = '".$r["guid"]."' LIMIT 1"));
            $r["rank"] = (int)$r["rank"]["rank"];
            $r["server"] = strtoupper($r["server"]);
            $r["message"] = utf8_encode($r["message"]).($r["team"] == "team" ? " (TEAM)" : "");
            $r["time"] = date("H:i, d.m", strtotime($r["time"]));
            $out[] = $r;
        }
        @exit(json_encode($out));
    } elseif(isset($_GET["servers"])) {
        $q = mysql_query("SELECT * FROM playerlist ORDER BY slotid ASC");
        while($r = mysql_fetch_array($q)) {
            $r["server"] = str_replace(" ", "_", $r["server"]);
            if(!$r["guid"]) {
                continue;
            }
            $playerdata = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE guid = '".$r["guid"]."' LIMIT 1"));
            if(!is_array($playerdata))
                continue;
            $playerdata["slotid"] = $r["slotid"];
            $playerdata["playtime_t"] = sprintf("%02d%s%02d%s%02ds", floor($playerdata["playtime_sec"]/3600), "h ", ($playerdata["playtime_sec"]/60)%60, "m ", ($playerdata["playtime_sec"]%60) . "s");
            $playerdata["playtime"] = sprintf("%02d%s%02ds", ($r["playtime"]/60)%60, "m ", ($r["playtime"]%60) . "s");
            $out[$r["server"]][] = $playerdata;
            $out[$r["server"]]["server"] = strtoupper($r["server"]);  
            @$out[$r["server"]]["total"]++;
        }
        @exit(json_encode($out));
    } elseif(isset($_GET["overall"])) {
        $res = mysql_query("SELECT id FROM players");
        $out["Total Players"] = mysql_num_rows( $res );
        $res = mysql_query("SELECT entry FROM chat_logs");
        $out["Total Messages"] = mysql_num_rows( $res );
        $res = mysql_query( "SELECT sum(damage) FROM weapon_player_stats");
        $out["Total Damage"] = mysql_fetch_array( $res );
        $out["Total Damage"] = $out["Total Damage"][0]." HP";
        $res = mysql_query( "SELECT sum(playtime_sec) FROM players");
        $anzahl = mysql_fetch_array( $res );
        $out["Total Playtime (h:m:s)"] = sprintf("%02d%s%02d%s%02d", floor($anzahl[0]/3600), ":", ($anzahl[0]/60)%60, ":", $anzahl[0]%60);
        $anzahl[0] = ($anzahl[0] / $out["Total Players"]);
        $out["Playtime Ø"] = sprintf("%02d%s%02d%s%02d", floor($anzahl[0]/3600), ":", ($anzahl[0]/60)%60, ":", $anzahl[0]%60);
        $res = mysql_query("SELECT entry FROM bans");
        $out["Players banned"] = mysql_num_rows( $res );
        $res = mysql_query("SELECT slotid FROM playerlist");
        $out["Players playing"] = mysql_num_rows( $res );
        @exit(json_encode($out));
    }

?>