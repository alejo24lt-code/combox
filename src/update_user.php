
<?php
require('../config/database.php');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_users.php");
    exit;
}

$id = $_POST['id'] ?? '';
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile_phone = trim($_POST['mobile_phone'] ?? '');

if (
    !ctype_digit((string)$id) ||
    $firstname === '' ||
    $lastname === '' ||
    $email === '' ||
    $mobile_phone === ''
) {
    die("Datos del usuario no válidos.");
}

$photo_url = null;

if (isset($_FILES['photo']) &&
    $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        die("Error al recibir la fotografía.");
    }

    if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
        die("La fotografía no puede superar los 5 MB.");
    }

    $image_info = getimagesize($_FILES['photo']['tmp_name']);

    if ($image_info === false) {
        die("El archivo no es una imagen válida.");
    }

    $mime = $image_info['mime'];

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($extensions[$mime])) {
        die("Solo se permiten imágenes JPG, PNG o WEBP.");
    }

    $extension = $extensions[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    $storage_path = rawurlencode($id) . '/' . rawurlencode($filename);

    $upload_url = SUPABASE_URL .
        '/storage/v1/object/' .
        SUPABASE_BUCKET . '/' . $storage_path;

    $file_content = file_get_contents($_FILES['photo']['tmp_name']);

    $ch = curl_init($upload_url);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $file_content,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_PUBLISHABLE_KEY,
            'Content-Type: ' . $mime
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);

    curl_close($ch);

    if ($response === false || !in_array($http_code, [200, 201])) {
        die("No se pudo subir la fotografía a Supabase. " . $curl_error);
    }

    $photo_url = SUPABASE_URL .
        '/storage/v1/object/public/' .
        SUPABASE_BUCKET . '/' . $storage_path;
}

if ($photo_url !== null) {
    $sql = "UPDATE users
            SET firstname = $1,
                lastname = $2,
                email = $3,
                mobile_phone = $4,
                photo_url = $5
            WHERE id = $6";

    $result = pg_query_params($supa_conn, $sql, [
        $firstname,
        $lastname,
        $email,
        $mobile_phone,
        $photo_url,
        $id
    ]);

} else {
    $sql = "UPDATE users
            SET firstname = $1,
                lastname = $2,
                email = $3,
                mobile_phone = $4
            WHERE id = $5";

    $result = pg_query_params($supa_conn, $sql, [
        $firstname,
        $lastname,
        $email,
        $mobile_phone,
        $id
    ]);
}

if (!$result) {
    die("Error al actualizar el usuario: " . pg_last_error($supa_conn));
}

header("Location: list_users.php");
exit;
?>
