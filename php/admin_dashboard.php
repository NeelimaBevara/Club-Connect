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

// Handling Records Per Page
$recordsPerPage = isset($_GET['recordsPerPage']) ? (int)$_GET['recordsPerPage'] : 10;

$pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pageNumber - 1) * $recordsPerPage;

$query = "SELECT * FROM registrations LIMIT $offset, $recordsPerPage";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<html lang="en">
            <head>
                <title>Registration Details</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
                <style>
                    table {
                        border-collapse: collapse;
                        width: 70%;
                        margin: auto;
                        text-align: center;
                    }

                    th, td {
                        border: 1px solid #dddddd;
                        padding: 8px;
                    }

                    th {
                        background-color: #f2f2f2;
                    }

                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }

                    h2 {
                        text-align: center;
                        font-size: 32px; /* Increased heading size */
                    }

                    .action-icons {
                        display: flex;
                        justify-content: space-around;
                    }

                    .action-icons i {
                        cursor: pointer;
                        font-size: 18px;
                    }

                    .action-icons i:hover {
                        color: #007BFF;
                    }

                    .logout-btn {
                        position: absolute;
                        top: 10px;
                        right: 10px; /* Changed to right corner */
                        background-color: #007BFF;
                        color: white;
                        border: none;
                        border-radius: 3px;
                        cursor: pointer;
                        font-size: 16px;
                        padding: 10px 20px;
                    }

                    .logout-btn:hover {
                        background-color: #0056b3;
                    }

                    .records-dropdown {
                        position: absolute;
                        top: 10px;
                        left: 70px;
                        font-size: 16px;
                        padding: 5px 10px;
                    }

                    .pagination {
                        text-align: center;
                        margin-top: 20px;
                    }

                    .pagination a {
                        padding: 10px;
                        text-decoration: none;
                        border: 1px solid #007BFF;
                        margin: 0 5px;
                        color: #007BFF;
                        border-radius: 5px;
                    }

                    .pagination a.active {
                        background-color: #007BFF;
                        color: #fff;
                    }
                </style>
            </head>
            <body>';

    echo '<div class="records-dropdown">
            <label for="recordsPerPage">Records per page:</label>
            <select id="recordsPerPage" name="recordsPerPage" onchange="changeRecordsPerPage()">
                <option value="10" ' . ($recordsPerPage == 10 ? 'selected' : '') . '>10</option>
                <option value="20" ' . ($recordsPerPage == 20 ? 'selected' : '') . '>20</option>
                <option value="50" ' . ($recordsPerPage == 50 ? 'selected' : '') . '>50</option>
            </select>
          </div>';

    echo '<form action="index.html" method="post">
            <input type="submit" class="logout-btn" value="Logout">
          </form>';

    echo '<h2>Registration Details</h2>';

    echo '<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Student ID</th>
                <th>Mobile No.</th>
                <th>Date of Birth</th>
                <th>Branch</th>
                <th>Academic Year</th>
                <th>Clubs</th>
                <th>Actions</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["id"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="name" data-id="' . $row["id"] . '">' . $row["name"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="email" data-id="' . $row["id"] . '">' . $row["email"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="student_id" data-id="' . $row["id"] . '">' . $row["student_id"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="mobile" data-id="' . $row["id"] . '">' . $row["mobile"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="dob" data-id="' . $row["id"] . '">' . $row["dob"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="branch" data-id="' . $row["id"] . '">' . $row["branch"] . '</td>';
        echo '<td contenteditable="true" class="edit" data-field="year" data-id="' . $row["id"] . '">' . $row["year"] . '</td>';

        $student_id = $row["student_id"];
        $club_query = "SELECT club_name FROM club_registrations WHERE student_id='$student_id'";
        $club_result = $conn->query($club_query);

        echo '<td>';
        if ($club_result->num_rows > 0) {
            while ($club_row = $club_result->fetch_assoc()) {
                echo $club_row["club_name"] . '<br>';
            }
        } else {
            echo 'Not registered in any clubs';
        }
        echo '</td>';

        echo '<td class="action-icons">
                <i class="fas fa-save" onclick="saveUser(' . $row["id"] . ')"></i>
                <i class="fas fa-trash-alt" onclick="deleteUser(' . $row["id"] . ')"></i>
              </td>';

        echo '</tr>';
    }

    echo '</table>';

    $query = "SELECT COUNT(*) as total FROM registrations";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $totalRecords = $row['total'];

    $totalPages = ceil($totalRecords / $recordsPerPage);

    echo '<div class="pagination" id="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?page=' . $i . '&recordsPerPage=' . $recordsPerPage . '">' . $i . '</a>';
    }
    echo '</div>';

    echo '<script>
            function changeRecordsPerPage() {
                let recordsPerPage = document.getElementById("recordsPerPage").value;
                window.location.href = "registration_details.php?recordsPerPage=" + recordsPerPage;
            }

            document.addEventListener("DOMContentLoaded", function() {
                let editFields = document.querySelectorAll(".edit");

                editFields.forEach(field => {
                    field.addEventListener("blur", function() {
                        let field = this.dataset.field;
                        let id = this.dataset.id;
                        let value = this.innerText;

                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "update_details.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                // Handle the response from the server if needed
                            }
                        };
                        xhr.send("field=" + field + "&value=" + encodeURIComponent(value) + "&id=" + id);
                    });
                });
            });

            function saveUser(userId) {
                let name = document.getElementById("name_" + userId).innerText;
                let email = document.getElementById("email_" + userId).innerText;
                let student_id = document.getElementById("student_id_" + userId).innerText;
                let mobile = document.getElementById("mobile_" + userId).innerText;
                let dob = document.getElementById("dob_" + userId).innerText;
                let branch = document.getElementById("branch_" + userId).innerText;
                let year = document.getElementById("year_" + userId).innerText;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "update_details.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        // Handle the response from the server if needed
                    }
                };
                xhr.send("field=name&value=" + encodeURIComponent(name) + "&id=" + userId);
                xhr.send("field=email&value=" + encodeURIComponent(email) + "&id=" + userId);
                xhr.send("field=student_id&value=" + encodeURIComponent(student_id) + "&id=" + userId);
                xhr.send("field=mobile&value=" + encodeURIComponent(mobile) + "&id=" + userId);
                xhr.send("field=dob&value=" + encodeURIComponent(dob) + "&id=" + userId);
                xhr.send("field=branch&value=" + encodeURIComponent(branch) + "&id=" + userId);
                xhr.send("field=year&value=" + encodeURIComponent(year) + "&id=" + userId);
            }

            function deleteUser(userId) {
                if (confirm("Are you sure you want to delete this user?")) {
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "delete_user.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            document.getElementById("name_" + userId).closest("tr").remove(); // Remove the row from the table
                        }
                    };
                    xhr.send("id=" + userId);
                }
            }
          </script>';

    echo '</body>
          </html>';
} else {
    echo "No users found.";
}

$conn->close();
?>
