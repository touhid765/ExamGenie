<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="space">
        <div class="container">
            <div class="innerDiv" style="max-height: 63vh;" id="add-course">
                <h2>Add Courses</h2>
                <!-- Form Section for Adding Course -->
                <form id="add-course-form">
                    <label for="course_name">Course Name:</label>
                    <input type="text" id="course_name" name="course_name" required>
                    
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code" required pattern="[A-Z]{3}\d{3}" 
                        title="Course code should be in the format ABC123">
                    
                    <label for="credits">Credits:</label>
                    <input type="number" id="credits" name="credits" required min="1" 
                        title="Credits must be a positive number">
                    
                    <button type="submit">Add Course</button>
                </form>
            </div>

            <div class="innerDiv" style="max-height: 85vh; padding:0;" id="submitted-course">
                <table style="margin:0;" id="courses-table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Credits</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Course rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Edit Course Modal -->
            <div id="edit-modal" class="modal">
                <div class="modal-content" style="width: 40%; height: 60%; margin: 10% auto;">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Course</h2>
                    <form id="edit-form">
                        <label for="edit_course_name">Course Name:</label>
                        <input type="text" id="edit_course_name" name="course_name" required>
                        
                        <label for="edit_course_code">Course Code:</label>
                        <input type="text" id="edit_course_code" name="course_code" required pattern="[A-Z]{3}\d{3}">
                        
                        <label for="edit_credits">Credits:</label>
                        <input type="number" id="edit_credits" name="credits" required min="1">
                        
                        <input type="hidden" id="edit_course_id" name="id">
                        
                        <button type="submit">Save Changes</button>
                    </form>
                </div>
            </div>
        
        </div>
    </div>
    <script src="assets/js/courses.js"></script>
</body>
</html>
