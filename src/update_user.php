
<?php

require('../config/database.php');

// Verificar que los datos lleguen mediante POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_users.php");
    exit;
}

// Recibir los datos del formulario
$id = $_POST['id'] ?? '';
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile_phone = trim($_POST['mobile_phone'] ?? '');

if (
    $id === '' ||
    $firstname === '' ||
    $lastname === '' ||
    $email === '' ||
    $mobile_phone === ''
) {
    die("Todos los campos son obligatorios.");
}

// Preparar la ruta de la fotografía
$photo_url = null;

// Verificar si el usuario seleccionó una fotografía
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    die("Error al subir la fotografía. Código: " . $_FILES['photo']['error']);
}
    // Comprobar el tamaño: máximo 5 MB
    if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
        die("La fotografía no puede superar los 5 MB.");
    }

    // Obtener la extensión
    $ext = strtolower(pathinfo(
        $_FILES['photo']['name'],
        PATHINFO_EXTENSION
    ));

    // Permitir únicamente JPG, JPEG y PNG
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        die("Solo se permiten fotografías JPG o PNG.");
    }

    // Comprobar que realmente sea una imagen
    $image_info = getimagesize($_FILES['photo']['tmp_name']);

    if ($image_info === false) {
        die("El archivo seleccionado no es una imagen válida.");
    }

    // Preparar el nombre utilizando el celular
    $safe_mobile = preg_replace('/[^0-9]/', '', $mobile_phone);

    if ($safe_mobile === '') {
        die("El número de celular no es válido para nombrar la foto.");
    }

    $filename = $safe_mobile . "." . $ext;
    $destination = "photos/" . $filename;

    // Mover la fotografía a la carpeta photos
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        die("No se pudo guardar la fotografía. Revisa los permisos de la carpeta photos.");
    }

    $photo_url = $destination;
}

// Actualizar los datos del usuario
if ($photo_url !== null) {

    $sql = "UPDATE users
            SET firstname = $1,
                lastname = $2,
                email = $3,
                mobile_phone = $4,
                photo_url = $5
            WHERE id = $6";

    $result = pg_query_params($supa_conn, $sql, array(
        $firstname,
        $lastname,
        $email,
        $mobile_phone,
        $photo_url,
        $id
    ));

} else {

    $sql = "UPDATE users
            SET firstname = $1,
                lastname = $2,
                email = $3,
                mobile_phone = $4
            WHERE id = $5";

    $result = pg_query_params($supa_conn, $sql, array(
        $firstname,
        $lastname,
        $email,
        $mobile_phone,
        $id
    ));
}

// Comprobar el resultado
if (!$result) {
    die("Error al actualizar el usuario: " . pg_last_error($supa_conn));
}

// Regresar al listado
header("Location: list_users.php");
exit;

?>