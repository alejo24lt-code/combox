<?php
// Get data from the form (cliente)

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

echo "First Name: " . $firstname . "<br>";
echo "Last Name: " . $lastname . "<br>";
echo "Email: " . $email . "<br>";
echo "Phone: " . $phone . "<br>";
echo "Password: " . $password . "<br>";

?>