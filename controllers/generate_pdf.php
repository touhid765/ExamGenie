<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);

    if (!isset($data['content']) || empty($data['content'])) {
        http_response_code(400);
        echo json_encode(["message" => "No content provided"]);
        exit;
    }

    // Capture the content
    $content = $data['content'];

    // Create the PDF using output buffering
    ob_start();
    echo '<html><body>';
    echo $content;
    echo '</body></html>';
    $htmlContent = ob_get_clean();

    // Convert HTML to PDF
    $pdfFilePath = "php://output"; // Output directly to browser
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=report.pdf");

    // Set up basic PDF headers
    $pageWidth = 210; // A4 width in mm
    $pageHeight = 297; // A4 height in mm
    $margin = 10;

    // Start PDF generation
    $pdf = fopen($pdfFilePath, "wb");
    fwrite($pdf, "%PDF-1.4\n");

    // Page content
    fwrite($pdf, "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n");
    fwrite($pdf, "2 0 obj\n<< /Type /Pages /Count 1 /Kids [3 0 R] >>\nendobj\n");
    fwrite($pdf, "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 $pageWidth $pageHeight]\n/Contents 4 0 R >>\nendobj\n");
    fwrite($pdf, "4 0 obj\n<< /Length " . strlen($htmlContent) . " >>\nstream\n$htmlContent\nendstream\nendobj\n");

    fwrite($pdf, "xref\n0 5\n0000000000 65535 f\n0000000010 00000 n\n0000000053 00000 n\n");
    fwrite($pdf, "trailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n9\n%%EOF");

    fclose($pdf);
    exit;
}
?>
