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
                        <li><a href="users.php">Users</a></li>
                        <li class="active"><a href="bans.php">Bans</a></li>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed table-hover table-striped">
                        <thead>
                            <th width="25" style="text-align: center;">#</th>
                            <th width="150" style="text-align: center;">GUID</th>
                            <th>Username</th>
                            <th>banned by</th>
                            <th>Reason</th>
                            <th width="130">Time</th>
                        </thead>
                        <tbody>
                            <?php
                            $q = mysql_query("SELECT * FROM bans ORDER BY time DESC");
                            while($r = mysql_fetch_array($q)) {
                            ?>
                                <tr>
                                    <td><?=$r["entry"]?></td>
                                    <td><?=$r["guid"]?></td>
                                    <td><a href="users.php?guid=<?=$r["guid"]?>"><?=$r["name"]?></a></td>
                                    <td><?=$r["banned_by"]?></td>
                                    <td><?=$r["reason"]?></td>
                                    <td><?=$r["time"]?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>