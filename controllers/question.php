<?php

// Include the constants.php file
include '../assets/utils/constants.php';  //$servername, $username, $password, $dbname

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the 'questions' table if it does not exist
$tableCreationQuery = "
    CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(100) NOT NULL,
        outcome_id INT NOT NULL,
        question_text TEXT NOT NULL,
        marks INT NOT NULL,
        level VARCHAR(50) NOT NULL
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

// Function to fetch questions from the database with outcome_text
function fetchQuestions($course_code = null) {
    global $conn;

    // Base query with a LEFT JOIN to include outcome_text
    $query = "
        SELECT 
            q.id, 
            q.course_code, 
            q.outcome_id, 
            q.question_text, 
            q.marks, 
            q.level, 
            o.outcome
        FROM 
            questions q
        LEFT JOIN 
            outcomes o 
        ON 
            q.outcome_id = o.id
    ";

    // Add filters if course_code is provided
    if ($course_code) {
        $query .= " WHERE q.course_code = ?";
    }

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    if ($course_code) {
        mysqli_stmt_bind_param($stmt, "s", $course_code);
    }

    // Execute the statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Process the result
    $questions = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $questions[] = $row;
        }
    } else {
        // Handle query failure
        error_log("Query Error: " . mysqli_error($conn));
        return ['questions' => [], 'message' => 'Failed to fetch questions'];
    }

    return ['questions' => $questions];
}


// Handle incoming requests based on HTTP method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Fetch questions with optional filters
        $urlParams = $_GET;
        $course_code = isset($urlParams['course_code']) ? $urlParams['course_code'] : null;
        $questions = fetchQuestions($course_code);
        sendResponse($questions, 200, 'Questions fetched successfully');
        break;

    case 'POST':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['course_code'], $inputData['outcome_id'], $inputData['question_text'], $inputData['marks'], $inputData['level']) ||
            empty($inputData['course_code']) || empty($inputData['outcome_id']) || empty($inputData['question_text']) ||
            !is_numeric($inputData['marks']) || empty($inputData['level'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $course_code = $inputData['course_code'];
        $outcome_id = (int)$inputData['outcome_id'];
        $question_text = $inputData['question_text'];
        $marks = (int)$inputData['marks'];
        $level = $inputData['level'];

        $query = "
            INSERT INTO questions (course_code, outcome_id, question_text, marks, level)
            VALUES ('$course_code', $outcome_id, '$question_text', $marks, '$level')
        ";
        if (mysqli_query($conn, $query)) {
            $questions = fetchQuestions();
            sendResponse($questions, 201, 'Question added successfully');
        } else {
            sendResponse([], 500, 'Failed to add question');
        }
        break;

    case 'PUT':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($inputData['id'], $inputData['outcome_id'], $inputData['question_text'], $inputData['marks'], $inputData['level']) ||
            !is_numeric($inputData['id']) || !is_numeric($inputData['outcome_id']) || empty($inputData['question_text']) ||
            !is_numeric($inputData['marks']) || empty($inputData['level'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $id = (int)$inputData['id'];
        $outcome_id = (int)$inputData['outcome_id'];
        $question_text = $inputData['question_text'];
        $marks = (int)$inputData['marks'];
        $level = $inputData['level'];
        
        $query = "
            UPDATE questions
            SET outcome_id = $outcome_id, question_text = '$question_text', marks = $marks, level = '$level'
            WHERE id = $id
        ";
        if (mysqli_query($conn, $query)) {
            $questions = fetchQuestions();
            sendResponse($questions, 200, 'Question updated successfully');
        } else {
            sendResponse([], 500, 'Failed to update question');
        }
        break;

    case 'DELETE':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $id = (int)$inputData['id'];
        $query = "DELETE FROM questions WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $questions = fetchQuestions();
            sendResponse($questions, 200, 'Question deleted successfully');
        } else {
            sendResponse([], 500, 'Failed to delete question');
        }
        break;

    default:
        sendResponse([], 405, 'Method not allowed');
}

?>
