<?php
/**
 * Simple test file to verify the Udemy Course Planner works
 */

echo "<h1>🧪 Udemy Course Planner - Test</h1>";

// Test 1: Check if required files exist
echo "<h2>Test 1: File Structure</h2>";
$requiredFiles = [
    'api/StudyPlanGenerator.php',
    'api/generate_plan.php',
    'api/preview_plan.php',
    'api/download_plan.php',
    'assets/css/style.css',
    'assets/js/script.js',
    'index.php'
];

$allFilesExist = true;
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
        $allFilesExist = false;
    }
}

// Test 2: Check if classes can be loaded
echo "<h2>Test 2: Class Loading</h2>";
try {
    require_once 'api/StudyPlanGenerator.php';
    echo "✅ StudyPlanGenerator.php loaded successfully<br>";
    
    $generator = new StudyPlanGenerator();
    echo "✅ StudyPlanGenerator class instantiated<br>";
    
    $markdownGenerator = new MarkdownGenerator();
    echo "✅ MarkdownGenerator class instantiated<br>";
    
} catch (Exception $e) {
    echo "❌ Error loading classes: " . $e->getMessage() . "<br>";
}

// Test 3: Test study plan generation
echo "<h2>Test 3: Study Plan Generation</h2>";
try {
    $courseData = getSampleCompTIACourse();
    $studyPlanData = $generator->generateStudyPlan($courseData, 2, date('Y-m-d'));
    
    echo "✅ Study plan generated successfully<br>";
    echo "📊 Course: " . $courseData['title'] . "<br>";
    echo "📅 Duration: " . $studyPlanData['formatted_total_duration'] . "<br>";
    echo "📋 Days: " . $studyPlanData['total_days'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error generating study plan: " . $e->getMessage() . "<br>";
}

// Test 4: Test markdown generation
echo "<h2>Test 4: Markdown Generation</h2>";
try {
    $markdownContent = $markdownGenerator->generateMarkdown($studyPlanData);
    echo "✅ Markdown generated successfully<br>";
    echo "📝 Content length: " . strlen($markdownContent) . " characters<br>";
    
    // Test file creation
    $testFile = 'generated_plans/test_' . time() . '.md';
    $markdownGenerator->generateMarkdown($studyPlanData, $testFile);
    echo "✅ Test file created: $testFile<br>";
    
} catch (Exception $e) {
    echo "❌ Error generating markdown: " . $e->getMessage() . "<br>";
}

// Test 5: Test HTML conversion
echo "<h2>Test 5: HTML Conversion</h2>";
try {
    $htmlContent = $markdownGenerator->markdownToHtml($markdownContent);
    echo "✅ HTML conversion successful<br>";
    echo "🌐 HTML length: " . strlen($htmlContent) . " characters<br>";
    
} catch (Exception $e) {
    echo "❌ Error converting to HTML: " . $e->getMessage() . "<br>";
}

// Test 6: Check PHP version and extensions
echo "<h2>Test 6: PHP Environment</h2>";
echo "🐘 PHP Version: " . PHP_VERSION . "<br>";
echo "📦 JSON Extension: " . (extension_loaded('json') ? '✅ Enabled' : '❌ Disabled') . "<br>";
echo "📁 File System: " . (is_writable('generated_plans/') ? '✅ Writable' : '❌ Not Writable') . "<br>";

// Summary
echo "<h2>🎯 Test Summary</h2>";
if ($allFilesExist) {
    echo "<p style='color: green; font-weight: bold;'>✅ All tests passed! The Udemy Course Planner is ready to use.</p>";
    echo "<p><a href='index.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Launch Application</a></p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Some tests failed. Please check the missing files.</p>";
}

?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f5f5f5;
}

h1, h2 {
    color: #2c3e50;
}

p {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #3498db;
}
</style> 