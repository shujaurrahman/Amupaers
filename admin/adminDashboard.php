<?php
require_once "../classes/User.php";
require_once "../classes/Database.php";

session_start(); 

// Check if the user is logged in and has the role of "super_admin"
if (isset($_SESSION['role']) && $_SESSION['role'] === 'super admin') {
    $isSuperAdmin = true;
} 
elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
} else {
    $isSuperAdmin = false;
    $isAdmin = false;
    header("location: ../index.php");
}

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize User object
$user = new User($conn);

// Fetch all users
$stmt = $user->readUsers();

// Check if there are any users
if ($stmt->rowCount() > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="./css/admindash.css">
        <style>
            td,tr{
                border: 2px solid !important;
                border-color: var(--green) !important ;
            }
            a:hover{
                color:var(--ultramarine-blue);
            }
            .login-btn:is(:hover, :focus){
                color: white;
            }
        </style>
        <?php require_once "../assets/fonts.php"; ?>
    </head>
    <body>
    <?php require_once "../includes/adminNav.php"; ?>
    <br><br><br><br>
    <div class="container">
        <h1 class="h3 mb-3" style="padding:20px 30px">All Users</h1>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                    <?php if ($isSuperAdmin) { ?>
                                        <th>Assign Role</th> <!-- Add Assign Role column for superadmins -->
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Loop through each user and display their details
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['username']}</td>";
                                    echo "<td id='rolecolumn-{$row['id']}'>{$row['role']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    // Check if the user is a superadmin
                                    if ($row['role'] == 'super_admin') {
                                        echo "<td><span class='badge bg-dark'>Super admin</span>";
                                    } else {
                                        // Display verification status for non-superadmin users
                                        echo "<td>";
                                        // Check if the role is not superadmin and the code is 0
                                        if ($row['code'] == 0) {
                                            echo "<span class='badge bg-success'>Verified</span>";
                                        } else {
                                            echo "<span class='badge bg-warning text-dark'>Unverified</span>";
                                        }
                                        echo "</td>";
                                    }
                                    // Format the registration date to display in "day month year" format
                                    $registrationDate = date('d F Y', strtotime($row['registration_date']));
                                    echo "<td>{$registrationDate}</td>";
                                    // Display the dropdown menu for superadmins
                                    if ($isSuperAdmin && $row['code'] == 0 && $row['role'] != 'super_admin') {
                                        echo "<td>";
                                        echo "<select class='form-select role-dropdown' data-user-id='{$row['id']}'>";
                                        echo "<option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>";
                                        echo "<option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
                                        echo "</select>";
                                        echo "</td>";
                                    } else {
                                        // If conditions not met, display an empty cell
                                        echo "<td></td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select all dropdowns with class 'role-dropdown'
        let roleDropdowns = document.querySelectorAll('.role-dropdown');

        // Add event listener for change event to each dropdown
        roleDropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('change', function() {
                // Get the selected value (new role)
                let newRole = this.value;

                // Get the user's ID from the 'data-user-id' attribute
                let userId = this.getAttribute('data-user-id');

                // Send asynchronous request to update user's role
                fetch(`../backend/updateUserRole.php?userId=${userId}&newRole=${newRole}`, {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle success response
                    console.log(data);
                    // Find the <td> element containing the role and update its text content
                    let roleCell = document.querySelector(`#rolecolumn-${userId}`);
                    if (roleCell) {
                        roleCell.textContent = newRole; // Update role text content
                    }
                })
                .catch(error => {
                    // Handle error
                    console.error('There was a problem with the fetch operation:', error);
                });
            });
        });
    });


</script>


    </body>
    </html>
    <?php
} else {
    // If no users found
    echo "<p>No users found.</p>";
}
?>
