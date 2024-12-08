<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="space">
        <!-- Select Course Section -->
        <div class="head">
            <div>
                <label for="course">Select Course</label>
                <select id="course" name="course" aria-label="Select a course">
                    <!-- Options of courses will be displayed here dynamically -->
                </select>
            </div>
        </div>

        <div class="container">

            <!-- Question Submission Form -->
            <form class="innerDiv" id="questionForm" aria-labelledby="questionForm">
                <label for="questionText">Question Text:</label>
                <textarea style="max-width: 100%; min-width: 100%; height:316px; max-height:316px; min-height:316px;" id="questionText" name="questionText" required placeholder="Enter question text" aria-describedby="question-help"></textarea>
                <small id="question-help">Provide the full question text. Use back tick, Don't use single or double quotes.</small>

                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap; padding: 10px;">
                    <label for="marks" style="margin-bottom: 20px;">Marks:</label>
                    <input type="number" id="marks" name="marks" required placeholder="Enter marks" aria-describedby="marks-help" style="width:120px; padding: 8px; margin-right: 10px;">

                    <label for="level" style="margin-bottom: 20px;">Level:</label>
                    <select id="level" name="level" aria-label="Select question difficulty level" style="width:100px; margin-right: 10px; padding: 8px;">
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                        <option value="L4">L4</option>
                        <option value="L5">L5</option>
                    </select>

                    <label for="outcome" style="margin-bottom: 20px;">Outcome:</label>
                    <select id="outcome" name="outcome" aria-label="Link to an outcome" style="width:100px; margin-right: 10px; padding: 8px;">
                        <!-- Options of outcomes will be displayed here dynamically -->
                    </select>
                </div>


                <button type="submit" aria-label="Submit new question">Submit</button>
            </form>
        

            <!-- Questions List -->
            <div class="innerDiv" style="padding:0;">
                <table style="margin:0;" id="question-table">
                    <thead>
                        <tr>
                            <th>Questions</th>
                            <th>Attr.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Questions rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Edit Question Modal -->
            <div id="editQuestionModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Edit Question</h2>
                    <form id="editQuestionForm" style="display: flex; flex-direction: column; gap: 20px; padding: 10px; max-width: 700px; margin: auto;">
                        <label for="editQuestionText">Question Text:</label>
                        <textarea id="editQuestionText" name="editQuestionText" required placeholder="Edit question text" aria-describedby="question-help" style="max-width: 100%; min-width: 100%; height: 216px; max-height: 216px; min-height: 216px;"></textarea>
                        <small id="question-help" style="font-size: 12px; color: gray;">Edit question text. Use back tick, Don't use single or double quotes.</small>

                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap; padding: 10px;">
                            <label for="editMarks" style="margin-bottom: 20px;">Marks:</label>
                            <input type="number" id="editMarks" name="editMarks" required placeholder="Edit marks" style="width: 120px; padding: 8px; margin-right: 10px;">

                            <label for="editLevel" style="margin-bottom: 20px;">Level:</label>
                            <select id="editLevel" name="editLevel" aria-label="Edit question difficulty level" style="width: 100px; margin-right: 10px; padding: 8px;">
                                <option value="L1">L1</option>
                                <option value="L2">L2</option>
                                <option value="L3">L3</option>
                                <option value="L4">L4</option>
                                <option value="L5">L5</option>
                            </select>

                            <label for="editOutcome" style="margin-bottom: 20px;">Outcome:</label>
                            <select id="editOutcome" name="editOutcome" aria-label="Edit linked outcome" style="width: 100px; margin-right: 10px; padding: 8px;">
                                <!-- Dynamic outcomes -->
                            </select>
                        </div>

                        <input type="hidden" id="editQuestionId">
                        <button type="submit" style="padding: 10px; max-width: 150px; margin: auto;">Save Changes</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/questions.js"></script>
</body>
</html>
