<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_club_information";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$field = $_POST['field'];
$value = $_POST['value'];
$id = $_POST['id'];

$sql = "UPDATE registrations SET $field='$value' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
