// Helper function to get 'program' from URL parameters
function getProgramFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('program');
}

// Fetch and render courses from the server
function loadCourses() {
    const program = getProgramFromURL();

    if (!program) {
        alert('Please select a program.');
        return;
    }

    fetch(`controllers/course.php?program=${program}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const coursesTable = document.querySelector('#courses-table tbody');
            coursesTable.innerHTML = '';

            if (data.data.courses.length === 0) {
                coursesTable.innerHTML = '<tr><td colspan="4">No courses available</td></tr>';
            } else {
                data.data.courses.forEach(course => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${course.course_name}</td>
                        <td>${course.course_code}</td>
                        <td>${course.credits}</td>
                        <td>
                            <button type="button" aria-label="Edit Course"
                                onclick="openEditModal('${course.id}', '${course.course_name}', '${course.course_code}', '${course.credits}')">Edit</button>
                            <button type="button" aria-label="Delete Course"
                                onclick="deleteCourse('${course.id}')">Delete</button>
                        </td>
                    `;
                    coursesTable.appendChild(row);
                });
            }
        })
        .catch(error => alert('Error loading courses: ' + error.message));
}

// Add a new course
document.getElementById('add-course-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const program = getProgramFromURL();
    const courseName = document.getElementById('course_name').value.trim();
    const courseCode = document.getElementById('course_code').value.trim();
    const credits = document.getElementById('credits').value.trim();

    // Validation checks
    if (!program) {
        alert('Please select a program.');
        return;
    }

    if (courseName === '') {
        alert('Course name is required.');
        return;
    }

    const courseCodePattern = /^[A-Za-z0-9\-]+$/;
    if (!courseCode.match(courseCodePattern)) {
        alert('Please enter a valid course code (alphanumeric with optional hyphens).');
        return;
    }

    if (isNaN(credits) || credits <= 0) {
        alert('Credits must be a positive number.');
        return;
    }

    fetch('controllers/course.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ course_name: courseName, course_code: courseCode, credits: credits, program })
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        alert(data.message);
        loadCourses();  // Reload courses
        document.getElementById('add-course-form').reset();  // Reset the form
    })
    .catch(error => alert('Error adding course: ' + error.message));
});

// Open the edit modal with course data
function openEditModal(courseId, courseName, courseCode, credits) {
    const modal = document.getElementById('edit-modal');
    modal.style.display = 'block';
    document.getElementById('edit_course_id').value = courseId;
    document.getElementById('edit_course_name').value = courseName;
    document.getElementById('edit_course_code').value = courseCode;
    document.getElementById('edit_credits').value = credits;
}

// Close the edit modal
function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}

// Update the course
document.getElementById('edit-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const program = getProgramFromURL();
    const courseId = document.getElementById('edit_course_id').value;
    const courseName = document.getElementById('edit_course_name').value.trim();
    const courseCode = document.getElementById('edit_course_code').value.trim();
    const credits = document.getElementById('edit_credits').value.trim();

    // Validation checks
    if (!program) {
        alert('Please select a program.');
        return;
    }

    if (courseName === '') {
        alert('Course name is required.');
        return;
    }

    const courseCodePattern = /^[A-Za-z0-9\-]+$/;
    if (!courseCode.match(courseCodePattern)) {
        alert('Please enter a valid course code (alphanumeric with optional hyphens).');
        return;
    }

    if (isNaN(credits) || credits <= 0) {
        alert('Credits must be a positive number.');
        return;
    }

    fetch('controllers/course.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: courseId, course_name: courseName, course_code: courseCode, credits: credits, program })
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        alert(data.message);
        loadCourses();  // Reload courses after edit
        closeEditModal();  // Close the modal
    })
    .catch(error => alert('Error updating course: ' + error.message));
});

// Delete the course
function deleteCourse(courseId) {
    if (confirm("This course will be deleted?")) {
        fetch('controllers/course.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: courseId })
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            alert(data.message);
            loadCourses();  // Reload courses after deletion
        })
        .catch(error => alert('Error deleting course: ' + error.message));
    }
}

// Initial load of courses
loadCourses();
