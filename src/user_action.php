
<?php
require('../config/database.php');

$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id)) {
    die("ID de usuario no válido.");
}

$sql = "SELECT id, firstname, lastname, email, status
        FROM users
        WHERE id = $1";

$result = pg_query_params($supa_conn, $sql, array($id));

if (!$result || pg_num_rows($result) === 0) {
    die("Usuario no encontrado.");
}

$user = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acción sobre usuario</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 380px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.5;
        }

        button, .cancelar {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            box-sizing: border-box;
        }

        .eliminar {
            background: #dc3545;
        }

        .desactivar {
            background: #f0ad4e;
        }

        .cancelar {
            background: #6c757d;
        }

        button:hover, .cancelar:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>¿Qué deseas hacer?</h2>

    <p>
        Usuario:
        <strong>
            <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
        </strong>
    </p>

    <p>Selecciona una opción para continuar.</p>

    <form action="process_user_action.php" method="POST">

        <input type="hidden" name="id"
               value="<?php echo htmlspecialchars($user['id']); ?>">

        <button class="eliminar" type="submit"
                name="action" value="delete"
                onclick="return confirm('¿Seguro que deseas eliminar definitivamente este usuario?');">
            Eliminar definitivamente
        </button>

	<?php if ($user['status'] === 't' || $user['status'] === true) { ?>

    <button class="desactivar" type="submit"
            name="action" value="deactivate"
            onclick="return confirm('¿Seguro que deseas desactivar este usuario?');">
        Desactivar usuario
    </button>

<?php } else { ?>

    <button class="desactivar" type="submit"
            name="action" value="activate"
            onclick="return confirm('¿Seguro que deseas activar este usuario?');">
        Activar usuario
    </button>

<?php } ?>

    </form>

    <a class="cancelar" href="list_users.php">
        Cancelar
    </a>

</div>

</body>
</html>
