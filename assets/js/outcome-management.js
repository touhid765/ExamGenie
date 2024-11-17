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
            const courses = document.querySelector('#course');
            courses.innerHTML = '<option value="">Select Course</option>';

            if (data.data.courses.length === 0) {
                courses.innerHTML = '<option>No courses available</option>';
            } else {
                data.data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.course_code;  // Using course_code instead of id
                    option.innerHTML = course.course_code;
                    courses.appendChild(option);
                });

                // Add an event listener for when the course selection changes
                courses.addEventListener('change', function() {
                    const selectedCourseCode = courses.value;
                    if (selectedCourseCode) {
                        loadOutcomes(selectedCourseCode);  // Load outcomes when course changes
                    }
                });
            }
        })
        .catch(error => alert('Error loading courses: ' + error.message));
}

// Fetch outcomes for the selected course
function loadOutcomes(courseCode) {
    fetch(`controllers/outcome.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const outcomesList = document.querySelector('#outcomes-list');
            outcomesList.innerHTML = '';

            if (data.data.outcomes.length === 0) {
                outcomesList.innerHTML = '<li>No outcomes available</li>';
            } else {
                data.data.outcomes.forEach(outcome => {
                    const li = document.createElement('li');
                    li.innerHTML = `${outcome.outcome_text} 
                        <button class="edit" onclick="editOutcome(${outcome.id}, '${outcome.outcome_text.replace(/'/g, "\\'")}')">Edit</button>
                        <button class="delete" onclick="deleteOutcome(${outcome.id})">Delete</button>`;
                    outcomesList.appendChild(li);
                });
                
            }
        })
        .catch(error => alert('Error loading outcomes: ' + error.message));
}

// Add a new outcome
document.getElementById('outcomeForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const courseCode = document.getElementById('course').value;
    const outcomeText = document.getElementById('outcome').value.trim();

    if (outcomeText === '') {
        alert('Outcome text is required.');
        return;
    }

    fetch('controllers/outcome.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ course_code: courseCode, outcome_text: outcomeText })  // Use course_code
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        alert(data.message);
        loadOutcomes(courseCode);  // Reload outcomes for the selected course
        document.getElementById('outcome').value = '';  // Clear the form
    })
    .catch(error => alert('Error adding outcome: ' + error.message));
});

// Edit an outcome using a modal popup
function editOutcome(outcomeId, outcomeText) {
    console.log(outcomeId, outcomeText)
    // Populate the modal with the current outcome details
    document.getElementById('editOutcomeText').value = outcomeText;
    document.getElementById('editOutcomeId').value = outcomeId;

    // Show the modal
    document.getElementById('editOutcomeModal').style.display = "block";
}

// Close the modal
function closeModal() {
    document.getElementById('editOutcomeModal').style.display = "none";
}

// Submit the form in the modal to update the outcome
document.getElementById('editOutcomeForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const outcomeId = document.getElementById('editOutcomeId').value;
    const outcomeText = document.getElementById('editOutcomeText').value.trim();
    const courseCode = document.getElementById('course').value;

    if (outcomeText === '') {
        alert('Outcome text is required.');
        return;
    }

    // Send the updated outcome text to the server
    fetch('controllers/outcome.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: outcomeId, outcome_text: outcomeText, course_code: courseCode })
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        alert(data.message);
        loadOutcomes(courseCode);  // Reload outcomes for the selected course
        closeModal();  // Close the modal
    })
    .catch(error => alert('Error editing outcome: ' + error.message));
});

// Delete an outcome
function deleteOutcome(outcomeId) {
    if (confirm("Are you sure you want to delete this outcome?")) {
        const courseCode = document.getElementById('course').value;

        fetch('controllers/outcome.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: outcomeId })
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            alert(data.message);
            loadOutcomes(courseCode);  // Reload outcomes after deletion
        })
        .catch(error => alert('Error deleting outcome: ' + error.message));
    }
}

// Initial load of courses
loadCourses();
