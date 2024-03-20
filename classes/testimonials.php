<?php

class Testimonial {
    private $conn;
    private $table = 'testimonials';

    public $id;
    public $name;
    public $email;
    public $mobile_number;
    public $message;
    public $submission_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all testimonials
    public function getAllTestimonials() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single testimonial by ID
    public function getTestimonialById() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Add a new testimonial
    public function addTestimonial() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET name = :name, email = :email, mobile_number = :mobile_number, message = :message';
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile_number = htmlspecialchars(strip_tags($this->mobile_number));
        $this->message = htmlspecialchars(strip_tags($this->message));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mobile_number', $this->mobile_number);
        $stmt->bindParam(':message', $this->message);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update an existing testimonial
    public function updateTestimonial() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET name = :name, email = :email, mobile_number = :mobile_number, message = :message
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile_number = htmlspecialchars(strip_tags($this->mobile_number));
        $this->message = htmlspecialchars(strip_tags($this->message));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mobile_number', $this->mobile_number);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete a testimonial
    public function deleteTestimonial() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}

?>
<!-- 
// Include the database class file
include_once 'path_to_database_class/Database.php';

// Include the testimonial class file
include_once 'path_to_testimonial_class/Testimonial.php';

// Create a new instance of the Database class
$database = new Database();

// Get the database connection
$conn = $database->getConnection();

// Create a new instance of the Testimonial class
$testimonial = new Testimonial($conn);

// Set testimonial details
$testimonial->name = 'John Doe';
$testimonial->email = 'john@example.com';
$testimonial->mobile_number = '1234567890';
$testimonial->message = 'This is a great product. Highly recommended!';

// Add the testimonial
if ($testimonial->addTestimonial()) {
    echo 'Testimonial added successfully';
} else {
    echo 'Failed to add testimonial';
} -->
