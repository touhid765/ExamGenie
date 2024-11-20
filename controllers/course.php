<?php

// Include the constants.php file
include '../assets/utils/constants.php';  //$servername, $username, $password, $dbname

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the 'courses' table if it does not exist, including the 'program' field
$tableCreationQuery = "
    CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_name VARCHAR(255) NOT NULL,
        course_code VARCHAR(100) NOT NULL UNIQUE,
        credits INT NOT NULL,
        program VARCHAR(255) NOT NULL  -- New column for program name
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

// Function to fetch courses from the database
function fetchCourses($program = null) {
    global $conn;
    $query = "SELECT * FROM courses";  // Fetch all courses by default

    // If a program is specified, filter courses by program
    if ($program) {
        $query .= " WHERE program = '$program'";  // Adjust query to include filtering by program
    }

    $result = mysqli_query($conn, $query);
    $courses = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    }
    return ['courses' => $courses];
}

// Handle incoming requests based on HTTP method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Fetch the list of courses from DB with optional program filter
        $urlParams = $_GET;
        $program = isset($urlParams['program']) ? $urlParams['program'] : null;
        $courses = fetchCourses($program);  // Pass the program if available
        sendResponse($courses, 200, 'Courses fetched successfully');
        break;

    case 'POST':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['course_name'], $inputData['course_code'], $inputData['credits'], $inputData['program']) ||
            empty($inputData['course_name']) || empty($inputData['course_code']) ||
            !is_numeric($inputData['credits']) || (int)$inputData['credits'] <= 0 || empty($inputData['program'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $course_name = $inputData['course_name'];
        $course_code = $inputData['course_code'];
        $credits = (int)$inputData['credits'];
        $program = $inputData['program'];

        $query = "INSERT INTO courses (course_name, course_code, credits, program) VALUES ('$course_name', '$course_code', $credits, '$program')";
        if (mysqli_query($conn, $query)) {
            $courses = fetchCourses();
            sendResponse($courses, 201, 'Course added successfully');
        } else {
            if (mysqli_errno($conn) === 1062) {
                sendResponse([], 400, 'Course code already exists');
            } else {
                sendResponse([], 500, 'Failed to add course');
            }
        }
        break;

    case 'PUT':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['id'], $inputData['course_name'], $inputData['course_code'], $inputData['credits'], $inputData['program']) ||
            empty($inputData['course_name']) || empty($inputData['course_code']) ||
            !is_numeric($inputData['credits']) || (int)$inputData['credits'] <= 0 || empty($inputData['program'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $id = (int)$inputData['id'];
        $course_name = $inputData['course_name'];
        $course_code = $inputData['course_code'];
        $credits = (int)$inputData['credits'];
        $program = $inputData['program'];

        $query = "UPDATE courses SET course_name = '$course_name', course_code = '$course_code', credits = $credits, program = '$program' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $courses = fetchCourses();
            sendResponse($courses, 200, 'Course updated successfully');
        } else {
            sendResponse([], 500, 'Failed to update course');
        }
        break;

    case 'DELETE':
        // Read and decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
            sendResponse([], 400, 'Invalid or missing input fields');
        }

        $id = (int)$inputData['id'];
        $query = "DELETE FROM courses WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $courses = fetchCourses();
            sendResponse($courses, 200, 'Course deleted successfully');
        } else {
            sendResponse([], 500, 'Failed to delete course');
        }
        break;

    default:
        sendResponse([], 405, 'Method not allowed');
}
?>
