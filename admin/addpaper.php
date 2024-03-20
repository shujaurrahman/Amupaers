<?php
require_once "../includes/departments.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Paper</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/form.css">
    <style>
        #login #formLogin {
            padding: 0px 0px;
        }
        #login #formLogin .inputContainer .inputLogin {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php require_once "../includes/header.php"; ?>
    <section class="form login">
        <div id="login">
            <div id="formLogin">
                <h1>Add Paper →</h1>
                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="error-text"></div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="title">
                        <label class="labelLogin">Title</label>
                    </div>
                    <div class="inputContainer">
                        <select name="category" class="inputLogin">
                            <option value="sessional">Sessional</option>
                            <option value="semester">Semester</option>
                            <option value="entrance">Entrance</option>
                        </select>
                        <label class="labelLogin">Category</label>
                    </div>
                    <div class="inputContainer">
                        <select name="department" class="inputLogin" id="departmentSelect">
                            <!-- Department options -->
                            <?php
                            foreach ($departmentCourses as $department => $courses) {
                                echo "<option value='$department'>$department</option>";
                            }
                            ?>
                        </select>
                        <label class="labelLogin">Department</label>
                    </div>
                    <div class="inputContainer">
                        <select name="course" class="inputLogin" id="courseSelect">
                            <!-- Course options will be dynamically added here -->
                        </select>
                        <label class="labelLogin">Course</label>
                    </div>
                    
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="subject">
                        <label class="labelLogin">Subject</label>
                    </div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="tags">
                        <label class="labelLogin">Tags (comma-separated)</label>
                    </div>
                    <div class="inputContainer">
                        <input type="number" class="inputLogin" placeholder=" " name="year">
                        <label class="labelLogin">Year</label>
                    </div>
                    <div class="field button">
                        <input type="submit" class="submitButton" value="Add Paper">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
    // Department and associated courses data
    var departmentCourses = <?php echo json_encode($departmentCourses); ?>;

 // Function to update courses dropdown based on selected department
function updateCourses() {
    var departmentSelect = document.getElementById("departmentSelect");
    var courseSelect = document.getElementById("courseSelect");
    var selectedDepartment = departmentSelect.value;

    // Find courses for departments with partially matching names
    var courses = [];
    for (var department in departmentCourses) {
        if (department.toLowerCase().includes(selectedDepartment.toLowerCase())) {
            courses = courses.concat(departmentCourses[department]);
        }
    }

    // Clear previous options and add new options to the courses dropdown
    courseSelect.innerHTML = "";
    courses.forEach(function(course) {
        var option = document.createElement("option");
        option.value = course;
        option.textContent = course;
        courseSelect.appendChild(option);
    });
}


    // Call updateCourses() initially to populate courses dropdown based on initial selected department
    updateCourses();

    // Add event listener to department dropdown to update courses when department selection changes
    document.getElementById("departmentSelect").addEventListener("change", updateCourses);
</script>




</body>
</html>
