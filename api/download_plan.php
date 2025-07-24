<?php
/**
 * API endpoint for downloading generated study plans
 */

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo 'Method not allowed';
    exit();
}

try {
    // Get file ID from query parameters
    $fileId = $_GET['file_id'] ?? '';
    
    if (empty($fileId)) {
        throw new Exception('File ID is required');
    }
    
    // Validate file ID format
    if (!preg_match('/^[a-f0-9]{8}$/', $fileId)) {
        throw new Exception('Invalid file ID format');
    }
    
    // Find the file
    $filename = '../generated_plans/course_plan_' . $fileId . '.md';
    
    if (!file_exists($filename)) {
        throw new Exception('File not found');
    }
    
    // Get file info
    $fileInfo = pathinfo($filename);
    $fileSize = filesize($filename);
    
    // Set headers for file download
    header('Content-Type: text/markdown');
    header('Content-Disposition: attachment; filename="course_plan.md"');
    header('Content-Length: ' . $fileSize);
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    // Output the file content
    readfile($filename);
    
} catch (Exception $e) {
    http_response_code(404);
    echo 'Error: ' . $e->getMessage();
}
?> 