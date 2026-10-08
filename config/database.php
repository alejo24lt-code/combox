<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// local connection esta es la conexion a la base de datos local, es basicamente un link que te lleva a la base de datos, tambien se llama connection string o database url

$local_host = "localhost";
$local_port = "5432";
$local_user = "postgres";
$local_dbname = "combox";
$local_password = "alejo24lt";

//===================================
// supa base connection esta es la conexion a la base de datos en la nube, es basicamente un link que te lleva a la base de datos, tambien se llama connection string o database url

$supa_host = "aws-0-us-west-2.pooler.supabase.com";
$supa_port = "6543";
$supa_user = "postgres.rzybdadcqjmlonauahdr";
$supa_dbname = "postgres";
$supa_password = "unicesmag@@";







// connect to the database

$local_conn = pg_connect("
    host=$local_host
    port=$local_port
    dbname=$local_dbname
    user=$local_user
    password=$local_password
");


//===========================================
//esta es  supa_conn esta es para conectar a la base de datos credenciales 
$supa_conn = pg_connect("
    host=$supa_host
    port=$supa_port
    dbname=$supa_dbname
    user=$supa_user
    password=$supa_password
");
//===========================================






//verificar conexion local 
if (!$local_conn) {

    die("Connection local connection");

} else {

    echo "Local Connected success ";

}



//verificar conexion a la nube
if (!$supa_conn) {

    die("Connection SupaBase connection");

} else {

    echo "SupaBase Connected success ";

}
// cloud connection



//enpoint: es basicamente la direccion de la base de datos en la nube, es como un link que te lleva a la base de datos tambien se llama connection string o database url
?>


