<?php

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;
    public $email;
    public $role;
    public $registration_date;
    public $is_super_admin;
    public $code;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function validateUserData($fname, $lname, $email, $password) {
        $errors = array();

        if (strlen($fname) < 4) {
            $errors[] = "First name should be at least 4 characters long.";
        }

        if (strlen($lname) < 4) {
            $errors[] = "Last name should be at least 4 characters long.";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password should be at least 8 characters long.";
        } elseif (!preg_match('/^(?=.*[0-9].*[0-9])(?=.*[!@#$%^&*]).{4,}$/', $password)) {
            $errors[] = "Password should contain at least 4 characters, 1 symbol, and 2 numbers.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        return $errors;
    }
    public function registerUser() {
        // Generate verification code (6-digit number)
        $verificationCode = mt_rand(100000, 999999);
    
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET username = :username, password = :password, email = :email, role = :role, registration_date = :registration_date, is_super_admin = :is_super_admin, code = :code';
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':registration_date', $this->registration_date);
        $stmt->bindParam(':is_super_admin', $this->is_super_admin);
        $stmt->bindParam(':code', $verificationCode); // Bind the verification code as an integer
    
        if ($stmt->execute()) {
            return $verificationCode; // Return verification code
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

        // Verify user using verification code
public function verifyUser($enteredCode, $email) {
    // Fetch the verification code from the database
    $query = 'SELECT code FROM ' . $this->table . ' WHERE email = :email';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $verificationCode = $row['code'];

        // Check if the entered code matches the verification code
        if ($enteredCode == $verificationCode) {
            // Set the user as verified by updating the code to 0
            $query = 'UPDATE ' . $this->table . ' SET code = 0 WHERE email = :email';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                return "success";
            } else {
                return "didnot exe";
            }
        } else {
            return "Incorrect verification code.";
        }
    } else {
        return "User not found or verification code not retrieved.";
    }
}
    // Generate verification code
    public function generateVerificationCode() {
        return mt_rand(100000, 999999); // Generate a 6-digit random number
    }

    // Send verification email
    public function sendVerificationEmail($email, $verificationCode) {
        $subject = 'Email Verification Code';
        $msg = 'Thank you for registering. Your verification code is:';
        $msgend = 'Please use this code to verify your email address.';

        // Send email using the PHPMailer instance created in mail.php
        try {
            require "../auth/mail.php";
            $m->addAddress($email, $this->getFirstName($email)); // Add recipient
            $m->Subject = $subject;
            $m->Body = $msg . ' <b>' . $verificationCode . '</b><br><br>' . $msgend; // Set email body
            $m->send(); // Send the email
        } catch (Exception $e) {
            echo "Failed to send verification email. Error: " . $e->getMessage();
        }
    }

    // Update verification code in the database
    public function updateVerificationCode($email, $verificationCode) {
        $query = 'UPDATE ' . $this->table . ' SET code = :code WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $verificationCode);
        $stmt->bindParam(':email', $email);
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Get first name from the database using email
    private function getFirstName($email) {
        $query = 'SELECT username FROM ' . $this->table . ' WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $username = $row['username'];
            $parts = explode(' ', $username);
            return $parts[0]; // Return the first part of the username as the first name
        }
        return ''; // Return empty string if no user found
    }
    // Log in user
// Log in user
public function loginUser() {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':email', $this->username); // Use email as the parameter
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($this->password, $row['password'])) {
        if ($row['code'] == 0) {
            return $row; // User is verified and login is successful
        } else {
            return 'unverified'; // User exists but is not verified
        }
    }
    return null; // Login failed, return null
}



    
       // Check if email already exists
       public function checkEmailExistence($email) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Returns true if email exists, false otherwise
    }

    // Login user with email existence check
    public function loginWithEmailExistence($email) {
        // Check if email exists
        if ($this->checkEmailExistence($email)) {
            // Proceed with login
            $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Returns user data if email exists
        } else {
            return null; // Return null if email doesn't exist
        }
    }

    // Read all users
    public function readUsers() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single user by ID
    public function readUserById() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function getUserDetailsByEmail($email) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $userDetails = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user details
        return $userDetails; // Return user details as an associative array
    }
    
    
    // Update user
    public function updateUser() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET username = :username, password = :password, email = :email, role = :role, registration_date = :registration_date, is_super_admin = :is_super_admin
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':registration_date', $this->registration_date);
        $stmt->bindParam(':is_super_admin', $this->is_super_admin);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete user
    public function deleteUser() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update user to admin role
    public function promoteToAdmin() {
        $query = 'UPDATE ' . $this->table . ' SET role = :role WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $adminRole = 'admin';
        $stmt->bindParam(':role', $adminRole);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Downgrade admin to user role
    public function demoteToUser() {
        $query = 'UPDATE ' . $this->table . ' SET role = :role WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $userRole = 'user';
        $stmt->bindParam(':role', $userRole);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
}

?>
