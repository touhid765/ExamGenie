<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamGenie - A Question Setting Software</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>ExamGenie</h1>
        <!-- Dropdown with onChange event to trigger action -->
        <select name="profile_type" onchange="redirectToProgramPage(this.value)">
            <option value="">Select Program</option>
            <option value="BCA" <?php echo (isset($_GET['program']) && $_GET['program'] == 'BCA') ? 'selected' : ''; ?>>BCA</option>
            <option value="MCA" <?php echo (isset($_GET['program']) && $_GET['program'] == 'MCA') ? 'selected' : ''; ?>>MCA</option>
            <option value="M.Tech" <?php echo (isset($_GET['program']) && $_GET['program'] == 'M.Tech') ? 'selected' : ''; ?>>M.Tech</option>
            <option value="B.Tech" <?php echo (isset($_GET['program']) && $_GET['program'] == 'B.Tech') ? 'selected' : ''; ?>>B.Tech</option>
        </select>
    </header>

    <!-- Sidebar Section -->
    <aside>
        <nav>
            <ul>
                <li><a href="?page=dashboard&program=<?php echo isset($_GET['program']) ? $_GET['program'] : ''; ?>">Dashboard</a></li>
                <li><a href="?page=course-management&program=<?php echo isset($_GET['program']) ? $_GET['program'] : ''; ?>">Course Management</a></li>
                <li><a href="?page=outcome-management&program=<?php echo isset($_GET['program']) ? $_GET['program'] : ''; ?>">Outcome Management</a></li>
                <li><a href="?page=question-management&program=<?php echo isset($_GET['program']) ? $_GET['program'] : ''; ?>">Question Management</a></li>
                <li><a href="?page=report-generation&program=<?php echo isset($_GET['program']) ? $_GET['program'] : ''; ?>">Report Generation</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        $allowed_pages = ['dashboard', 'course-management', 'outcome-management', 'question-management', 'report-generation'];
        if (in_array($page, $allowed_pages)) {
            include("pages/$page.php");
        } else {
            echo "<h2>Page not found</h2>";
        }
        ?>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Question Setting Software</p>
    </footer>

    <script>
        // Function to redirect to a page with selected program
        function redirectToProgramPage(selectedProgram) {
            // If no program is selected, just return (do nothing)
            if (!selectedProgram) return;
            
            // Get the current URL query parameters, including the 'page' and 'program'
            const urlParams = new URLSearchParams(window.location.search);
            
            // Check if 'page' is set in the query string and if it's in the allowed pages list
            const currentPage = urlParams.get('page') || 'dashboard';
            const allowedPages = ['dashboard', 'course-management', 'outcome-management', 'question-management', 'report-generation'];

            // If the current page is allowed, proceed with redirect
            if (allowedPages.includes(currentPage)) {
                // Update the URL with the selected program
                window.location.href = `?page=${currentPage}&program=${selectedProgram}`;
            } else {
                console.log("Invalid page");
            }
        }
    </script>
</body>
</html>
