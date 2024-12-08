<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outcome Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="space">
        <div class="head">
            <div class="head">
                <label for="course">Select Course</label>
                <select id="course" name="course" aria-label="Select a course" style="padding-right:10px;"`>
                    <!-- Options of course code will be displayed here dynamically -->
                </select>
            </div>
        </div>
        <div class="container">
            <!-- Outcome Submission Form -->
            <form class="innerDiv" id="outcomeForm" aria-labelledby="outcomeForm">
                <h2>Add Outcomes</h2>
                <label for="outcome">Outcome:</label>
                <input type="text" id="outcome" name="outcome" required placeholder="Enter outcome i.e CO1, CO2, etc" aria-describedby="outcome-help">
                
                <label for="outcomeText">Outcome Text:</label>
                <input type="text" id="outcomeText" name="outcomeText" required placeholder="Enter outcome description" aria-describedby="outcome-help">

                <button type="submit" aria-label="Submit new outcome">Submit</button>
            </form>
        
            <!-- Outcomes List -->
            <div class="innerDiv" >
                <!-- <h3>Outcomes List</h3> -->
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
    </div>
    <script src="assets/js/outcomes.js"></script>
</body>
</html>
