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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all papers
    public function getAllPapers()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
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
    
    // Update paper
    public function updatePaper()
    {
        // Validate status
        if (!in_array($this->status, ['pending', 'approved', 'rejected'])) {
            echo "Invalid status. Status must be one of 'pending', 'approved', or 'rejected'.";
            return false;
        }

        $query = 'UPDATE ' . $this->table . ' 
                  SET title = :title, category = :category, department = :department, 
                      course = :course, subject = :subject, tags = :tags, year = :year, 
                      file_path = :file_path, uploaded_by = :uploaded_by, status = :status
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':tags', $this->tags);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':file_path', $this->file_path);
        $stmt->bindParam(':uploaded_by', $this->uploaded_by);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    // Approve paper (update status to 'approved')
    public function approvePaper()
    {
        $this->status = 'approved';
        return $this->updatePaper(); // Call updatePaper method to apply changes
    }

    // Delete paper
    public function deletePaper()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
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
        $query = 'SELECT * FROM ' . $this->table . ' WHERE category = :category AND department = :department AND course = :course';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':course', $course);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get papers uploaded by a specific user
    public function getPapersByUser($userId)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE uploaded_by = :userId';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Search papers by title
    public function searchPapers($keyword)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE title LIKE :keyword';
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%'; // Add wildcards for partial matching
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get papers by tag
    public function getPapersByTag($tag)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE tags LIKE :tag';
        $stmt = $this->conn->prepare($query);
        $tag = '%' . $tag . '%'; // Add wildcards for partial matching
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Search papers by tag
    public function searchPapersByTag($tag)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE tags LIKE :tag';
        $stmt = $this->conn->prepare($query);
        $tag = '%' . $tag . '%'; // Add wildcards for partial matching
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get popular papers by views
    public function getPopularPapersByViews($limit = 10)
    {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY view_count DESC LIMIT :limit';
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
    
        // Method to get papers sorted by upload date
   // Inside the Paper class

public function getPapersSortedByDateAndCategory($category, $department, $course, $order) {
    // Prepare SQL query to fetch papers based on category, department, and course sorted by date
    $query = "SELECT * FROM papers WHERE category = :category AND department = :department AND course = :course ORDER BY upload_date $order";
    
    // Prepare and execute the query
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':course', $course);
    $stmt->execute();

    // Fetch all papers as an associative array
    $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched papers
    return $papers;
}

public function getPapersSortedByViewCountAndCategory($category, $department, $course) {
    // Prepare SQL query to fetch papers based on category, department, and course sorted by view count
    $query = "SELECT * FROM papers WHERE category = :category AND department = :department AND course = :course ORDER BY view_count DESC";
    
    // Prepare and execute the query
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':course', $course);
    $stmt->execute();

    // Fetch all papers as an associative array
    $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched papers
    return $papers;
}


}


?>
