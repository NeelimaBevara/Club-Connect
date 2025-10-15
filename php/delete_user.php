<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_club_information";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

    $deleteQuery = "DELETE FROM registrations WHERE id=$userId";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $conn->close();
}
?>
