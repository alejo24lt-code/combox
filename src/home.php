
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combox | Home</title>
    <link rel="icon" type="image/png" href="icons/logo.png">
</head>

<body style="
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #0C6E0C, #1565C0);
">

    <!-- Header -->
    <header style="
        background-color: rgba(255,255,255,0.15);
        padding: 15px 30px;
        display: flex;
        align-items: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.25);
    ">

        <img src="icons/logo.png" alt="Combox Logo" width="70">

        <h2 style="
            color: white;
            margin-left: 15px;
        ">
            COMBOX
        </h2>

        <!-- User Profile -->
        <div style="
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 18px;
        ">

            <!-- User Icon -->
            <div style="
                width: 45px;
                height: 45px;
                background-color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #1565C0;
                font-size: 24px;
            ">
                👤
            </div>

            <!-- User Name -->
            <span style="color: white; font-weight: bold;">
                <?php
                echo htmlspecialchars($_SESSION['fName'] . " " . $_SESSION['lName']);
                ?>
            </span>

            <!-- Logout -->
            <a href="logout.php" style="
                color: white;
                text-decoration: none;
                margin-left: 10px;
            ">
                LogOut
            </a>

        </div>

    </header>


    <!-- Welcome -->
    <div style="
        text-align: center;
        padding: 45px 20px;
    ">

        <h1 style="color: white;">
            Welcome to COMBOX
        </h1>

        <p style="
            color: #E0E0E0;
            font-size: 17px;
        ">
            Your account is ready to use.
        </p>


        <!-- Cards -->
        <div style="
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 35px;
            flex-wrap: wrap;
        ">

            <!-- Profile Card -->
            <div style="
                width: 200px;
                padding: 25px;
                background-color: rgba(245,245,245,0.88);
                border: 2px solid #42A5F5;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            ">

                <h3 style="color: #1565C0;">
                    Profile
                </h3>

                <p style="color: #555555;">
                    Your personal information.
                </p>

            </div>


            <!-- Services Card -->
            <div style="
                width: 200px;
                padding: 25px;
                background-color: rgba(245,245,245,0.88);
                border: 2px solid #66BB6A;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            ">

                <h3 style="color: #0C6E0C;">
                    Services
                </h3>

                <p style="color: #555555;">
                    Explore COMBOX services.
                </p>

            </div>


            <!-- Information Card -->
            <div style="
                width: 200px;
                padding: 25px;
                background-color: rgba(245,245,245,0.88);
                border: 2px solid #42A5F5;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            ">

                <h3 style="color: #1565C0;">
                    Information
                </h3>

                <p style="color: #555555;">
                    Find useful information.
                </p>

            </div>

        </div>

    </div>

</body>
</html>
