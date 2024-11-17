<h2>Task 3: Question Management</h2>
<form method="POST" action="">
    <label for="subject">Select Subject:</label>
    <select id="subject" name="subject">
        <option value="subject1">Subject 1</option>
        <option value="subject2">Subject 2</option>
    </select>

    <label for="question_text">Question Text:</label>
    <textarea id="question_text" name="question_text" required></textarea>

    <label for="marks">Marks:</label>
    <input type="number" id="marks" name="marks" required>

    <label for="level">Level:</label>
    <select id="level" name="level">
        <option value="L1">L1</option>
        <option value="L2">L2</option>
        <option value="L3">L3</option>
    </select>

    <label for="outcome">Outcome:</label>
    <select id="outcome" name="outcome">
        <option value="outcome1">Outcome 1</option>
        <option value="outcome2">Outcome 2</option>
    </select>
    
    <button type="submit">Submit</button>
</form>