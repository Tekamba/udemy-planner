<?php
/**
 * Study Plan Generator for Udemy Courses
 * PHP version of the Python Udemy Course Planner
 */

class StudyPlanGenerator {
    private $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    /**
     * Parse duration string to minutes
     * 
     * @param string $durationStr Duration string (e.g., "2h 30m", "45m")
     * @return int Duration in minutes
     */
    public function parseDuration($durationStr) {
        $totalMinutes = 0;
        
        // Extract hours
        if (preg_match('/(\d+)h/', $durationStr, $matches)) {
            $totalMinutes += intval($matches[1]) * 60;
        }
        
        // Extract minutes
        if (preg_match('/(\d+)m/', $durationStr, $matches)) {
            $totalMinutes += intval($matches[1]);
        }
        
        return $totalMinutes;
    }
    
    /**
     * Format minutes back to duration string
     * 
     * @param int $minutes Duration in minutes
     * @return string Formatted duration string
     */
    public function formatDuration($minutes) {
        $hours = intval($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        } else {
            return "{$mins}m";
        }
    }
    
    /**
     * Generate a study plan based on course curriculum and desired timeframe
     * 
     * @param array $courseData Course information and curriculum
     * @param int $weeks Number of weeks to complete the course
     * @param string $startDate Start date in YYYY-MM-DD format (optional)
     * @return array Study plan with daily breakdowns
     */
    public function generateStudyPlan($courseData, $weeks, $startDate = null) {
        $curriculum = $courseData['curriculum'];
        $totalDays = $weeks * 7;
        
        // Calculate total course duration
        $totalMinutes = 0;
        $allLectures = [];
        
        foreach ($curriculum as $section) {
            $sectionMinutes = $this->parseDuration($section['duration']);
            $totalMinutes += $sectionMinutes;
            
            foreach ($section['lectures'] as $lecture) {
                $lectureMinutes = $this->parseDuration($lecture['duration']);
                $totalMinutes += $lectureMinutes;
                $allLectures[] = [
                    'section' => $section['title'],
                    'lecture' => $lecture['title'],
                    'duration' => $lecture['duration'],
                    'minutes' => $lectureMinutes
                ];
            }
        }
        
        // Calculate target daily study time
        $targetDailyMinutes = intval($totalMinutes / $totalDays);
        $remainingMinutes = $totalMinutes % $totalDays;
        
        // Distribute lectures more evenly across days
        $studyPlan = [];
        $currentDay = 1;
        $currentDayMinutes = 0;
        $currentDayLectures = [];
        $currentDaySections = [];
        
        foreach ($allLectures as $lectureData) {
            // Check if adding this lecture would exceed target daily time
            if (($currentDayMinutes + $lectureData['minutes'] > $targetDailyMinutes * 1.2) && 
                !empty($currentDayLectures) && $currentDay < $totalDays) {
                // Complete current day
                $studyPlan[] = [
                    'day' => $currentDay,
                    'date' => $this->getDateForDay($currentDay, $startDate),
                    'lectures' => $currentDayLectures,
                    'sections' => array_unique($currentDaySections),
                    'total_minutes' => $currentDayMinutes,
                    'formatted_duration' => $this->formatDuration($currentDayMinutes)
                ];
                
                // Start new day
                $currentDay++;
                $currentDayMinutes = 0;
                $currentDayLectures = [];
                $currentDaySections = [];
            }
            
            // Add lecture to current day
            $currentDayLectures[] = $lectureData;
            $currentDaySections[] = $lectureData['section'];
            $currentDayMinutes += $lectureData['minutes'];
        }
        
        // Add the last day
        if (!empty($currentDayLectures)) {
            $studyPlan[] = [
                'day' => $currentDay,
                'date' => $this->getDateForDay($currentDay, $startDate),
                'lectures' => $currentDayLectures,
                'sections' => array_unique($currentDaySections),
                'total_minutes' => $currentDayMinutes,
                'formatted_duration' => $this->formatDuration($currentDayMinutes)
            ];
        }
        
        // Calculate actual daily average
        $actualDailyAverage = 0;
        if (!empty($studyPlan)) {
            $totalPlanMinutes = array_sum(array_column($studyPlan, 'total_minutes'));
            $actualDailyAverage = intval($totalPlanMinutes / count($studyPlan));
        }
        
        return [
            'course_info' => $courseData,
            'study_plan' => $studyPlan,
            'total_duration' => $totalMinutes,
            'formatted_total_duration' => $this->formatDuration($totalMinutes),
            'target_daily_average' => $targetDailyMinutes,
            'formatted_target_daily_average' => $this->formatDuration($targetDailyMinutes),
            'actual_daily_average' => $actualDailyAverage,
            'formatted_actual_daily_average' => $this->formatDuration($actualDailyAverage),
            'weeks' => $weeks,
            'total_days' => count($studyPlan),
            'start_date' => $startDate
        ];
    }
    
    /**
     * Get the date for a given day number starting from the specified date or today
     * 
     * @param int $dayNumber Day number
     * @param string $startDate Start date in YYYY-MM-DD format (optional)
     * @return string Formatted date string
     */
    private function getDateForDay($dayNumber, $startDate = null) {
        if ($startDate) {
            try {
                $startDateObj = new DateTime($startDate);
            } catch (Exception $e) {
                $startDateObj = new DateTime();
            }
        } else {
            $startDateObj = new DateTime();
        }
        
        $targetDate = clone $startDateObj;
        $targetDate->add(new DateInterval('P' . ($dayNumber - 1) . 'D'));
        
        return $targetDate->format('l, F j, Y');
    }
}

/**
 * Markdown Generator for study plans
 */
class MarkdownGenerator {
    /**
     * Generate markdown file with study plan and progress tracking
     * 
     * @param array $studyPlanData Study plan data
     * @param string $outputFile Output filename
     * @return string Generated markdown content
     */
    public function generateMarkdown($studyPlanData, $outputFile = null) {
        $courseInfo = $studyPlanData['course_info'];
        $studyPlan = $studyPlanData['study_plan'];
        
        $markdownContent = [];
        
        // Header
        $markdownContent[] = "# 📚 Udemy Course Study Plan";
        $markdownContent[] = "";
        
        // Course Information
        $markdownContent[] = "## 📋 Course Information";
        $markdownContent[] = "";
        $markdownContent[] = "**Course:** " . $courseInfo['title'];
        $markdownContent[] = "**Instructor:** " . $courseInfo['instructor'];
        $markdownContent[] = "**Total Duration:** " . $studyPlanData['formatted_total_duration'];
        $markdownContent[] = "**Study Period:** " . $studyPlanData['weeks'] . " weeks";
        $markdownContent[] = "**Target Daily Time:** " . $studyPlanData['formatted_target_daily_average'];
        $markdownContent[] = "**Actual Daily Average:** " . $studyPlanData['formatted_actual_daily_average'];
        if (isset($courseInfo['url'])) {
            $markdownContent[] = "**Course URL:** " . $courseInfo['url'];
        }
        $markdownContent[] = "";
        
        // Progress Summary
        $markdownContent[] = "## 📊 Progress Summary";
        $markdownContent[] = "";
        $markdownContent[] = "| Day | Date | Sections | Duration | Status |";
        $markdownContent[] = "|-----|------|----------|----------|--------|";
        
        foreach ($studyPlan as $day) {
            $sectionsCount = count($day['sections']);
            $markdownContent[] = "| Day " . $day['day'] . " | " . $day['date'] . " | " . $sectionsCount . " sections | " . $day['formatted_duration'] . " | ⬜ |";
        }
        $markdownContent[] = "";
        
        // Daily Study Plans
        $markdownContent[] = "## 📅 Daily Study Plans";
        $markdownContent[] = "";
        
        foreach ($studyPlan as $day) {
            $markdownContent[] = "### Day " . $day['day'] . " - " . $day['date'];
            $markdownContent[] = "**Total Time:** " . $day['formatted_duration'];
            $markdownContent[] = "";
            
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
                $markdownContent[] = "#### 📖 " . $sectionTitle;
                $markdownContent[] = "";
                
                foreach ($lectures as $lecture) {
                    $markdownContent[] = "- [ ] **" . $lecture['lecture'] . "** (" . $lecture['duration'] . ")";
                }
                $markdownContent[] = "";
            }
            
            $markdownContent[] = "---";
            $markdownContent[] = "";
        }
        
        // Study Tips
        $markdownContent[] = "## 💡 Study Tips";
        $markdownContent[] = "";
        $markdownContent[] = "- ✅ Check off each lecture as you complete it";
        $markdownContent[] = "- 📝 Take notes during lectures";
        $markdownContent[] = "- 🔄 Review previous sections regularly";
        $markdownContent[] = "- ⏰ Stick to the daily schedule for best results";
        $markdownContent[] = "- 🎯 Focus on understanding concepts, not just memorizing";
        $markdownContent[] = "";
        
        // Notes Section
        $markdownContent[] = "## 📝 Notes";
        $markdownContent[] = "";
        $markdownContent[] = "Add your personal notes here:";
        $markdownContent[] = "";
        
        foreach ($studyPlan as $day) {
            $markdownContent[] = "### Day " . $day['day'] . " Notes";
            $markdownContent[] = "";
        }
        
        $content = implode("\n", $markdownContent);
        
        // Write to file if specified
        if ($outputFile) {
            $this->ensureDirectoryExists(dirname($outputFile));
            file_put_contents($outputFile, $content);
        }
        
        return $content;
    }
    
    /**
     * Convert markdown to HTML for preview
     * 
     * @param string $markdown Markdown content
     * @return string HTML content
     */
    public function markdownToHtml($markdown) {
        // Simple markdown to HTML conversion
        $html = $markdown;
        
        // Headers
        $html = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $html);
        
        // Bold
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        
        // Lists
        $html = preg_replace('/^- \[ \] (.*$)/m', '<li class="checkbox">☐ $1</li>', $html);
        $html = preg_replace('/^- \[x\] (.*$)/m', '<li class="checkbox">☑ $1</li>', $html);
        $html = preg_replace('/^- (.*$)/m', '<li>$1</li>', $html);
        
        // Wrap lists in ul tags
        $html = preg_replace('/(<li.*<\/li>)/s', '<ul>$1</ul>', $html);
        
        // Tables
        $html = preg_replace('/\| (.*) \|/', '<tr><td>$1</td></tr>', $html);
        $html = preg_replace('/\|-----/', '<table>', $html);
        
        // Paragraphs
        $html = preg_replace('/^([^<].*)$/m', '<p>$1</p>', $html);
        
        // Clean up empty paragraphs
        $html = preg_replace('/<p><\/p>/', '', $html);
        
        return $html;
    }
    
    /**
     * Ensure directory exists
     * 
     * @param string $dir Directory path
     */
    private function ensureDirectoryExists($dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

/**
 * Sample course data for demonstration
 */
function getSampleCompTIACourse() {
    return [
        'title' => 'CompTIA A+ Core 1 (220-1101) Complete Course',
        'instructor' => 'Mike Meyers',
        'url' => 'https://www.udemy.com/course/comptia-a-core-1/',
        'curriculum' => [
            [
                'title' => 'Introduction to CompTIA A+',
                'duration' => '45m',
                'lectures' => [
                    ['title' => 'Welcome to the Course', 'duration' => '5m'],
                    ['title' => 'Course Overview and Objectives', 'duration' => '10m'],
                    ['title' => 'What You Will Learn', 'duration' => '15m'],
                    ['title' => 'Setting Up Your Study Environment', 'duration' => '15m']
                ]
            ],
            [
                'title' => 'Hardware Fundamentals',
                'duration' => '2h 15m',
                'lectures' => [
                    ['title' => 'Understanding Computer Components', 'duration' => '25m'],
                    ['title' => 'CPU and Motherboard Basics', 'duration' => '30m'],
                    ['title' => 'Memory and Storage Technologies', 'duration' => '35m'],
                    ['title' => 'Power Supplies and Cooling Systems', 'duration' => '25m'],
                    ['title' => 'Hardware Lab Exercise', 'duration' => '20m']
                ]
            ],
            [
                'title' => 'Networking Basics',
                'duration' => '1h 45m',
                'lectures' => [
                    ['title' => 'Network Fundamentals', 'duration' => '30m'],
                    ['title' => 'Network Protocols and Standards', 'duration' => '25m'],
                    ['title' => 'Wireless Networking Technologies', 'duration' => '20m'],
                    ['title' => 'Network Troubleshooting Basics', 'duration' => '30m']
                ]
            ],
            [
                'title' => 'Mobile Devices',
                'duration' => '55m',
                'lectures' => [
                    ['title' => 'Mobile Device Types and Features', 'duration' => '25m'],
                    ['title' => 'Mobile Device Hardware', 'duration' => '20m'],
                    ['title' => 'Mobile Device Connectivity', 'duration' => '20m'],
                    ['title' => 'Mobile Device Security', 'duration' => '15m']
                ]
            ],
            [
                'title' => 'Hardware and Network Troubleshooting',
                'duration' => '2h',
                'lectures' => [
                    ['title' => 'Troubleshooting Methodology', 'duration' => '30m'],
                    ['title' => 'Hardware Troubleshooting', 'duration' => '45m'],
                    ['title' => 'Network Troubleshooting', 'duration' => '45m'],
                    ['title' => 'Common Issues and Solutions', 'duration' => '30m']
                ]
            ],
            [
                'title' => 'Virtualization and Cloud Computing',
                'duration' => '1h 15m',
                'lectures' => [
                    ['title' => 'Virtualization Concepts', 'duration' => '25m'],
                    ['title' => 'Cloud Computing Basics', 'duration' => '25m'],
                    ['title' => 'Virtualization and Cloud Security', 'duration' => '25m']
                ]
            ],
            [
                'title' => 'Operating Systems',
                'duration' => '2h 15m',
                'lectures' => [
                    ['title' => 'Windows Operating System', 'duration' => '45m'],
                    ['title' => 'macOS and Linux Basics', 'duration' => '30m'],
                    ['title' => 'Operating System Installation', 'duration' => '30m'],
                    ['title' => 'Operating System Configuration', 'duration' => '30m'],
                    ['title' => 'Operating System Maintenance', 'duration' => '30m']
                ]
            ],
            [
                'title' => 'Software Troubleshooting',
                'duration' => '1h 15m',
                'lectures' => [
                    ['title' => 'Software Installation Issues', 'duration' => '30m'],
                    ['title' => 'Operating System Problems', 'duration' => '30m'],
                    ['title' => 'Security Software Issues', 'duration' => '25m'],
                    ['title' => 'Software Troubleshooting Tools', 'duration' => '20m']
                ]
            ],
            [
                'title' => 'Security Fundamentals',
                'duration' => '1h 30m',
                'lectures' => [
                    ['title' => 'Security Threats and Vulnerabilities', 'duration' => '30m'],
                    ['title' => 'Security Best Practices', 'duration' => '30m'],
                    ['title' => 'Data Protection and Privacy', 'duration' => '30m']
                ]
            ],
            [
                'title' => 'Course Review and Exam Preparation',
                'duration' => '1h 15m',
                'lectures' => [
                    ['title' => 'Course Review and Key Concepts', 'duration' => '30m'],
                    ['title' => 'Practice Exam Questions', 'duration' => '30m'],
                    ['title' => 'Exam Day Tips and Strategies', 'duration' => '15m']
                ]
            ]
        ]
    ];
}
?> 