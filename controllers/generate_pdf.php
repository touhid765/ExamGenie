<?php
// Include FPDF library
include '../assets/lib/fpdf.php';

// Handle incoming JSON data
$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);

if (!isset($data['report'])) {
    http_response_code(400);
    echo "No report data received";
    exit;
}

$report = $data['report'];
$courseCode = $report['course_code'];
$year = $report['year'];

// Sanitize the filename
$filename = preg_replace('/[^A-Za-z0-9_\-]/', '', $courseCode . "_" . $year) . ".pdf";

// Initialize FPDF object
$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 20); // Automatically break pages, 20mm bottom margin
$pdf->AddPage();

// Set title
$pdf->SetFont('Arial', 'B', 26);
$pdf->Cell(0, 10, "Aliah University", 0, 1, 'C');

// Set course details
$pdf->SetFont('Arial', 'B', 16);
$pdf->Ln(10); // Line break
$pdf->Cell(0, 10, "Course Code: {$courseCode}", 0, 1);
$pdf->Cell(0, 10, "Year: {$year}", 0, 1);

// Add a line break
$pdf->Ln(10);

// Add questions
$pdf->SetFont('Arial', '', 12);

$n = 1;
foreach ($report['questions'] as $question) {
    $questionText = "$n. " . $question['question_text'] . " [ Marks: {$question['marks']}, {$question['level']}, CO{$question['outcome_id']} ]";

    // Check if content will overflow and add a new page if necessary
    if ($pdf->GetY() + 10 > 280) { // If the Y position is near the bottom of the page
        $pdf->AddPage(); // Start a new page
    }

    // Print question text
    $pdf->MultiCell(0, 10, $questionText);

    $n++;
}

// Output the PDF as a download
$pdf->Output('D', $filename);
?>
