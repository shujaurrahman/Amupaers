<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classes/database.php';

$database = new Database();
$conn = $database->getConnection();

// Fetch all testimonials from the database
$sql = "SELECT * FROM `testimonials`";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <?php require_once "../assets/fonts.php";
  require_once "../includes/bootstrap.php"; ?>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .btn{
      min-height: 30px;
      padding: 4px 8px;
      font-size: var(--fs-7);
      font-weight: var(--fw-400);
      border-radius: 6px;
      background-color: var(--ultramarine-blue)
    }
    .btn:hover{
      background-color: var(--green);
    }
    ul{
      padding-top: 20px;
    }
    p{
      padding-top: 100px;
    }
  </style>
</head>

<body>
  <?php require_once "../includes/adminNav.php"; ?>

  <section class="section category" aria-label="category">
    <div class="container my-5">
      <?php
      // Check if there are testimonials
      if ($stmt->rowCount() > 0) {
        // Output data of each row
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <ul>
          <li class="my-3">
            <div class="card border-dark mb-8 my-10" style="max-width: 100rem;">
              <div class="card-header" style="background-color: var(--ultramarine-blue); color: white;">
                <?php echo $row['subject']; ?>
              </div>
              <div class="card-body">
                <h5 class="card-title my-2">Date:
                  <?php echo $row['submission_date']; ?> | Name:
                  <?php echo $row['name']; ?> | Email:
                  <?php echo $row['email']; ?> | Phone:
                  <?php echo $row['mobile_number']; ?>
                </h5>
                <p class="card-text my-2">
                  <?php echo $row['message']; ?>
                </p>
                <form action="../backend/delete_testimonial.php" method="post">
                  <input type="hidden" name="testimonial_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="btn btn-dark">Delete</button>
                </form>
              </div>
            </div>
          </li>
          </ul>
          <?php
        }
      } else {
        echo "<p>No testimonials found.</p>";
      }
      ?>
    </div>
  </section>

</body>

</html>