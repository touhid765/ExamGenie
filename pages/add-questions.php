<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Management</title>
    <link rel="stylesheet" href="assets/css/dark-theme.css">
</head>
<body>
    <div>
        <!-- Select Course Section -->
        <div>
            <h2>Question Management</h2>

            <label for="course">Select Course:</label>
            <select id="course" name="course" aria-label="Select a course">
                <!-- Options of courses will be displayed here dynamically -->
            </select>
        </div>

        <!-- Question Submission Form -->
        <form id="questionForm" aria-labelledby="questionForm">
            <label for="questionText">Question Text:</label>
            <textarea id="questionText" name="questionText" required placeholder="Enter question text" aria-describedby="question-help"></textarea>
            <small id="question-help">Provide the full question text. Use back tick, Don't use single or double quotes.</small>

            <label for="marks">Marks:</label>
            <input type="number" id="marks" name="marks" required placeholder="Enter marks for the question" aria-describedby="marks-help">
            <small id="marks-help">Specify the total marks for this question.</small>

            <label for="level">Level:</label>
            <select id="level" name="level" aria-label="Select question difficulty level">
                <option value="L1">L1</option>
                <option value="L2">L2</option>
                <option value="L3">L3</option>
                <option value="L4">L4</option>
                <option value="L5">L5</option>
            </select>

            <label for="outcome">Outcome:</label>
            <select id="outcome" name="outcome" aria-label="Link to an outcome">
                <!-- Options of outcomes will be displayed here dynamically -->
            </select>

            <button type="submit" aria-label="Submit new question">Submit</button>
        </form>
    </div>
    <div class="container">

        <!-- Questions List -->
        <div>
            <h3>Questions List</h3>
            <ul id="questions-list">
                <!-- List of questions will be displayed here dynamically -->
            </ul>
        </div>

        <!-- Edit Question Modal -->
        <div id="editQuestionModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Question</h2>
                <form id="editQuestionForm">
                    <label for="editQuestionText">Question Text:</label>
                    <textarea id="editQuestionText" rows="4" required></textarea>
                    <small id="question-help">Edit question text. Use back tick, Don't use single or double quotes.</small>

                    <label for="editMarks">Marks:</label>
                    <input type="number" id="editMarks" required />

                    <label for="editLevel">Level:</label>
                    <select id="editLevel">
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                        <option value="L4">L4</option>
                        <option value="L5">L5</option>
                    </select>

                    <label for="editOutcome">Outcome:</label>
                    <select id="editOutcome" name="editOutcome">
                        <!-- Dynamic outcomes -->
                    </select>

                    <input type="hidden" id="editQuestionId" />
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/questions.js"></script>
</body>
</html>
