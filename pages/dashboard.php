<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Question Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="space">
        
        <!-- Course and Year Selection -->
        <div class="head">
            <label><strong>Welcome (●'◡'●)</strong></label>
            <div>
                <label for="course">Select Course</label>
                <select id="course" name="course">
                    <!-- Dynamic course options will be added here -->
                </select>
            </div>

            <div>
                <label for="year">Select Year</label>
                <select id="year" name="year">
                    <!-- Dynamic year options will be added here -->
                </select>
            </div>
        </div>

        <div class="container">
            <div class="innerDiv" style="padding: 10px 0 0 0;">
                <!-- Question Filters -->
                <form id="filterForm" class="head" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <label for="marks" style="margin-bottom: 20px;">Marks:</label>
                    <select id="marks" name="marks" style="margin-right: 10px; padding: 8px 8px;">
                        <!-- dynamically generated options -->
                    </select>

                    <label for="level" style="margin-bottom: 20px;">Level:</label>
                    <select id="level" name="level" style="margin-right: 10px; padding: 8px 8px;">
                        <option value="">Select Level</option>
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                        <option value="L4">L4</option>
                        <option value="L5">L5</option>
                    </select>

                    <label for="outcome" style="margin-bottom: 20px;">Outcome:</label>
                    <select id="outcome" name="outcome" style="margin-right: 10px; padding: 8px 8px;">
                        <!-- dynamically generated options -->
                    </select>

                    <button type="button" id="filterBtn" style="padding: 10px 15px; margin-bottom: 15px;">Filter</button>
                </form>


                <!-- Filtered Questions -->
                <table style="margin:0;" id="question-list">
                    <tbody>
                        <!-- Questions rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <div class="innerDiv" style="padding: 0;">
                <table class="question-list" id="report" style="margin:0;">
                    <tbody>
                        <!-- Questions rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
