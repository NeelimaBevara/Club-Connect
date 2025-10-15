<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_club_information";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only process updates if the form was submitted
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];

    // Update registration details
    $update_query = "UPDATE registrations 
                     SET name='$name', email='$email', mobile='$mobile', dob='$dob', branch='$branch', year='$year' 
                     WHERE student_id='$student_id'";
    $conn->query($update_query);

    // Delete existing club registrations
    $delete_clubs_query = "DELETE FROM club_registrations WHERE student_id='$student_id'";
    $conn->query($delete_clubs_query);

    // Insert new club registrations
    if (isset($_POST['clubs'])) {
        foreach ($_POST['clubs'] as $club) {
            $insert_club_query = "INSERT INTO club_registrations (student_id, club_name) VALUES ('$student_id', '$club')";
            $conn->query($insert_club_query);
        }
    }

    header("Location: user_dashboard.php");
    exit();
}
$email = $_SESSION['email'];
$query = "SELECT * FROM registrations WHERE email='$email'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    echo '<html lang="en">
            <head>
                <title>Edit Details</title>
                <style>
                    /* Add your styles here */
                </style>
            </head>
            <body>
                <h1>Edit Your Details</h1>
                <form action="" method="post">
                    <input type="hidden" name="student_id" value="' . $row["student_id"] . '">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="' . $row["name"] . '"><br>
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="' . $row["email"] . '"><br>
                    <label for="mobile">Mobile No.:</label>
                    <input type="text" id="mobile" name="mobile" value="' . $row["mobile"] . '"><br>
                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob" value="' . $row["dob"] . '"><br>
                    <label for="branch">Branch:</label>
                    <input type="text" id="branch" name="branch" value="' . $row["branch"] . '"><br>
                    <label for="year">Academic Year:</label>
                    <input type="text" id="year" name="year" value="' . $row["year"] . '"><br>
                    <label>Choose Clubs to Join:</label><br>';

    $clubs_query = "SELECT * FROM clubs";
    $clubs_result = $conn->query($clubs_query);

    if ($clubs_result->num_rows > 0) {
        while ($club_row = $clubs_result->fetch_assoc()) {
            $club_name = $club_row["club_name"];
            $checked = '';
            $club_query = "SELECT * FROM club_registrations WHERE student_id='" . $row["student_id"] . "' AND club_name='$club_name'";
            $club_result = $conn->query($club_query);
            if ($club_result->num_rows > 0) {
                $checked = 'checked';
            }

            echo '<input type="checkbox" name="clubs[]" value="' . $club_name . '" ' . $checked . '> ' . $club_name . '<br>';
        }
    }

    echo '<br><input type="submit" value="Save Changes">
          </form>
          </body>
          </html>';
} else {
    echo "User not found.";
}

$conn->close();
?>
