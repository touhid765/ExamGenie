// Helper function to get 'program' from URL parameters
function getProgramFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('program');
}  

// Populate Year Dropdown
function populateYearDropdown() {
    const yearDropdown = document.getElementById("year");
    const currentYear = new Date().getFullYear();
    const startYear = 2022;
    const endYear = currentYear + 1;

    for (let year = startYear; year <= endYear; year++) {
        const option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        yearDropdown.appendChild(option);
    }
}

// Fetch and Populate Courses
function populateCourseDropdown() {
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
            const courseDropdown = document.getElementById("course");
            courseDropdown.innerHTML = '<option value="">-- Select Course --</option>';

            data.data.courses.forEach(course => {
                const option = document.createElement("option");
                option.value = course.course_code;
                option.textContent = course.course_code;
                courseDropdown.appendChild(option);
            });

            courseDropdown.addEventListener("change", function () {
                const selectedCourse = this.value;
                if (selectedCourse) {
                    fetchQuestions(selectedCourse);
                    fetchOutcomes(selectedCourse);
                }
            });
        })
        .catch(error => alert('Error loading courses: ' + error.message));
}


let allQuestions = []; // Store all fetched questions for the selected course
// fetch questions
function fetchQuestions(courseCode) {
    fetch(`controllers/question.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            // Store fetched questions globally
            allQuestions = data.data.questions;

            // Render questions initially
            renderQuestions(allQuestions);

            // Populate Marks Dropdown
            const marksDropdown = document.getElementById("marks");
            const uniqueMarks = [...new Set(allQuestions.map(q => q.marks))];
            marksDropdown.innerHTML = '<option value="">-- Select Marks --</option>';
            uniqueMarks.sort((a, b) => a - b).forEach(mark => {
                const option = document.createElement("option");
                option.value = mark;
                option.textContent = mark;
                marksDropdown.appendChild(option);
            });
        })
        .catch(error => alert('Error loading questions: ' + error.message));
}


function renderQuestions(questions) {
    const filteredQuestionsEl = document.getElementById("filteredQuestions");
    filteredQuestionsEl.innerHTML = '';

    if (questions.length === 0) {
        filteredQuestionsEl.innerHTML = '<li>No questions available</li>';
    } else {
        questions.forEach(question => {
            const li = document.createElement("li");
            li.dataset.id = question.id;
            li.dataset.marks = question.marks;
            li.dataset.level = question.level;
            li.dataset.outcomeId = question.outcome_id;

            li.innerHTML = `
                ${question.question_text}
                <button onclick="handleQuestionAction(${question.id}, 'add')">Add</button>
            `;
            filteredQuestionsEl.appendChild(li);
        });
    }
}

document.getElementById("filterBtn").addEventListener("click", () => {
    const marks = document.getElementById("marks").value;
    const level = document.getElementById("level").value;
    const outcome = document.getElementById("outcome").value;

    // Filter from the global `allQuestions` array
    const filtered = allQuestions.filter(question => {
        return (
            (!marks || question.marks == marks) &&
            (!level || question.level === level) &&
            (!outcome || question.outcome_id == outcome)
        );
    });

    renderQuestions(filtered); // Update the UI with the filtered questions
});


// Fetch and Populate Outcomes
function fetchOutcomes(courseCode) {
    fetch(`controllers/outcome.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const outcomeDropdown = document.getElementById("outcome");
            outcomeDropdown.innerHTML = '<option value="">-- Select Outcome --</option>';

            data.data.outcomes.forEach(outcome => {
                const option = document.createElement("option");
                option.value = outcome.id;
                option.textContent = outcome.outcome_text;
                outcomeDropdown.appendChild(option);
            });
        })
        .catch(error => alert('Error loading outcomes: ' + error.message));
}

// Handle Add/Delete Question Actions
const selectedQuestionsEl = document.getElementById("selectedQuestions");

function handleQuestionAction(id, action) {
    const filteredQuestionsEl = document.getElementById("filteredQuestions");
    const questionEl = filteredQuestionsEl.querySelector(`[data-id='${id}']`) || 
                       selectedQuestionsEl.querySelector(`[data-id='${id}']`);
    if (!questionEl) return;

    if (action === "add") {
        questionEl.querySelector("button").innerText = "Delete";
        questionEl.querySelector("button").onclick = () => handleQuestionAction(id, 'delete');
        selectedQuestionsEl.appendChild(questionEl);
    } else if (action === "delete") {
        questionEl.querySelector("button").innerText = "Add";
        questionEl.querySelector("button").onclick = () => handleQuestionAction(id, 'add');
        filteredQuestionsEl.appendChild(questionEl);
    }
}

// // Filter Questions Based on Criteria
// document.getElementById("filterBtn").addEventListener("click", () => {

//     const marks = document.getElementById("marks").value;
//     const level = document.getElementById("level").value;
//     const outcome = document.getElementById("outcome").value;

//     const filtered = Array.from(document.querySelectorAll("#filteredQuestions li")).filter(li => {
//         const question = li.dataset;
//         return (
//             (!marks || question.marks == marks) &&
//             (!level || question.level === level) &&
//             (!outcome || question.outcomeId == outcome)
//         );
//     });

//     const filteredQuestionsEl = document.getElementById("filteredQuestions");
//     filteredQuestionsEl.innerHTML = '';
//     filtered.forEach(question => filteredQuestionsEl.appendChild(question));
// });

// Initialize Dashboard
populateYearDropdown();
populateCourseDropdown();
