<?php

// GET DATABASE CONNECTION
require('../config/database.php'); //es el arxchivo donde tenemos configurada la conexcion 


//session _start(); esto es para iniciar la sesión del usuario, pero no es necesario en este caso ya que no estamos usando sesiones.
session_start(); // inicia o recupera una sesion existente para el usuario, esto es necesario para poder almacenar los datos del usuario en la sesión y poder acceder a ellos en otras páginas.

/*
if(isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
*/

$_SESSION = [];

//recibe los datos del formulario de inicio de sesión y los almacena en variables.
$email = $_POST['email'] ;
$password = $_POST['password'] ;
$password_enc = md5($password);



// PREPARE QUERY aqui permite la consulta en supabase para verificar si el usuario existe en la base de datos y si la contraseña es correcta. 
$sql = "SELECT * FROM public.users
        WHERE email = $1 AND password = $2";
//ejecuta consulta en la base de datos y almacena el resultado en la variable $res.
$res = pg_query_params($supa_conn, $sql, array(
    $email,
    $password_enc
));

// CHECK USER basicamente aqui hace la verificación de si el usuario existe o no, si existe, se inicia la sesión y se redirige a la página de inicio, si no existe, se muestra un mensaje de error y se redirige a la página de inicio de sesión.
// Buscar si el correo existe
$sql = "SELECT * FROM public.users WHERE email = $1";

$res = pg_query_params($supa_conn, $sql, array($email));

if ($res && pg_num_rows($res) > 0) {

    $row = pg_fetch_assoc($res);

    // Verificar la contraseña
    if ($row['password'] == $password_enc) {

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['fName'] = trim($row['firstname']);
        $_SESSION['lName'] = trim($row['lastname']);

        header('Location: home.php');
        exit;

    } else {

        echo "<script>
            alert('Error: La contraseña es incorrecta');
            window.location.href = 'signin.html';
        </script>";

        exit;
    }

} else {

    echo "<script>
        alert('Error: El correo electrónico no está registrado');
        window.location.href = 'signin.html';
    </script>";

    exit;
}

?>