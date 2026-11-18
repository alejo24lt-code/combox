
<?php

header('Content-Type: application/json; charset=utf-8');

// Obtener conexión con Supabase
require('../config/database.php');

// Recibir datos del formulario
$f_name = trim($_POST['firstname'] ?? '');
$l_name = trim($_POST['lastname'] ?? '');
$m_phone = trim($_POST['phone'] ?? '');
$e_mail = trim($_POST['email'] ?? '');
$p_assword = $_POST['password'] ?? '';

// Verificar campos obligatorios
if (
    $f_name === '' ||
    $l_name === '' ||
    $m_phone === '' ||
    $e_mail === '' ||
    $p_assword === ''
) {
    echo json_encode([
        'success' => false,
        'message' => 'Todos los campos son obligatorios.'
    ]);
    exit;
}

// Encriptar contraseña (se conserva el método actual)
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

    error_log(pg_last_error($supa_conn));

    echo json_encode([
        'success' => false,
        'message' => 'Error al verificar los datos.'
    ]);
    exit;
}

// COMPROBAR COINCIDENCIAS

while ($user = pg_fetch_assoc($check_res)) {

    if ($user['email'] === $e_mail) {

        echo json_encode([
            'success' => false,
            'field' => 'email',
            'message' => 'Este correo ya se encuentra registrado.'
        ]);
        exit;
    }

    if ($user['mobile_phone'] === $m_phone) {

        echo json_encode([
            'success' => false,
            'field' => 'phone',
            'message' => 'Este teléfono ya se encuentra registrado.'
        ]);
        exit;
    }
}

// PREPARAR CONSULTA DE INSERCION

$photo_url = 'photos/usuario.png';

$sql = "INSERT INTO public.users (
            firstname,
            lastname,
            mobile_phone,
            email,
            password,
            photo_url
        )
        VALUES ($1, $2, $3, $4, $5, $6)";

// INSERTAR EN SUPABASE

$supa_res = pg_query_params(
    $supa_conn,
    $sql,
    array(
    $f_name,
    $l_name,
    $m_phone,
    $e_mail,
    $pass_enc,
    $photo_url
)
);

// RESPUESTA

if ($supa_res) {

    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully!'
    ]);

} else {

    error_log(pg_last_error($supa_conn));

    echo json_encode([
        'success' => false,
        'message' => 'Could not create the account. Please try again.'
    ]);
}

exit;
?>

