<?php
/**
 * API endpoint for generating Udemy course study plans
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Include the study plan generator
require_once 'StudyPlanGenerator.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    // Extract parameters
    $courseUrl = $input['course_url'] ?? '';
    $weeks = intval($input['weeks'] ?? 2);
    $startDate = $input['start_date'] ?? null;
    
    // Validate input
    if (empty($courseUrl)) {
        throw new Exception('Course URL is required');
    }
    
    if ($weeks < 1 || $weeks > 52) {
        throw new Exception('Study period must be between 1 and 52 weeks');
    }
    
    // For demo purposes, we'll use the sample course
    // In a real implementation, this would extract course data from the URL
    $courseData = getSampleCompTIACourse();
    $courseData['url'] = $courseUrl;
    
    // Generate study plan
    $planGenerator = new StudyPlanGenerator();
    $studyPlanData = $planGenerator->generateStudyPlan($courseData, $weeks, $startDate);
    
    // Generate markdown
    $markdownGenerator = new MarkdownGenerator();
    
    // Create output directory if it doesn't exist
    $outputDir = '../generated_plans';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    
    // Create a unique filename
    $uniqueId = substr(md5(uniqid()), 0, 8);
    $filename = $outputDir . '/course_plan_' . $uniqueId . '.md';
    
    // Generate the markdown file
    $markdownContent = $markdownGenerator->generateMarkdown($studyPlanData, $filename);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Study plan generated successfully!',
        'file_id' => $uniqueId,
        'filename' => $filename,
        'course_info' => [
            'title' => $courseData['title'],
            'instructor' => $courseData['instructor'],
            'total_duration' => $studyPlanData['formatted_total_duration'],
            'target_daily_average' => $studyPlanData['formatted_target_daily_average'],
            'actual_daily_average' => $studyPlanData['formatted_actual_daily_average']
        ],
        'study_plan_summary' => [
            'total_days' => $studyPlanData['total_days'],
            'weeks' => $studyPlanData['weeks'],
            'total_lectures' => count(array_merge(...array_column($courseData['curriculum'], 'lectures')))
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error generating study plan: ' . $e->getMessage()
    ]);
}
?> 