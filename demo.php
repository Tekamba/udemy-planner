<?php
/**
 * Demo file to test the Udemy Course Planner functionality
 * This file demonstrates how to use the StudyPlanGenerator class
 */

// Include the study plan generator
require_once 'api/StudyPlanGenerator.php';

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>📚 Udemy Course Planner - Demo</h1>";
echo "<p>This demo shows the study plan generation functionality.</p>";

try {
    // Get sample course data
    $courseData = getSampleCompTIACourse();
    
    // Create study plan generator
    $planGenerator = new StudyPlanGenerator();
    
    // Generate study plan for 2 weeks
    $studyPlanData = $planGenerator->generateStudyPlan($courseData, 2, date('Y-m-d'));
    
    // Display course information
    echo "<h2>📋 Course Information</h2>";
    echo "<ul>";
    echo "<li><strong>Course:</strong> " . $courseData['title'] . "</li>";
    echo "<li><strong>Instructor:</strong> " . $courseData['instructor'] . "</li>";
    echo "<li><strong>Total Duration:</strong> " . $studyPlanData['formatted_total_duration'] . "</li>";
    echo "<li><strong>Study Period:</strong> " . $studyPlanData['weeks'] . " weeks</li>";
    echo "<li><strong>Target Daily Time:</strong> " . $studyPlanData['formatted_target_daily_average'] . "</li>";
    echo "<li><strong>Actual Daily Average:</strong> " . $studyPlanData['formatted_actual_daily_average'] . "</li>";
    echo "<li><strong>Total Days:</strong> " . $studyPlanData['total_days'] . "</li>";
    echo "</ul>";
    
    // Display study plan summary
    echo "<h2>📊 Study Plan Summary</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Day</th><th>Date</th><th>Sections</th><th>Duration</th></tr>";
    
    foreach ($studyPlanData['study_plan'] as $day) {
        $sectionsCount = count($day['sections']);
        echo "<tr>";
        echo "<td>Day " . $day['day'] . "</td>";
        echo "<td>" . $day['date'] . "</td>";
        echo "<td>" . $sectionsCount . " sections</td>";
        echo "<td>" . $day['formatted_duration'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Display detailed daily plans
    echo "<h2>📅 Daily Study Plans</h2>";
    
    foreach ($studyPlanData['study_plan'] as $day) {
        echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;'>";
        echo "<h3>Day " . $day['day'] . " - " . $day['date'] . "</h3>";
        echo "<p><strong>Total Time:</strong> " . $day['formatted_duration'] . "</p>";
        
        // Group lectures by section
        $sections = [];
        foreach ($day['lectures'] as $lecture) {
            $sectionTitle = $lecture['section'];
            if (!isset($sections[$sectionTitle])) {
                $sections[$sectionTitle] = [];
            }
            $sections[$sectionTitle][] = $lecture;
        }
        
        foreach ($sections as $sectionTitle => $lectures) {
            echo "<h4>📖 " . $sectionTitle . "</h4>";
            echo "<ul>";
            foreach ($lectures as $lecture) {
                echo "<li><strong>" . $lecture['lecture'] . "</strong> (" . $lecture['duration'] . ")</li>";
            }
            echo "</ul>";
        }
        echo "</div>";
    }
    
    // Test markdown generation
    echo "<h2>📝 Markdown Generation Test</h2>";
    
    $markdownGenerator = new MarkdownGenerator();
    $markdownContent = $markdownGenerator->generateMarkdown($studyPlanData);
    
    // Create a test file
    $testFilename = 'generated_plans/demo_test.md';
    $markdownGenerator->generateMarkdown($studyPlanData, $testFilename);
    
    echo "<p><strong>Markdown file generated:</strong> " . $testFilename . "</p>";
    echo "<p><strong>File size:</strong> " . filesize($testFilename) . " bytes</p>";
    
    // Show a preview of the markdown
    echo "<h3>Markdown Preview (first 500 characters):</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto;'>";
    echo htmlspecialchars(substr($markdownContent, 0, 500)) . "...";
    echo "</pre>";
    
    // Test HTML conversion
    echo "<h2>🌐 HTML Conversion Test</h2>";
    $htmlContent = $markdownGenerator->markdownToHtml($markdownContent);
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto;'>";
    echo substr($htmlContent, 0, 1000) . "...";
    echo "</div>";
    
    echo "<h2>✅ Demo Completed Successfully!</h2>";
    echo "<p>The study plan generator is working correctly. You can now use the web interface at <a href='index.php'>index.php</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f5f5f5;
}

h1, h2, h3, h4 {
    color: #2c3e50;
}

table {
    background: white;
    border-radius: 5px;
}

th {
    background-color: #3498db;
    color: white;
    padding: 10px;
}

td {
    padding: 8px;
}

ul {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #3498db;
}

pre {
    font-family: 'Courier New', monospace;
    font-size: 12px;
}
</style> 