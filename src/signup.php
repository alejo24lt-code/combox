<?php

// Get data from the form (cliente)

// get database connection
require('../config/database.php');

$f_name = $_POST['firstname'];
$l_name = $_POST['lastname'];
$m_phone = $_POST['phone'];
$e_mail = $_POST['email'];
$p_assword = $_POST['password'];

// vamos a encriptar la contraseña
$pass_enc = md5($p_assword);


// VERIFICAR SI EL CORREO O EL TELEFONO YA EXISTEN

$sql_check = "SELECT email, mobile_phone
              FROM public.users
              WHERE email = $1 OR mobile_phone = $2";

$check_res = pg_query_params(
    $supa_conn,
    $sql_check,
    array($e_mail, $m_phone)
);

if (!$check_res) {
    die("Error al verificar los datos.");
}
/* COMPROBAR SI EXISTE ALGUNA COINCIDENCIA */
if (pg_num_rows($check_res) > 0) {

    $user = pg_fetch_assoc($check_res);

    if ($user['email'] == $e_mail) {

        echo json_encode([
            'success' => false,
            'field' => 'email',
            'message' => 'Este correo ya se encuentra registrado.'
        ]);
        exit;

    } elseif ($user['mobile_phone'] == $m_phone) {

        echo json_encode([
            'success' => false,
            'field' => 'phone',
            'message' => 'Este teléfono ya se encuentra registrado.'
        ]);
        exit;
    }
}

// PREPARE QUERY
// Preparamos la consulta para insertar los datos en la base de datos.

$sql = "INSERT INTO public.users (
            firstname, lastname, mobile_phone, email, password
        )
        VALUES ($1, $2, $3, $4, $5)";


// INSERCION LOCAL DESACTIVADA POR AHORA

/*
$local_res = pg_query_params($local_conn, $sql, array(
    $f_name, $l_name, $m_phone, $e_mail, $pass_enc
));
*/


// INSERCION EN SUPABASE

$supa_res = pg_query_params(
    $supa_conn,
    $sql,
    array($f_name, $l_name, $m_phone, $e_mail, $pass_enc)
);
if ($supa_res) {

    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully!'
    ]);
    exit;

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Could not create the account.'
    ]);
    exit;
}

// VERIFICAR SI LA CONSULTA SE EJECUTO CORRECTAMENTE

if ($supa_res) {

    echo "<script>
            alert('User has been created successfully in Supabase!');
            window.location.href = 'signin.html';
          </script>";

} else {

    error_log(pg_last_error($supa_conn));

    echo "<script>
            alert('User has not been created. Please try again.');
            window.history.back();
          </script>";
}

?>