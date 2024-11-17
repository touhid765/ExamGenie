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
        <select name="profile_type">
            <option value="program">Select Program</option>
            <option value="BCA">BCA</option>
            <option value="MCA">MCA</option>
            <option value="M.Tech">M.Tech</option>
        </select>
    </header>

    <!-- Sidebar Section -->
    <aside>
        <nav>
            <ul>
                <li><a href="?page=dashboard">Dashboard</a></li>
                <li><a href="?page=course-management">Course Management</a></li>
                <li><a href="?page=outcome-management">Outcome Management</a></li>
                <li><a href="?page=question-management">Question Management</a></li>
                <li><a href="?page=report-generation">Report Generation</a></li>
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
</body>
</html>