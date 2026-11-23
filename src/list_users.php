
<?php

require('../config/database.php');

$sql = "SELECT id,
    firstname,
    lastname,
    mobile_phone,
    email,
    photo_url,
    CASE
        WHEN status = true THEN 'Active'
        ELSE 'Inactive'
    END AS user_status
    FROM users
    ORDER BY lastname ASC";

$res = pg_query($supa_conn, $sql);

if (!$res) {
    die("Error al consultar usuarios: " . pg_last_error($supa_conn));
}

$storage_prefix = 'https://rzybdadcqjmlonauahdr.supabase.co/storage/v1/object/public/profile-photos/';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combox</title>
</head>
<body>

    <center><h3>List of Users</h3></center>

    <table border="1" align="center">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Status</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($res)) {

            $foto = $row['photo_url'] ?? '';

            if (!empty($foto) && strpos($foto, $storage_prefix) === 0) {
                $rutaFoto = $foto;
            } else {
                $nombreFoto = basename($foto);
                $rutaFoto = 'photos/' . $nombreFoto;

                if (
                    empty($foto) ||
                    $nombreFoto === '.' ||
                    $nombreFoto === '..' ||
                    !is_file(__DIR__ . '/' . $rutaFoto)
                ) {
                    $rutaFoto = 'photos/usuario.png';
                }
            }
        ?>

        <tr>
            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
            <td><?php echo htmlspecialchars($row['mobile_phone']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['user_status']); ?></td>

            <td align="center">
                <img src="<?php echo htmlspecialchars($rutaFoto); ?>" width="30">
            </td>

            <td align="center">
                <a href="edit_user.php?id=<?php echo urlencode($row['id']); ?>">
                    <img src="icons/lapiz.png" width="20" alt="Editar">
                </a>

                <a href="user_action.php?id=<?php echo urlencode($row['id']); ?>">
                    <img src="icons/basura.png" width="20" alt="Eliminar">
                </a>

                <a href="view_user.php?id=<?php echo urlencode($row['id']); ?>">
                    <img src="icons/lupa.png" width="20" alt="Consultar">
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</body>
</html>

