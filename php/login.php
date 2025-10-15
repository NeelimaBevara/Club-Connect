<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_club_information";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$email = $_POST["email"];
$password = $_POST["password"];
$role = $_POST["role"];

if ($role == "user") {
    $query = "SELECT * FROM registrations WHERE email='$email' AND student_id='$password'";
    $dashboard = "user_dashboard.php";
} elseif ($role == "admin") {
    $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $dashboard = "admin_dashboard.php";
}

$result = $conn->query($query);

if ($result->num_rows == 1) {
    $_SESSION['email'] = $email;
    header("Location: $dashboard");
} else {
    header("Location: log_in.php?error=invalid");
}

$conn->close();
?>
