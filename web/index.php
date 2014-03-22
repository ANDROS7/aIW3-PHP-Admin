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
        <script type="text/javascript">
        function get_last_chats() {
            $.getJSON( "ajx.php",{ chat: "" }, function( data ) {
                var items = [];
                $.each( data, function( key, val ) {
                    items.push("<p><a href=\"users.php?guid="+val.guid+"\">"+val.name+"</a> ("+val.server+"): "+val.message+" <span class=\"label label-default pull-right\">"+val.time+"</span></p>")
                });
                $('#chat').html(items);
            });
            setTimeout('get_last_chats()', 1000);
        }
        
        function get_overall() {
            $.getJSON( "ajx.php",{ overall: "" }, function( data ) {
                var items = [];
                $.each( data, function( key, val ) {
                    items.push("<p>"+key+" <span class=\"label label-primary pull-right\">"+val+"</span></p>")
                });
                
                $('#overall').html(items);
            });
            setTimeout('get_overall()', 5000);
        }
        
        function get_servers() {
            $.getJSON( "ajx.php",{ servers: "" }, function( data ) {
                $('#servers').html("");
                $.each( data, function( server, dataa ) {
                    $('#servers').append("<div class=\"panel panel-default\"><div class=\"panel-heading\">"+dataa.server+" ("+dataa.total+" Players)</div><div class=\"panel-body\"><table class=\"table table-hover table-striped table-condensed\" id=\""+dataa.server+"\"><thead><th width=\"10\">SlotID</th><th width=\"150\">Name</th><th width=\"600\">GUID</th><th style=\"text-align: center;\" width=\150\">Playtime total</th><th style=\"text-align: center;\">Playtime playing</th><th width=\"110\"></th></thead><tbody></tbody></table></div></div>");
                    $.each(dataa, function(key, val) {
                        if($.isPlainObject(val)) {
                            if(val.rank > 2) {
                                $('#'+dataa.server+" > tbody").append("<tr class=\"info\"><td>"+val.slotid+"</td><td><a href=\"users.php?guid="+val.guid+"\">"+val.name+"</a></td><td>"+val.guid+"</td><td style=\"text-align: center;\">"+val.playtime_t+"</td><td style=\"text-align: center;\">"+val.playtime+"</td><td></td></tr>");
                            } else {
                                $('#'+dataa.server+" > tbody").append("<tr><td>"+val.slotid+"</td><td><a href=\"users.php?guid="+val.guid+"\">"+val.name+"</a></td><td>"+val.guid+"</td><td style=\"text-align: center;\">"+val.playtime_t+"</td><td style=\"text-align: center;\">"+val.playtime+"</td><td><button class=\"btn btn-warning btn-xs\" role=\"button\" onClick=\"parent.location='users.php?kick="+val.guid+"'\">kick</button> <button onClick=\"parent.location='users.php?ban="+val.guid+"'\" class=\"btn btn-danger btn-xs\">ban</button></td></tr>");
                            }
                            //console.log("added player "+val.name+" to "+dataa.server);
                        }
                    });
                });
            });
            setTimeout('get_servers()', 1000);
        }
        $( document ).ready(function() {
            get_last_chats();
            get_overall();
            get_servers();
            
            //*** CHAT BUTTON ***//
            $('#chat_button').click(function() {
                var message = $('#chat_text').val();
                $('#chat_text').val("");
                
                $.getJSON( "ajx.php",{ chat_message: message }, function( data ) {
                        alert(data.reply);
                });
            });
        });
        </script>
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
                        <li class="active"><a href="index.php">Overview</a></li>
                        <li><a href="users.php">Users</a></li>
                        <li><a href="bans.php">Bans</a></li>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <div class="row">
                <?php
                $players = mysql_query("SELECT COUNT(*) FROM playerlist");
                $players = mysql_fetch_array($players);
                
                 $res             = mysql_query( "SELECT `server`, COUNT(`server`) AS `anzahl` FROM `playerlist` GROUP BY `server`" );
                 $servers            = array( );
                 while ( $row = mysql_fetch_assoc( $res ) ) {
                  $servers[ $row[ 'server' ] ] = $row[ 'anzahl' ];
                 } //$row = mysql_fetch_assoc( $res )
                ?>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Overall
                        </div>
                        <div class="panel-body" id="overall">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Chat
                        </div>
                        <div class="panel-body">
                            <div id="chat" class="pre-scrollable" style="max-height: 150px;"></div>
                        </div>
                        <div class="panel-footer">
                            <div class="input-group">
                              <input type="text" class="form-control" id="chat_text" maxlength="40" size="40">
                              <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="chat_button">Send to Game (as <?=$_SERVER["PHP_AUTH_USER"]?>)</button>
                              </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="servers"></div>
            </div>
        </div>
    </body>
</html>