<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamGenie - A Question Setting Software</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Exam<i class="fa-brands fa-google fa-bounce"></i>enie</h1>
        <div>
            <!-- Dropdown with onChange event to trigger action -->
            <select name="profile_type" onchange="redirectToProgramPage(this.value)">
                <option value="BCA" <?php echo (isset($_GET['program']) && $_GET['program'] == 'BCA') ? 'selected' : ''; ?>>BCA</option>
                <option value="MCA" <?php echo (isset($_GET['program']) && $_GET['program'] == 'MCA') ? 'selected' : ''; ?>>MCA</option>
                <option value="M.Tech" <?php echo (isset($_GET['program']) && $_GET['program'] == 'M.Tech') ? 'selected' : ''; ?>>M.Tech</option>
                <option value="B.Tech" <?php echo (isset($_GET['program']) && $_GET['program'] == 'B.Tech') ? 'selected' : ''; ?>>B.Tech</option>
            </select>
        </div>
    </header>

    <!-- Sidebar Section -->
    <aside>
        <ul>
            <li><a href="?page=dashboard&program=<?php echo isset($_GET['program']) ? $_GET['program'] : 'BCA'; ?>" 
                   class="<?php echo (!isset($_GET['page']) || $_GET['page'] === 'dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-dice-d6"></i> Dashboard</a></li>
            <li><a href="?page=add-courses&program=<?php echo isset($_GET['program']) ? $_GET['program'] : 'BCA'; ?>" 
                   class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'add-courses') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chalkboard"></i> Courses</a></li>
            <li><a href="?page=add-outcomes&program=<?php echo isset($_GET['program']) ? $_GET['program'] : 'BCA'; ?>" 
                   class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'add-outcomes') ? 'active' : ''; ?>">
                <i class="fa-solid fa-trophy"></i> Outcomes</a></li>
            <li><a href="?page=add-questions&program=<?php echo isset($_GET['program']) ? $_GET['program'] : 'BCA'; ?>" 
                   class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'add-questions') ? 'active' : ''; ?>">
                <i class="fa-regular fa-circle-question"></i> Questions</a></li>
        </ul>
    </aside>



    <!-- Main Content Area -->
    <main class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        $allowed_pages = ['dashboard', 'add-courses', 'add-outcomes', 'add-questions'];
        if (in_array($page, $allowed_pages)) {
            include("pages/$page.php");
        } else {
            echo "<h2>Page not found</h2>";
        }
        ?>
    </main>

    <script>
        // Function to redirect to a page with selected program
        function redirectToProgramPage(selectedProgram) {
            // If no program is selected, just return (do nothing)
            if (!selectedProgram){
                selectedProgram = 'BCA'
            }
            
            // Get the current URL query parameters, including the 'page' and 'program'
            const urlParams = new URLSearchParams(window.location.search);
            
            // Check if 'page' is set in the query string and if it's in the allowed pages list
            const currentPage = urlParams.get('page') || 'dashboard';
            const allowedPages = ['dashboard', 'add-courses', 'add-outcomes', 'add-questions'];

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
