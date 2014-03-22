<?php
    //var_dump($data);
    /**
    echo $colors->getColoredString( $server, "black", "light_gray" );
    echo $colors->getColoredString( $data[ 3 ] . " (" . $data[ 2 ] . ") >> " . $data[ 4 ], "black", "blue" ) . PHP_EOL;
    **/
    
    /**
    if(!is_dir(dirname(__FILE__)."\\positions\\")) {
        mkdir(dirname(__FILE__)."\\positions\\");
    }
    
    if(!$poslogh[$server]) {
        $poslogh[$server] = fopen(dirname(__FILE__)."\\positions\\".$server.".pos", "c+");
    }
    
    $poslogd = unserialize(fread($poslogh[$server], filesize(dirname(__FILE__)."\\positions\\".$server.".pos")));
    $poslogd[$data[1]] = array( "name" => $data[2],
                                        "team" => $data[3],
                                        "pos" => $data[4]);
    
    ftruncate($poslogh[$server], 0);
    fwrite($poslogh[$server], serialize($poslogd));
    unset($poslogd);
    **/
    
    mysql_unbuffered_query( "INSERT INTO positions (guid, pos, slotid, team, name, server, hp) VALUES ('" . $players[$server][$data[1]]["guid"] . "', '" . mysql_real_escape_string( $data[ 4 ] ) . "', '".mysql_real_escape_string($data[1])."', '".mysql_real_escape_string($data[3])."', '".mysql_Real_escape_string($data[2])."', '".$server."', '".$data[5]."')
                             ON DUPLICATE KEY UPDATE pos = '" . mysql_real_escape_string($data[ 4 ]) . "', team = '".$data[3]."', hp = '".$data[5]."'" );

    
    //echo "Got POS ".$data[4]." for ".$data[2]." (".$data[3].")".PHP_EOL;
?>