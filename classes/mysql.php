<?php
    mysql_connect($config->get("mysql", "host"), $config->get("mysql", "username"), $config->get("mysql", "password")) or die("Can not establish a MySQL Connection");
    
    mysql_select_db($config->get("mysql", "database")) or die("Database is not accessible"); 
    
    /** ??? automaticcly generate a new database if not found ???
    if(mysql_error()) {
        mysql_query("CREATE DATABASE IF NOT EXISTS \"".$config->get("mysql", "database")."\"") or die("Error: ".mysql_error());
        mysql_query(file_get_contents(FILE_DIR."/sql_structs.sql")) or die("Error: ".mysql_error());
    }
    **/
?>