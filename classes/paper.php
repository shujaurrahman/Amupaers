<?php

class Paper
{
    private $conn;
    private $table = 'papers';

    public $id;
    public $title;
    public $category;
    public $department;
    public $course;
    public $subject;
    public $tags;
    public $year;
    public $file_path;
    public $view_count;
    public $uploaded_by;
    public $upload_date;
    public $status='pending';

    public $updated_by;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all papers
    public function getAllPapers()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE status = "pending"';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPaper()
    {
        // Validate status
        if (!in_array($this->status, ['pending', 'approved', 'rejected'])) {
            echo "Invalid status. Status must be one of 'pending', 'approved', or 'rejected'.";
            return false;
        }
    
        // Check if subject is set, if not, set it to NULL
        if (!isset($this->subject) || empty($this->subject)) {
            $subject = null;
        } else {
            $subject = $this->subject;
        }
    
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET title = :title, category = :category, department = :department, 
                      course = :course, subject = :subject, tags = :tags, year = :year, 
                      file_path = :file_path, uploaded_by = :uploaded_by, status = :status';
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':subject', $subject); // Bind the subject variable
        $stmt->bindParam(':tags', $this->tags);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':file_path', $this->file_path);
        $stmt->bindParam(':uploaded_by', $this->uploaded_by);
        $stmt->bindParam(':status', $this->status);
    
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    public function updatePaperStatus($paper_id, $status) {
        // Validate status
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            echo "Invalid status. Status must be one of 'pending', 'approved', or 'rejected'.";
            return false;
        }
    
        // Prepare SQL query to update paper status
        $query = "UPDATE $this->table SET status = :status WHERE id = :id";
        
        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $paper_id);
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
    


    // Delete paper
    public function deletePaper($paperId)
    {
        // Fetch file path of the PDF associated with the paper
        $query = 'SELECT file_path FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $paperId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $filePath = $row['file_path'];

        // Delete the paper record from the database
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $paperId);

        if ($stmt->execute()) {
            // If deletion from the database is successful, delete the associated PDF file
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the PDF file
            }
            return true; // Return true if deletion is successful
        } else {
            // If deletion fails, print error message
            printf("Error: %s.\n", $stmt->error);
            return false; // Return false if deletion fails
        }
    }

    public function getPaperById($id)
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':id', $id);

        // Execute query
        $stmt->execute();

        // Fetch record
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->category = $row['category'];
        $this->department = $row['department'];
        $this->course = $row['course'];
        $this->tags = $row['tags'];
        $this->year = $row['year'];
        // Set other properties as needed

        return $row; // Return fetched record
    }

    public function updatePaper()
    {
        // Check if all necessary properties are set
        if (!isset($this->id, $this->title, $this->category, $this->department, $this->course, $this->tags, $this->year, $this->updated_by)) {
            return false;
        }

        // Query to update paper details without updating PDF file
        $query = 'UPDATE ' . $this->table . ' 
                    SET title = :title, category = :category, department = :department, 
                        course = :course, tags = :tags, year = :year, updated_by = :updated_by 
                    WHERE id = :id';

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':tags', $this->tags);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':updated_by', $this->updated_by);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Paper details updated successfully
        } else {
            printf("Error: %s.\n", $stmt->error); // Error occurred while updating paper details
            return false;
        }
    }
    public function updateReason()
    {
        // Check if all necessary properties are set
        if (!isset($this->id, $this->subject)) {
            return false;
        }

        // Query to update paper details without updating PDF file
        $query = 'UPDATE ' . $this->table . ' 
                    SET subject = :subject
                    WHERE id = :id';

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':subject', $this->subject);

        // Execute the query
        if ($stmt->execute()) {
            return true; 
        } else {
            printf("Error: %s.\n", $stmt->error); // Error occurred while updating paper details
            return false;
        }
    }

    // Get all departments by category
    public function getDepartmentsByCategory($category)
    {
        $query = 'SELECT DISTINCT department FROM ' . $this->table . ' WHERE category = :category';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get all courses by department
    public function getCoursesByDepartment($category, $department)
    {
        $query = 'SELECT DISTINCT course FROM ' . $this->table . ' WHERE category = :category AND department = :department';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':department', $department);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get papers by category, department, and course
    public function getPapersByCategoryDepartmentCourse($category, $department, $course)
    {
        $status = 'approved'; 
    
        $query = 'SELECT * FROM ' . $this->table . ' WHERE category = :category AND department = :department AND course = :course AND status = :status';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':status', $status); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMorePapers($offset, $limit) {
        // Perform database query to fetch more papers using $offset and $limit
        $query = 'SELECT * FROM papers LIMIT :offset, :limit';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get papers uploaded by a specific user
    public function getPapersByUser($email)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE uploaded_by = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search papers by title
    public function searchPapers($keyword)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE 
                  title LIKE :keyword OR 
                  course LIKE :keyword OR 
                  department LIKE :keyword OR 
                  category LIKE :keyword OR 
                  uploaded_by LIKE :keyword OR 
                  year LIKE :keyword OR 
                  tags LIKE :keyword';
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%'; // Add wildcards for partial matching
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllTags()
    {
        $query = 'SELECT tags FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Create an array to store unique tags
        $uniqueTags = array();
    
        // Loop through each row and extract tags
        foreach ($tags as $tagsString) {
            // Split tags by comma
            $tagsArray = explode(',', $tagsString);
            
            // Trim and add each tag to uniqueTags array
            foreach ($tagsArray as $tag) {
                $tag = trim($tag);
                // Convert tag to lowercase for case-insensitive comparison
                $tagLower = strtolower($tag);
                if (!empty($tag) && !in_array($tagLower, $uniqueTags)) {
                    $uniqueTags[] = $tagLower;
                }
            }
        }
    
        return $uniqueTags;
    }
    
    
    // Get papers by tag
    public function getPapersByTag($tag)
    {
        // Convert tag to lowercase for case insensitivity
        $tagLower = strtolower($tag);
    
        // Add wildcard characters for partial matching
        $tagWildcard = '%' . $tagLower . '%';
    
        $query = 'SELECT * FROM ' . $this->table . ' WHERE LOWER(tags) LIKE :tagLower OR LOWER(tags) LIKE :tagWildcard';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagLower', $tagLower);
        $stmt->bindParam(':tagWildcard', $tagWildcard);
        $stmt->execute();
        $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $papers;
    }
    
    // Get popular papers by views
    public function getPopularPapersByViews($limit = 100)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE status = "approved" ORDER BY view_count DESC LIMIT :limit';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Increment view count for a paper
    public function incrementViewCount($paperId)
    {
        $query = 'UPDATE ' . $this->table . ' SET view_count = view_count + 1 WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $paperId);
        $stmt->execute();
    }

    // Get view count for a paper
    public function getViewCount($paperId)
    {
        $query = 'SELECT view_count FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $paperId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['view_count'];
    }
    public function getRelatedPapersByTags($paperId, $limit = 5)
    {
        // Retrieve tags associated with the paper
        $query = 'SELECT tag_id FROM paper_tags WHERE paper_id = :paper_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // If paper has no tags, return empty array
        if (empty ($tags)) {
            return [];
        }

        // Find papers with similar tags
        $query = 'SELECT p.* 
                  FROM papers p
                  INNER JOIN paper_tags pt ON p.id = pt.paper_id
                  WHERE pt.tag_id IN (' . implode(',', $tags) . ')
                  AND p.id != :paper_id
                  GROUP BY p.id
                  ORDER BY COUNT(*) DESC
                  LIMIT :limit';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRelatedPapersByCourse($paperId, $limit = 5)
    {
        // Retrieve course of the specified paper
        $query = 'SELECT course FROM papers WHERE id = :paper_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_COLUMN);

        // Find papers with the same course
        $query = 'SELECT * FROM papers WHERE course = :course AND id != :paper_id LIMIT :limit';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRelatedPapersByDepartment($paperId, $limit = 5)
    {
        // Retrieve department of the specified paper
        $query = 'SELECT department FROM papers WHERE id = :paper_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->execute();
        $department = $stmt->fetch(PDO::FETCH_COLUMN);

        // Find papers with the same department
        $query = 'SELECT * FROM papers WHERE department = :department AND id != :paper_id LIMIT :limit';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get related papers based on multiple criteria
    public function getRelatedPapers($paperId, $limit = 5)
    {
        // Get related papers by tags
        $relatedByTags = $this->getRelatedPapersByTags($paperId, $limit);

        // Get related papers by course
        $relatedByCourse = $this->getRelatedPapersByCourse($paperId, $limit);

        // Get related papers by department
        $relatedByDepartment = $this->getRelatedPapersByDepartment($paperId, $limit);

        // Combine related papers from all criteria into a single array
        $relatedPapers = array_merge($relatedByTags, $relatedByCourse, $relatedByDepartment);

        // Count occurrences of each paper ID
        $counts = array_count_values(array_column($relatedPapers, 'id'));

        // Sort papers by number of occurrences (most matched first)
        arsort($counts);

        // Return top $limit papers
        return array_slice($counts, 0, $limit);
    }      
    
    public function getPapersSortedByDateAndCategory($category, $department, $course, $order) {
        $status = 'approved'; 
    
        // Prepare SQL query to fetch papers based on category, department, and course sorted by date
        $query = "SELECT * FROM papers WHERE category = :category AND department = :department AND course = :course AND status = :status ORDER BY upload_date $order";
        
        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':status', $status); // Add this line to bind status
        $stmt->execute();
    
        // Fetch all papers as an associative array
        $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Return the fetched papers
        return $papers;
    }
    
    public function getPapersSortedByViewCountAndCategory($category, $department, $course) {
        $status = 'approved'; 
        // Prepare SQL query to fetch papers based on category, department, and course sorted by view count
        $query = "SELECT * FROM papers WHERE category = :category AND department = :department AND course = :course AND status = :status ORDER BY view_count DESC";
        
        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':status', $status); // Add this line to bind status
        $stmt->execute();
    
        // Fetch all papers as an associative array
        $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Return the fetched papers
        return $papers;
    }
    

public function getPapersByStatus($status) {
    // Prepare the query
    $query = 'SELECT * FROM ' . $this->table . ' WHERE status = :status';
    
    // Prepare the statement
    $stmt = $this->conn->prepare($query);

    // Bind the status parameter
    $stmt->bindParam(':status', $status);

    // Execute the query
    $stmt->execute();

    // Return the fetched papers
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getTotalCourses()
{
    $query = 'SELECT COUNT(DISTINCT course) AS total_courses FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total_courses'];
}

public function getTotalDepartments()
{
    $query = 'SELECT COUNT(DISTINCT department) AS total_departments FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total_departments'];
}

public function getTotalPapers()
{
    $query = 'SELECT COUNT(*) AS total_papers FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total_papers'];
}


}


?>
