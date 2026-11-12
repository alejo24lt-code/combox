
<?php

// ============================================
// 1. INICIAR LA SESIÓN
// ============================================

// Permite recuperar una sesión existente o crear una nueva.
session_start();


// ============================================
// 2. CONECTAR CON LA BASE DE DATOS
// ============================================

// Incluimos el archivo que contiene la conexión con Supabase.
// Este archivo debe definir la variable $supa_conn.
require_once('../config/database.php');


// ============================================
// 3. COMPROBAR EL MÉTODO DE ENVÍO
// ============================================

// Este archivo debe recibir los datos mediante POST
// desde el formulario de inicio de sesión.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.html');
    exit;
}


// ============================================
// 4. RECIBIR LOS DATOS DEL FORMULARIO
// ============================================

// trim() elimina espacios al principio y al final del correo.
// No aplicamos trim() a la contraseña porque podría contener espacios válidos.
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


// ============================================
// 5. VALIDAR LOS CAMPOS
// ============================================

// Comprobamos que el usuario haya escrito ambos datos.
if ($email === '' || $password === '') {
    echo "<script>
        alert('Por favor, ingrese el correo y la contraseña');
        window.location.href = 'signin.html';
    </script>";
    exit;
}


// ============================================
// 6. BUSCAR EL USUARIO POR SU CORREO
// ============================================

// Buscamos únicamente por correo.
// La contraseña se comprobará después con PHP.
$sql = "SELECT id, firstname, lastname, email, password
        FROM public.users
        WHERE email = $1";

// pg_query_params permite enviar el correo como parámetro
// y evita construir la consulta concatenando datos del usuario.
$res = pg_query_params($supa_conn, $sql, array($email));


// ============================================
// 7. COMPROBAR SI LA CONSULTA FUNCIONÓ
// ============================================

if (!$res) {
    error_log('Error al consultar el usuario: ' . pg_last_error($supa_conn));

    echo "<script>
        alert('No fue posible consultar el usuario. Intente nuevamente.');
        window.location.href = 'signin.html';
    </script>";
    exit;
}


// ============================================
// 8. COMPROBAR SI EL USUARIO EXISTE
// ============================================

if (pg_num_rows($res) !== 1) {

    echo "<script>
        alert('El correo electrónico no está registrado');
        window.location.href = 'signin.html';
    </script>";
    exit;
}


// Recuperamos los datos del usuario encontrado.
$row = pg_fetch_assoc($res);


// ============================================
// 9. VERIFICAR LA CONTRASEÑA
// ============================================

// Recuperamos el hash almacenado en la base de datos.
$password_db = $row['password'];

// Inicialmente consideramos que la contraseña no es válida.
$valid_password = false;

// Indicamos si la contraseña necesita actualizarse de MD5
// al formato seguro de password_hash().
$update_password = false;


// --------------------------------------------
// OPCIÓN A: CONTRASEÑAS CON password_hash()
// --------------------------------------------

// password_verify() comprueba si la contraseña ingresada
// corresponde al hash almacenado.
if (password_verify($password, $password_db)) {

    $valid_password = true;

    // Si el algoritmo o sus parámetros necesitan actualizarse,
    // PHP nos permite detectar esa situación.
    if (password_needs_rehash($password_db, PASSWORD_DEFAULT)) {
        $update_password = true;
    }

} else {

    // ----------------------------------------
    // OPCIÓN B: CONTRASEÑAS ANTIGUAS CON MD5
    // ----------------------------------------

    // MD5 produce una cadena hexadecimal de 32 caracteres.
    // Comprobamos ese formato antes de intentar la comparación.
    if (preg_match('/^[a-f0-9]{32}$/i', $password_db)) {

        // Comparamos el MD5 de la contraseña ingresada
        // con el valor antiguo almacenado en la base de datos.
        if (hash_equals(strtolower($password_db), md5($password))) {

            $valid_password = true;

            // Si la contraseña antigua es correcta,
            // debemos actualizarla a password_hash().
            $update_password = true;
        }
    }
}


// ============================================
// 10. SI LA CONTRASEÑA ES INCORRECTA
// ============================================

if (!$valid_password) {

    echo "<script>
        alert('La contraseña es incorrecta');
        window.location.href = 'signin.html';
    </script>";
    exit;
}


// ============================================
// 11. ACTUALIZAR CONTRASEÑAS ANTIGUAS
// ============================================

// Si el usuario inició sesión con una contraseña MD5,
// la convertimos a un hash seguro y actualizamos el registro.
if ($update_password) {

    $new_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql_update = "UPDATE public.users
                   SET password = $1
                   WHERE id = $2";

    $result_update = pg_query_params(
        $supa_conn,
        $sql_update,
        array($new_hash, $row['id'])
    );

    // Si la actualización falla, registramos el error.
    // No bloqueamos el inicio de sesión porque la contraseña
    // original ya fue comprobada correctamente.
    if (!$result_update) {
        error_log('No se pudo actualizar el hash: ' . pg_last_error($supa_conn));
    }
}


// ============================================
// 12. CREAR LA SESIÓN DEL USUARIO
// ============================================

// Generamos un nuevo identificador de sesión.
// Esto ayuda a prevenir ataques de fijación de sesión.
session_regenerate_id(true);

// Guardamos los datos necesarios para reconocer al usuario
// en las demás páginas del sistema.
$_SESSION['user_id'] = $row['id'];
$_SESSION['fName'] = trim($row['firstname']);
$_SESSION['lName'] = trim($row['lastname']);
$_SESSION['email'] = $row['email'];


// ============================================
// 13. REDIRIGIR AL HOME
// ============================================

// El usuario ya fue autenticado correctamente.
// Lo enviamos a la página principal.
header('Location: home.php');
exit;

?>
