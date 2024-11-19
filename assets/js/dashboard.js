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
    fetch('controllers/course.php', { method: 'GET' })
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

// Fetch and Populate Questions
function fetchQuestions(courseCode) {
    fetch(`controllers/question.php?course_code=${courseCode}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const filteredQuestionsEl = document.getElementById("filteredQuestions");
            const marksDropdown = document.getElementById("marks");
            filteredQuestionsEl.innerHTML = '';
            marksDropdown.innerHTML = '<option value="">-- Select Marks --</option>';

            if (data.data.questions.length === 0) {
                filteredQuestionsEl.innerHTML = '<li>No questions available</li>';
            } else {
                const uniqueMarks = new Set();

                data.data.questions.forEach(question => {
                    uniqueMarks.add(question.marks);

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

                // Populate Marks Dropdown
                [...uniqueMarks].sort((a, b) => a - b).forEach(mark => {
                    const option = document.createElement("option");
                    option.value = mark;
                    option.textContent = mark;
                    marksDropdown.appendChild(option);
                });
            }
        })
        .catch(error => alert('Error loading questions: ' + error.message));
}

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

// Filter Questions Based on Criteria
document.getElementById("filterBtn").addEventListener("click", () => {
    const marks = document.getElementById("marks").value;
    const level = document.getElementById("level").value;
    const outcome = document.getElementById("outcome").value;

    const filtered = Array.from(document.querySelectorAll("#filteredQuestions li")).filter(li => {
        const question = li.dataset;
        return (
            (!marks || question.marks == marks) &&
            (!level || question.level === level) &&
            (!outcome || question.outcomeId == outcome)
        );
    });

    const filteredQuestionsEl = document.getElementById("filteredQuestions");
    filteredQuestionsEl.innerHTML = '';
    filtered.forEach(question => filteredQuestionsEl.appendChild(question));
});

// Initialize Dashboard
populateYearDropdown();
populateCourseDropdown();
