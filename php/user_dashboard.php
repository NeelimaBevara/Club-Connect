<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: log_in.php");
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

$email = $_SESSION['email'];
$query = "SELECT * FROM registrations WHERE email='$email'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    echo '<html lang="en">
            <head>
                <title>User Dashboard</title>
                <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f2f2f2;
                        text-align: center;
                        margin-top: 50px;
                    }

                    .welcome-msg {
                        text-align: left;
                        margin-left: 50px;
                        font-size: 20px;
                        color: #007BFF;
                    }

                    .registration-details {
                        font-size: 24px;
                        margin-bottom: 20px;
                    }

                    table {
                        border-collapse: collapse;
                        width: 70%;
                        margin: auto;
                    }

                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: left;
                    }

                    th {
                        background-color: #007BFF;
                        color: white;
                    }

                    h2 {
                        color: #007BFF;
                    }

                    form {
                        margin-top: 20px;
                    }

                    input[type="submit"] {
                        background-color: #007BFF;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 3px;
                        cursor: pointer;
                        font-size: 16px;
                    }

                    input[type="submit"]:hover {
                        background-color: #0056b3;
                    }

                    .edit-icon {
                        cursor: pointer;
                        color: #007BFF;
                    }

                    .edit-icon:hover {
                        color: #0056b3;
                    }

                    .logout-btn {
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: #007BFF;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        font-size: 16px;
                        cursor: pointer;
                    }

                    .logout-btn:hover {
                        background: #0056b3;
                    }
                </style>
            </head>
            <body>
                <form action="index.html" method="post">
                    <input type="submit" class="logout-btn" value="Logout">
                </form>

                <div class="welcome-msg">
                    <h1>Welcome, ' . $row["name"] . '!</h1>
                </div>';

    echo '<table border="1">
            <tr>
                <th>Field</th>
                <th>Details</th>
            </tr>
            <tr>
                <td>Full Name <span class="edit-icon" data-field="name" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="name">' . $row["name"] . '</span></td>
            </tr>
            <tr>
                <td>Email <span class="edit-icon" data-field="email" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="email">' . $row["email"] . '</span></td>
            </tr>
            <tr>
                <td>Student ID <span class="edit-icon" data-field="student_id" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="student_id">' . $row["student_id"] . '</span></td>
            </tr>
            <tr>
                <td>Mobile No. <span class="edit-icon" data-field="mobile" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="mobile">' . $row["mobile"] . '</span></td>
            </tr>
            <tr>
                <td>Date of Birth <span class="edit-icon" data-field="dob" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="dob">' . $row["dob"] . '</span></td>
            </tr>
            <tr>
                <td>Branch <span class="edit-icon" data-field="branch" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="branch">' . $row["branch"] . '</span></td>
            </tr>
            <tr>
                <td>Academic Year <span class="edit-icon" data-field="year" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td><span id="year">' . $row["year"] . '</span></td>
            </tr>
            <tr>
                <td>Clubs <span class="edit-icon" data-field="clubs" data-id="' . $row["id"] . '">&#9998;</span></td>
                <td>';

    // Fetch and display club registrations
    $student_id = $row["student_id"];
    $club_query = "SELECT id, club_name FROM club_registrations WHERE student_id='$student_id'";
    $club_result = $conn->query($club_query);

    if ($club_result->num_rows > 0) {
        while ($club_row = $club_result->fetch_assoc()) {
            echo '<span class="club" data-club-id="' . $club_row["id"] . '">' . $club_row["club_name"] . '<br></span>';
        }
    } else {
        echo 'Not registered in any clubs';
    }

    echo '</td></tr>
          </table>';

    echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    let editIcons = document.querySelectorAll(".edit-icon");

                    editIcons.forEach(icon => {
                        icon.addEventListener("click", function() {
                            let field = this.dataset.field;
                            let id = this.dataset.id;
                            let value = document.getElementById(field).innerText;

                            let newValue = prompt("Edit " + field, value);

                            if (newValue !== null && newValue !== "") {
                                document.getElementById(field).innerText = newValue;

                                let xhr = new XMLHttpRequest();
                                xhr.open("POST", "update_details.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                        // Handle the response from the server if needed
                                    }
                                };
                                xhr.send("field=" + field + "&value=" + encodeURIComponent(newValue) + "&id=" + id);
                            }
                        });
                    });
                });
            </script>';

    echo '</body>
          </html>';
} else {
    echo "User not found.";
}

$conn->close();
?>
