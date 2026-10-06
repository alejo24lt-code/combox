<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// local connection

$local_host = "localhost";
$local_port = "5432";
$local_user = "postgres";
$local_dbname = "combox";
$local_password = "alejo24lt";

// connect to the database

$conn = pg_connect("
    host=$local_host
    port=$local_port
    dbname=$local_dbname
    user=$local_user
    password=$local_password
");

if (!$conn) {

    die("Connection failed");

} else {

    echo "Connected successfully";

}

// cloud connection

?>