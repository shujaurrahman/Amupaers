<?php
require_once "../classes/User.php";
require_once "../classes/Database.php";

session_start(); 

// Check if the user is logged in and has the role of "super_admin"
if (isset($_SESSION['role']) && $_SESSION['role'] === 'super admin') {
    $isSuperAdmin = true;
} else {
    $isSuperAdmin = false;
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
        <?php require_once "../assets/fonts.php"; ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="./css/admindash.css">
    </head>
    <body>
    <?php require_once "../includes/adminNav.php"; ?>
    <br><br><br><br>
    <div class="container">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">All registered users</h1>
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
                                    echo "<td>{$row['role']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    // Check if the user is a superadmin
                                    if ($row['role'] == 'super_admin') {
                                        echo "<td>No Verification Required</td>";
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
                                    if ($isSuperAdmin) {
                                        echo "<td>";
                                        echo "<select class='form-select'>";
                                        echo "<option value='user'>User</option>";
                                        echo "<option value='admin'>Admin</option>";
                                        echo "</select>";
                                        echo "</td>";
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

    <?php require_once "../includes/footer.php"; ?>
    </body>
    </html>
    <?php
} else {
    // If no users found
    echo "<p>No users found.</p>";
}
?>
