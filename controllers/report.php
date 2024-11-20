<?php

// Include the constants.php file
include '../assets/utils/constants.php'; //$servername, $username, $password, $dbname

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the 'reports' table if it does not exist
$tableCreationQuery = "
    CREATE TABLE IF NOT EXISTS reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(100) NOT NULL,
        year INT NOT NULL,
        question_id TEXT NOT NULL
    );
";

if (!mysqli_query($conn, $tableCreationQuery)) {
    die("Error creating table: " . mysqli_error($conn));
}

// Helper function to send JSON responses
function sendResponse($data = [], $statusCode = 200, $message = '') {
    header("Content-Type: application/json");
    http_response_code($statusCode);
    echo json_encode(['message' => $message, 'data' => $data]);
    exit;
}

// Handle incoming requests based on HTTP method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Fetch reports based on optional filters
        $urlParams = $_GET;
        $course_code = isset($urlParams['course_code']) ? $urlParams['course_code'] : null;
        $year = isset($urlParams['year']) ? (int)$urlParams['year'] : null;
    
        // Query to fetch reports based on course_code and year
        $query = "SELECT * FROM reports";
        $params = [];
    
        if ($course_code || $year) {
            $query .= " WHERE ";
            if ($course_code) {
                $query .= "course_code = ?";
                $params[] = $course_code;
            }
            if ($year) {
                if ($course_code) {
                    $query .= " AND ";
                }
                $query .= "year = ?";
                $params[] = $year;
            }
        }
    
        $stmt = mysqli_prepare($conn, $query);
    
        if (!empty($params)) {
            $types = "si"; // Corrected the type string to match the parameters
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
    
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $reports = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $report = $row;
            
            // Fetch questions related to this report's question_ids
            $questionIds = explode(",", $row['question_id']);
            
            $questions = [];
            foreach ($questionIds as $qid) {
                $questionQuery = "
                    SELECT 
                        q.id, 
                        q.course_code, 
                        q.outcome_id, 
                        q.question_text, 
                        q.marks, 
                        q.level, 
                        o.outcome_text
                    FROM 
                        questions q
                    LEFT JOIN 
                        outcomes o 
                    ON 
                        q.outcome_id = o.id
                    WHERE 
                        q.id = ?
                ";
    
                $questionStmt = mysqli_prepare($conn, $questionQuery);
                mysqli_stmt_bind_param($questionStmt, "i", $qid);
                mysqli_stmt_execute($questionStmt);
                $questionResult = mysqli_stmt_get_result($questionStmt);
    
                while ($question = mysqli_fetch_assoc($questionResult)) {
                    $questions[] = $question;
                }
            }
    
            // Add the questions to the report
            $report['questions'] = $questions;
            $reports[] = $report;
        }
    
        sendResponse(['reports' => $reports], 200, 'Reports fetched successfully');
        break;
    

    case 'POST':
        // Add or update a report
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['course_code'], $inputData['year'], $inputData['question_id']) ||
            empty($inputData['course_code']) || !is_numeric($inputData['year']) || empty($inputData['question_id'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $course_code = $inputData['course_code'];
        $year = (int)$inputData['year'];
        $question_id = $inputData['question_id']; // Comma-separated list of question IDs

        // Check if the report already exists
        $checkQuery = "SELECT * FROM reports WHERE course_code = ? AND year = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "si", $course_code, $year);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Report exists, append new question IDs
            $existingQuestionIds = $row['question_id'];
            $newQuestionIds = array_unique(array_merge(
                explode(",", $existingQuestionIds),
                explode(",", $question_id)
            ));
            $updatedQuestionIds = implode(",", $newQuestionIds);

            $updateQuery = "UPDATE reports SET question_id = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "si", $updatedQuestionIds, $row['id']);
            mysqli_stmt_execute($updateStmt);

            sendResponse([], 200, 'Report updated successfully');
        } else {
            // Create a new report
            $insertQuery = "INSERT INTO reports (course_code, year, question_id) VALUES (?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "sis", $course_code, $year, $question_id);
            mysqli_stmt_execute($insertStmt);

            sendResponse([], 201, 'Report created successfully');
        }
        break;

    case 'PUT':
        // Update a report by its ID
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['id'], $inputData['question_id']) ||
            !is_numeric($inputData['id']) || empty($inputData['question_id'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $id = (int)$inputData['id'];
        $question_id = $inputData['question_id']; // Comma-separated list of question IDs

        $updateQuery = "UPDATE reports SET question_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $question_id, $id);
        if (mysqli_stmt_execute($stmt)) {
            sendResponse([], 200, 'Report updated successfully');
        } else {
            sendResponse([], 500, 'Failed to update report');
        }
        break;

    default:
        sendResponse([], 405, 'Method not allowed');
}

?>
