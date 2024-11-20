<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outcome Management</title>
    <link rel="stylesheet" href="assets/css/dark-theme.css">
</head>
<body>
    <div>
        <!-- Select Course Section -->
        <div>
            <h2>Outcomes</h2>

            <label for="course">Select Course:</label>
            <select id="course" name="course" aria-label="Select a course">
                <!-- Options of course code will be displayed here dynamically -->
            </select>
        </div>

        <!-- Outcome Submission Form -->
        <form id="outcomeForm" aria-labelledby="outcomeForm">
            <label for="outcome">Outcome:</label>
            <input type="text" id="outcome" name="outcome" required placeholder="Enter outcome description" aria-describedby="outcome-help">
            <small id="outcome-help">Provide a brief description of the outcome.</small>
            <button type="submit" aria-label="Submit new outcome">Submit</button>
        </form>
    </div>
    <div class="container">

        <!-- Outcomes List -->
        <div>
            <h3>Outcomes List</h3>
            <ul id="outcomes-list">
                <!-- List of outcomes will be displayed here dynamically -->
            </ul>
        </div>

        <!-- Edit Outcome Modal -->
        <div id="editOutcomeModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Outcome</h2>
                <form id="editOutcomeForm">
                    <label for="editOutcomeText">Outcome Text</label>
                    <textarea id="editOutcomeText" rows="4" required></textarea>
                    <input type="hidden" id="editOutcomeId" />
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>

    </div>
    <script src="assets/js/outcomes.js"></script>
</body>
</html>
