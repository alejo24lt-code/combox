
<?php

require('../config/database.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No se recibió el ID del usuario.");
}

$sql = "SELECT * FROM users WHERE id = $1";
$result = pg_query_params($supa_conn, $sql, array($id));

if (!$result || pg_num_rows($result) == 0) {
    die("Usuario no encontrado.");
}

$user = pg_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar usuario - COMBOX</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 15px;
            color: #333;
        }

        .container {
            max-width: 550px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            color: #4e73df;
        }

        .description {
            text-align: center;
            color: #858796;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d3e2;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #4e73df;
        }

        .photo-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fc;
            border-radius: 8px;
        }

        .photo-section img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        input[type="file"] {
            width: 100%;
            font-size: 13px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button, .cancel {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        button {
            background: #4e73df;
            color: white;
        }

        button:hover {
            background: #2e59d9;
        }

        .cancel {
            background: #858796;
            color: white;
        }

        .cancel:hover {
            background: #6c6e7e;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h2>Editar usuario</h2>

        <p class="description">
            Modifica la información del usuario seleccionado.
        </p>

        <form action="update_user.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id"
                value="<?php echo htmlspecialchars($user['id']); ?>">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="firstname"
                    value="<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="lastname"
                    value="<?php echo htmlspecialchars($user['lastname'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email"
                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Celular</label>
                <input type="text" name="mobile_phone"
                    value="<?php echo htmlspecialchars($user['mobile_phone'] ?? ''); ?>" required>
            </div>

            <div class="photo-section">

                <label>Foto de perfil actual</label>

                <?php if (!empty($user['photo_url'])) { ?>
                    <br>
                    <img src="<?php echo htmlspecialchars($user['photo_url']); ?>"
                         alt="Foto de perfil">
                <?php } else { ?>
                    <p>Sin foto de perfil</p>
                <?php } ?>

                <div class="form-group">
                    <label>Seleccionar nueva foto</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png">
                </div>

            </div>

            <div class="buttons">

                <button type="submit">Guardar cambios</button>

                <a href="list_users.php" class="cancel">Cancelar</a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
