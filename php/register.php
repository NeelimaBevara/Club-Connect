<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "college_club_information";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST["name"];
$email = $_POST["email"];
$student_id = $_POST["student-id"];
$mobile = $_POST["mobile"];
$dob = $_POST["dob"];
$branch = $_POST["branch"];
$year = $_POST["year"];
$clubs = $_POST["clubs"];

// Check if the user already exists
$check_query = "SELECT * FROM registrations WHERE student_id='$student_id'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    echo "You are already registered.";
} else {
    $sql = "INSERT INTO registrations (name, email, student_id, mobile, dob, branch, year)
            VALUES ('$name', '$email', '$student_id', '$mobile', '$dob', '$branch', '$year')";

    if ($conn->query($sql) === TRUE) {
        foreach ($clubs as $club) {
            $club_sql = "INSERT INTO club_registrations (student_id, club_name)
                         VALUES ('$student_id', '$club')";
            $conn->query($club_sql);
        }
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
