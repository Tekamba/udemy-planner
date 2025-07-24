<?php
/**
 * API endpoint for previewing generated study plans
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Include the study plan generator
require_once 'StudyPlanGenerator.php';

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
    
    // Read the markdown content
    $markdownContent = file_get_contents($filename);
    
    if ($markdownContent === false) {
        throw new Exception('Unable to read file');
    }
    
    // Convert markdown to HTML
    $markdownGenerator = new MarkdownGenerator();
    $htmlContent = $markdownGenerator->markdownToHtml($markdownContent);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'html_content' => $htmlContent,
        'file_id' => $fileId
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error loading plan preview: ' . $e->getMessage()
    ]);
}
?> 