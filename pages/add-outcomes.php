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
                <textarea style="max-width: 100%; min-width: 100%; height:258px; max-height:258px;min-height:258px;" type="text" id="outcomeText" name="outcomeText" required placeholder="Enter outcome description" aria-describedby="outcome-help"></textarea>

                <button type="submit" aria-label="Submit new outcome">Submit</button>
            </form>
        
            <!-- Outcomes List -->
            <div class="innerDiv" style="padding:0;">
                <table style="margin:0;" id="outcome-table">
                    <thead>
                        <tr>
                            <th>CO</th>
                            <th>Text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Outcomes rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Edit Outcome Modal -->
            <div id="editOutcomeModal" class="modal">
                <div class="modal-content" style="height: 75%;">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Edit Outcome</h2>
                    <form id="editOutcomeForm">
                        <label for="editOutcome">Outcome</label>
                        <input type="text" id="editOutcome" />
                        <label for="editOutcomeText">Outcome Text</label>
                        <textarea style="max-width: 100%; min-width: 100%; height:216px; max-height:216px; min-height: 216px;" id="editOutcomeText" rows="4" required></textarea>
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
