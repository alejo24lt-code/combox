
<?php

// Obtener la conexión a la base de datos
require('../config/database.php');

// Recibir el ID del usuario
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    die("No se recibió el ID del usuario.");
}

// Eliminar el usuario
$sql = "DELETE FROM users WHERE id = $1";

$res = pg_query_params($supa_conn, $sql, array($user_id));

// Comprobar el resultado
if ($res && pg_affected_rows($res) > 0) {

    echo "<script>
        alert('Usuario eliminado correctamente');
        window.location.href = 'list_users.php';
    </script>";

} else {

    error_log("Error al eliminar usuario: " . pg_last_error($supa_conn));

    echo "<script>
        alert('No se pudo eliminar el usuario');
        window.location.href = 'list_users.php';
    </script>";
}

exit;

?>
