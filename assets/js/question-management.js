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
                    option.value = course.course_code; // Using course_code
                    option.innerHTML = course.course_code;
                    courses.appendChild(option);
                });

                // Add an event listener for when the course selection changes
                courses.addEventListener('change', function () {
                    const selectedCourseCode = courses.value;
                    if (selectedCourseCode) {
                        loadOutcomes(selectedCourseCode); // Load outcomes when course changes
                        loadQuestions(selectedCourseCode);
                    }
                });
            }
        })
        .catch(error => alert('Error loading courses: ' + error.message));
}

function loadOutcomes(courseCode, selectedOutcomeId = null) {
    const outcomes = document.querySelector('#outcome');
    const editOutcome = document.querySelector('#editOutcome');

    outcomes.innerHTML = '<option>Loading...</option>';
    editOutcome.innerHTML = '<option>Loading...</option>';

    fetch(`controllers/outcome.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            outcomes.innerHTML = '<option value="">Select Outcome</option>';
            editOutcome.innerHTML = '<option value="">Select Outcome</option>';

            if (data.data.outcomes.length === 0) {
                outcomes.innerHTML = '<option>No outcomes available</option>';
                editOutcome.innerHTML = '<option>No outcomes available</option>';
            } else {
                data.data.outcomes.forEach(outcome => {
                    const option = document.createElement('option');
                    option.value = outcome.id;
                    option.innerHTML = outcome.outcome_text;

                    outcomes.appendChild(option);

                    const option1 = document.createElement('option');
                    option1.value = outcome.id;
                    option1.innerHTML = outcome.outcome_text;

                    // Pre-select if this is the selected outcome for editing
                    if (selectedOutcomeId && outcome.id == selectedOutcomeId) {
                        option1.selected = true;
                    }

                    editOutcome.appendChild(option1);
                });
            }
        })
        .catch(error => {
            outcomes.innerHTML = '<option>Error loading outcomes</option>';
            editOutcome.innerHTML = '<option>Error loading outcomes</option>';
            alert('Error loading outcomes: ' + error.message);
        });
}



// Add a new question
document.getElementById('questionForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const courseCode = document.getElementById('course').value;
    const outcomeId = document.getElementById('outcome').value;
    const questionText = document.getElementById('questionText').value.trim();
    const marks = parseInt(document.getElementById('marks').value, 10);
    const level = document.getElementById('level').value;

    if (!questionText || isNaN(marks)) {
        alert('Question text and marks are required.');
        return;
    }

    fetch('controllers/question.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            course_code: courseCode,
            outcome_id: outcomeId,
            question_text: questionText,
            marks: marks,
            level: level,
        })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            alert(data.message);
            loadQuestions(courseCode); // Reload questions for the selected course
            document.getElementById('questionForm').reset(); // Clear the form
        })
        .catch(error => alert('Error adding question: ' + error.message));
});

// Fetch and display questions for the selected course
function loadQuestions(courseCode) {
    fetch(`controllers/question.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const questionsList = document.querySelector('#questions-list');
            questionsList.innerHTML = '';

            if (data.data.questions.length === 0) {
                questionsList.innerHTML = '<li>No questions available</li>';
            } else {
                data.data.questions.forEach(question => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <strong>${question.question_text}</strong> 
                        (Marks: ${question.marks}, Level: ${question.level})
                        <br>Outcome: ${question.outcome_text}
                        <button class="edit" 
                            onclick="editQuestion(${question.id}, '${question.question_text.replace(/'/g, "\\'")}', ${question.marks}, '${question.level}', '${question.outcome_id}')">
                            Edit
                        </button>
                        <button class="delete" onclick="deleteQuestion(${question.id})">Delete</button>`;
                    questionsList.appendChild(li);
                });
            }
        })
        .catch(error => alert('Error loading questions: ' + error.message));
}

// Edit a question using a modal popup
function editQuestion(id, questionText, marks, level, outcomeId) {
    document.getElementById('editQuestionText').value = questionText;
    document.getElementById('editMarks').value = marks;
    document.getElementById('editLevel').value = level;
    document.getElementById('editQuestionId').value = id;

    // Load outcomes with pre-selection for editing
    const courseCode = document.getElementById('course').value;
    loadOutcomes(courseCode, outcomeId);

    document.getElementById('editQuestionModal').style.display = "block";
}


// Close the modal
function closeModal() {
    document.getElementById('editQuestionModal').style.display = "none";
}

// Submit the form in the modal to update the question
document.getElementById('editQuestionForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const id = parseInt(document.getElementById('editQuestionId').value, 10);
    const questionText = document.getElementById('editQuestionText').value.trim();
    const marks = parseInt(document.getElementById('editMarks').value, 10);
    const level = document.getElementById('editLevel').value;
    const outcomeId = parseInt(document.getElementById('editOutcome').value, 10);
    const courseCode = document.getElementById('course').value;

    if (!questionText || isNaN(marks)) {
        alert('Question text and marks are required.');
        return;
    }
    fetch('controllers/question.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: id,
            question_text: questionText,
            marks: marks,
            level: level,
            outcome_id: outcomeId,
        })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            alert(data.message);
            loadQuestions(courseCode); // Reload questions
            closeModal();
        })
        .catch(error => alert('Error editing question: ' + error.message));
});

// Delete a question
function deleteQuestion(id) {
    if (confirm("Are you sure you want to delete this question?")) {
        const courseCode = document.getElementById('course').value;

        fetch('controllers/question.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                alert(data.message);
                loadQuestions(courseCode); // Reload questions
            })
            .catch(error => alert('Error deleting question: ' + error.message));
    }
}

// Initial load of courses
loadCourses();
