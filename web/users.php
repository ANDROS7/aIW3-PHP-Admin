<?php
    require_once(dirname(__FILE__)."\\functions.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Administration - x4</title>
        <link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/lumen/bootstrap.min.css" rel="stylesheet">
        <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <style>
            .row {
                padding-top: 65px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="#">x4 Administration</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                        <li><a href="index.php">Overview</a></li>
                        <li class="active"><a href="users.php">Users</a></li>
                        <li><a href="bans.php">Bans</a></li>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if(isset($_GET["ban"]) || isset($_GET["kick"])) {
                        if(isset($_GET["ban"])) {
                            $checkforbanned = mysql_fetch_array(mysql_query("SELECT banned FROM players WHERE guid = '".mysql_real_escape_string($_GET["ban"])."' LIMIT 1"));
                            if($checkforbanned["banned"] == FALSE) {
                                $q = mysql_query("UPDATE players SET banned = TRUE WHERE guid = '".mysql_real_escape_string($_GET["ban"])."'");
                                $username = mysql_fetch_array(mysql_query("SELECT name FROM players WHERE guid = '".mysql_real_escape_String($_GET["ban"])."' LIMIT 1"));
                                mysql_query("INSERT INTO bans (banned_by, name, reason, guid) VALUES ('".$_SERVER['PHP_AUTH_USER']."', '".$username["name"]."', 'Banned through Web', '".mysql_real_escape_string($_GET["ban"])."')");
                                if($q) {
                                    $ison = mysql_fetch_array(mysql_query("SELECT server, slotid FROM playerlist WHERE guid = '".mysql_real_escape_string($_GET["ban"])."' LIMIT 1"));
                                    if($ison && $ison["server"]) {
                                        $rcon = rcon_command("clientkick ".$ison["slotid"]." \"You have been banned by an Gamemaster\"", server($ison["server"]));
                                    }
                                    
                                    alert("success", "Player banned <br /><a href=\"users.php?guid=".$_GET["ban"]."\">Go back</a>");
                                } else {
                                    alert("danger", "Something went wrong. Player was not banned<br /><a href=\"users.php?guid=".$_GET["ban"]."\">Go back</a>");
                                }
                            } else {
                                $q = mysql_query("UPDATE players SET banned = FALSE WHERE guid = '".mysql_real_escape_string($_GET["ban"])."'");
                                mysql_query("DELETE FROM bans WHERE guid = '".mysql_real_escape_string($_GET["ban"])."'");
                                if($q) {
                                    alert("success", "Player unbanned<br /><a href=\"users.php?guid=".$_GET["ban"]."\">Go back</a>");
                                } else {
                                    alert("danger", "Something went wrong. Player was not banned<br /><a href=\"users.php?guid=".$_GET["ban"]."\">Go back</a>");
                                }
                            }
                        } else {
                            @$ison = mysql_fetch_array(mysql_query("SELECT server, slotid FROM playerlist WHERE guid = '".mysql_real_escape_string($_GET["kick"])."' LIMIT 1"));
                            if(is_array($ison) && isset($ison["server"]) && isset($ison["slotid"])) {
                                $rcon = rcon_command("clientkick ".$ison["slotid"]." \"You have been kicked by an Gamemaster\"", server($ison["server"]));
                                alert("success", "Player was kicked out of ".$ison["server"]);
                            } else {
                                alert("danger", "Player was not found online.");
                            }
                        }
                    } elseif(isset($_POST["search"])) {
                        $users = mysql_query("SELECT guid, name FROM players WHERE name LIKE '%".$_POST["search"]."%'");
                        if(mysql_num_rows($users) <= 0) {
                            alert("danger", "No users found");
                        } else {
                        ?>
                        <ul class="list-group">
                        <?php
                            while($r = mysql_fetch_object($users)) {
                            ?>
                                <a href="users.php?guid=<?=$r->guid?>" class="list-group-item"><?=$r->name?> (<?=$r->guid?>)</a>
                            <?php
                            }
                        }
                        ?>
                        </ul>
                        <?php
                    } elseif(isset($_GET["guid"])) {
                        $userdata = mysql_query("SELECT * FROM players WHERE guid = '".mysql_real_escape_string($_GET["guid"])."' LIMIT 1") or alert("danger", mysql_error());
                        if(mysql_num_rows($userdata) == 0) {
                            alert("danger", "User not found");
                        } else {
                            $userdata = mysql_fetch_object($userdata);
                            ?>
                            <div class="col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <?=htmlentities($userdata->name)?>
                                    </div>
                                    <div class="panel-body">
                                        <p>ID<span class="label label-primary pull-right">#<?=$userdata->id?></label></p>
                                        <p>GUID<span class="label label-primary pull-right"><?=$userdata->guid?></label></p>
                                        <p>Name (last)<span class="label label-primary pull-right"><?=$userdata->name?></label></p>
                                        <p>Banned<span class="label label-primary pull-right"><?=($userdata->banned == 1 ? "true" : "false")?></label></p>
                                        <p>first / last connect<span class="label label-primary pull-right"><?=$userdata->joined?> / <?=$userdata->last?></label></p>
                                        <p>Connections<span class="label label-primary pull-right"><?=$userdata->connections?></label></p>
                                        <p>Playtime (H:M:S)<span class="label label-primary pull-right"><?=sprintf("%02d%s%02d%s%02d", floor($userdata->playtime_sec/3600), ":", ($userdata->playtime_sec/60)%60, ":", $userdata->playtime_sec%60);?></label></p>
                                    </div>
                                    <div class="panel-footer">
                                        <?php
                                        if($userdata->banned) { ?>
                                        <button class="btn btn-success" onClick="parent.location='users.php?ban=<?=$userdata->guid?>'"><i class="fa fa-circle-o"></i> Unban user</button>
                                        <?php } else {
                                        ?>
                                        <button class="btn btn-danger" onClick="parent.location='users.php?ban=<?=$userdata->guid?>'"><i class="fa fa-ban"></i> Ban user</button>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        Latest chat messages
                                    </div>
                                    <div class="panel-body">
                                            <?php
                                            $chats = mysql_query("SELECT * FROM chat_logs WHERE guid = '".$userdata->guid."' ORDER BY time DESC LIMIT 25");
                                            if(mysql_num_rows($chats) <= 0) {
                                                alert("warning", "No chat messages");
                                            }
                                            while($r = mysql_fetch_object($chats)) {
                                                echo "<p>".utf8_encode($r->message)." <span class=\"badge pull-right\">".date("H:i, d.m.y", strtotime($r->time))."</span>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        Weapon statistics
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                            $playerdata["kills"] = mysql_fetch_object(mysql_query("SELECT * FROM player_stats WHERE guid = '".$userdata->guid."' LIMIT 1")); ?>
                                            <p><span style="float: right; display: inline;"><?=@(int)$playerdata["kills"]->kills?> Kills</span> <span class="float: right;"><?=@(int)$playerdata["kills"]->deaths?> Deaths</span></p>
                                            <p> <center><?=@round(($playerdata["kills"]->kills / $playerdata["kills"]->deaths),2)?> KDR</center></p>
                                            <?php
                                            $weapondata = mysql_query("SELECT * FROM weapon_player_stats WHERE guid = '".$userdata->guid."' ORDER BY kills DESC");
                                            while($r = mysql_fetch_object($weapondata)) {
                                                if($r->weapon == "none")
                                                    continue;
                                                    
                                                echo "<p>".$r->weapon." <span class=\"badge pull-right\">".ucfirst($r->kills)." Kills</span>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                    ?>
                        <form method="POST">
                            <input type="text" class="form-control" placeholder="Search for Username (press Enter to search)" name="search">
                        </form>
                        <table class="table table-hover table-striped">
                            <thead>
                                <th width="20">#</td>
                                <th>Player</th>
                                <th>GUID</th>
                                <th>Connections</th>
                                <th>Last connect</th>
                            </thead>
                            <tbody>
                                <?php
                                    $players = mysql_query("SELECT * FROM players ORDER BY last DESC LIMIT 100");
                                    while($r = mysql_fetch_object($players)) {
                                        ?>
                                        <tr>
                                            <td><?=$r->id?></td>
                                            <td><a href="users.php?guid=<?=$r->guid?>"><?=$r->name?></a></td>
                                            <td><?=$r->guid?></td>
                                            <td><?=$r->connections?></td>
                                            <td><?=$r->last?></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>