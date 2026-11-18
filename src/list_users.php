
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

    FROM users u
    order by lastname asc";

$res = pg_query($supa_conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="width=device-width, initial-scale=1.0">
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
        </tr>

        <?php
        while ($row = pg_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";
            echo "<td>" . $row['mobile_phone'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['user_status'] . "</td>";
            echo "<td align='center'><img src='" . $row['photo_url'] . "' width='30'></td>";
            
            
            echo "<td>
                <a href='edit_user.php?id=" . $row['id'] . "'>
                 <img src='icons/lapiz.png' width='20'>&nbsp;&nbsp;
                </a>&nbsp;&nbsp;
                <a href='user_action.php?id=" . $row['id'] . "'>
    <img src='icons/basura.png' width='20'>
</a>&nbsp;&nbsp;

                <img src='icons/lupa.png' width='20'>
                </td>";
            
            
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
