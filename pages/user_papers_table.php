<?php
echo "<style>tr{
    border: 2px solid !important;
    border-color: var(--green) !important ;
}
td,th{
    padding-left: 10px !important;

}
.btn-group .btn {
margin-right: 10px; /* Adjust the value as needed */
}
.btn {
min-height: 25px;
max-width: max-content;
font-size: var(--fs-5);
font-weight: var(--fw-700);
display: flex;
align-items: center;
gap: 5px;
padding: 8px 15px;
border-radius: var(--radius-6);
transition: var(--transition-1);
margin-bottom: 12px;

}
.btn:hover{
background-color: var(--green);
color: white;
}
.btn.active{
background-color: var(--green);
color: white;
}
span a:hover,.ion-icon-container:hover {
color: var(--ultramarine-blue) !important ;
}

.error-text {
color: #fff;
/* color: var(--ultramarine-blue); */
padding: 5px 15px;
text-align: center;
border-radius: 6px;
background: var(--ultramarine-blue);
border: 1px solid #f5c6cb;
margin-bottom: 20px;
display: none;
width: 50rem;
}</style>";

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize User object
$paper = new Paper($conn);

// Fetch all users
$papers = $paper->getPapersByUser($email);
if (!empty($papers)){
?>

    <div class="container">
        <h1 class="h3 mb-3" style="padding:20px 30px">Uploaded by you</h1>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <!-- <th>Title</th> -->
                                    
                                    <th>Category</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Year</th>
                                    <th>View</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Loop through each paper and display its details
                                foreach ($papers as $paper) {
                                    $uploadDate = date('d F Y', strtotime($paper['upload_date']));
                                    $msgStatus="";
                                    if ($paper['status']=="rejected"){
                                        $msgStatus="<p style='font-size:13px'> {$paper['course']} {$paper['year']} of {$paper['department']} Department  upload {$paper['status']} reason: ".$paper['subject']."</p>";
                                    }
                                    echo "<tr>";
                                    // echo "<td>{$paper['title']}</td>";
                                    echo "<td>{$paper['category']}</td>";
                                    echo "<td>{$paper['department']}</td>";
                                    echo "<td>{$paper['course']}</td>";
                                    echo "<td>{$paper['year']}</td>";
                                    echo "<td><span class='ion-icon-container'><a href='{$paper['file_path']}' target='_blank'><ion-icon name='eye' size='large'></ion-icon></a></span></td>";
                                    echo "<td>{$paper['status']}</td>";
                                    echo "<td>{$uploadDate}</td>";
                                    echo $msgStatus;
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
    <?php
}
?>