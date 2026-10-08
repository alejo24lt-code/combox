<?php
// Get data from the form (cliente)


//get data base conecction 
require('../config/database.php');

$f_name = $_POST['firstname'];
$l_name = $_POST['lastname'];
$m_phone = $_POST['phone'];
$e_mail = $_POST['email'];
$p_assword = $_POST['password'];



//prepare query es basicamente preparar la consulta para insertar los datos en la base de datos, es como un borrador de la consulta, luego se ejecuta con pg_query_params
    $sql = "INSERT INTO users (
        firstname, lastname, mobile_phone, email, password) 
        VALUES ('$f_name', '$l_name', '$m_phone', '$e_mail', '$p_assword')";

    	$local_res = pg_query($local_conn, $sql);

	$supa_res = pg_query($supa_conn, $sql);


// aqui verificamos si la consulta se ejecuto correctamente en la base de datos local y en la base de datos supabase, si es asi imprimimos un mensaje de exito, si no imprimimos un mensaje de error
    if($local_res) {
        echo "User has been created sucessfully into local database";
    } else {
        echo "User has not been created to local database !! " ;
    }
// aqui verificamos si la consulta se ejecuto correctamente en la base de datos supabase, si es asi imprimimos un mensaje de exito, si no imprimimos un mensaje de error

    if($supa_res) {
        echo "User has been created sucessfully into supabase database";
    } else {
        echo "User has not been created to supabase database !! " ;
    }


// $_POST  es basicamente php, dame el dato llamado firstname que llego mediante post 

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];


// echo significa imprimir en pantalla, es como un print 
/*
echo "First Name: " . $firstname . "<br>";
echo "Last Name: " . $lastname . "<br>";
echo "Email: " . $email . "<br>";
echo "Phone: " . $phone . "<br>";
echo "Password: " . $password . "<br>";
*/




?>
