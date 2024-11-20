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
    yearDropdown.addEventListener("change", function () {
        const selectedYear = this.value;
        if (selectedYear) {
            fetchQuestionReport();
        }
    });
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
            courseDropdown.innerHTML = '<option value="">Select Course</option>';

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
                    fetchQuestionReport();    
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
            marksDropdown.innerHTML = '<option value="">Select Marks</option>';
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
                <button onclick="handleAddQuestionInReport(${question.id})">Add</button>
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
            outcomeDropdown.innerHTML = '<option value="">Select Outcome</option>';

            data.data.outcomes.forEach(outcome => {
                const option = document.createElement("option");
                option.value = outcome.id;
                option.textContent = outcome.outcome_text;
                outcomeDropdown.appendChild(option);
            });
        })
        .catch(error => alert('Error loading outcomes: ' + error.message));
}


function handleAddQuestionInReport(id) {
    const courseCode = document.getElementById('course').value;
    const year = document.getElementById('year').value;

    if (!courseCode || !year) {
        alert("Please select a course and year first.");
        return;
    }

    fetch('controllers/report.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            course_code: courseCode,
            year: year,
            question_id: id
        })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            alert("Question added to report successfully!");
            fetchQuestionReport(); // Refresh the report
        })
        .catch(error => alert("Error adding question to report: " + error.message));
}

function handleRemoveQuestionFromReport(id) {
    console.log(id);
    const courseCode = document.getElementById('course').value;
    const year = document.getElementById('year').value;

    if (!courseCode || !year) {
        alert("Please select a course and year first.");
        return;
    }

    fetch(`controllers/report.php?course_code=${courseCode}&year=${year}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const report = data.data.reports[0]; // Assuming only one report per course_code/year
            if (!report) {
                alert("Report not found.");
                return;
            }

            const updatedQuestionIds = report.question_id
                .split(',')
                .filter(qid => qid != id)
                .join(',');

            
            fetch('controllers/report.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: report.id,
                    question_id: updatedQuestionIds
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    alert("Question removed from report successfully!");
                    fetchQuestionReport(); // Refresh the report
                })
                .catch(error => alert("Error removing question from report: " + error.message));
        })
        .catch(error => alert("Error fetching report: " + error.message));
}


function fetchQuestionReport() {
    const courseCode = document.getElementById('course').value;
    const year = document.getElementById('year').value;

    if (!courseCode || !year) {
        alert("Please select a course and year first.");
        return;
    }

    fetch(`controllers/report.php?course_code=${courseCode}&year=${year}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const report = document.getElementById("report");
            report.innerHTML = '';

            if (!data.data.reports || data.data.reports.length === 0) {
                report.innerHTML = `<h2>No report found for ${courseCode}, Year: ${year}</h2>`;
                return;
            }

            const reportHeader = document.createElement("h2");
            reportHeader.textContent = `Report for Course Code: ${courseCode}, Year: ${year}`;
            report.appendChild(reportHeader);

            const questionsList = document.createElement("ul");
            data.data.reports[0].questions.forEach(question => {
                const li = document.createElement("li");
                li.innerHTML = `
                    Marks : ${question.marks} Level : ${question.level} </br>
                    Outcome : ${question.outcome_text} </br>
                    Question : ${question.question_text} 
                    <button onclick="handleRemoveQuestionFromReport(${question.id})">Remove</button>
                `;
                questionsList.appendChild(li);
            });

            report.appendChild(questionsList);
            
            const button = `<button id="downloadPdf" onClick="downloadAsPdf()">Download as PDF</button>`;
            report.innerHTML += button;
        })
        .catch(error => alert("Error fetching report: " + error.message));
}


function downloadAsPdf() {
    const courseCode = document.getElementById("course").value;
    const year = document.getElementById("year").value;

    if (!courseCode || !year) {
        alert("Please select a course and year first.");
        return;
    }

    fetch(`controllers/report.php?course_code=${courseCode}&year=${year}`, { method: "GET" })
        .then((response) => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then((data) => {
            if (!data.data.reports || data.data.reports.length === 0) {
                alert(`No report found for ${courseCode}, Year: ${year}`);
                return;
            }

            const report = data.data.reports[0]; // Use the first report (or modify as needed)
            
            // Send the raw report object to the server for PDF generation
            fetch("controllers/generate_pdf.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ report }),
            })
                .then((response) => response.blob())
                .then((blob) => {
                    // Create a link to download the PDF
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = `${courseCode}_${year}.pdf`;
                    link.click();
                })
                .catch((error) => {
                    console.error("Error generating PDF:", error);
                });
        })
        .catch((error) => {
            alert("Error fetching report: " + error.message);
        });
}



// Initialize Dashboard
populateYearDropdown();
populateCourseDropdown();
