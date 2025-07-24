// JavaScript for Udemy Course Planner

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('plannerForm');
    const generateBtn = document.getElementById('generateBtn');
    const loading = document.getElementById('loading');
    const result = document.getElementById('result');
    const planModal = new bootstrap.Modal(document.getElementById('planModal'));
    const downloadPlanBtn = document.getElementById('downloadPlanBtn');
    
    let currentPlanData = null;
    
    // Set default start date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('startDate').value = today;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        generateBtn.disabled = true;
        loading.style.display = 'block';
        result.style.display = 'none';
        
        const formData = new FormData(form);
        const courseUrl = formData.get('courseUrl');
        const weeks = parseInt(formData.get('weeks'));
        const startDate = formData.get('startDate');
        
        try {
            const response = await fetch('api/generate_plan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    course_url: courseUrl,
                    weeks: weeks,
                    start_date: startDate
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                currentPlanData = data;
                showSuccessResult(data);
            } else {
                showErrorResult(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorResult('An error occurred while generating the study plan. Please try again.');
        } finally {
            // Hide loading state
            generateBtn.disabled = false;
            loading.style.display = 'none';
        }
    });
    
    function showSuccessResult(data) {
        result.className = 'result success';
        result.style.display = 'block';
        
        result.innerHTML = `
            <div class="text-center">
                <h3><i class="fas fa-check-circle me-2"></i>Study Plan Generated Successfully!</h3>
                <div class="course-info">
                    <p><strong><i class="fas fa-book me-2"></i>Course:</strong> ${data.course_info.title}</p>
                    <p><strong><i class="fas fa-user me-2"></i>Instructor:</strong> ${data.course_info.instructor}</p>
                    <p><strong><i class="fas fa-clock me-2"></i>Total Duration:</strong> ${data.course_info.total_duration}</p>
                    <p><strong><i class="fas fa-calendar-day me-2"></i>Target Daily Time:</strong> ${data.course_info.target_daily_average}</p>
                    <p><strong><i class="fas fa-chart-line me-2"></i>Actual Daily Average:</strong> ${data.course_info.actual_daily_average}</p>
                </div>
                <div class="actions">
                    <button class="btn btn-secondary" onclick="previewPlan('${data.file_id}')">
                        <i class="fas fa-eye me-2"></i>Preview Plan
                    </button>
                    <button class="btn btn-secondary" onclick="downloadPlan('${data.file_id}')">
                        <i class="fas fa-download me-2"></i>Download Markdown
                    </button>
                </div>
            </div>
        `;
    }
    
    function showErrorResult(message) {
        result.className = 'result error';
        result.style.display = 'block';
        result.innerHTML = `
            <div class="text-center">
                <h3><i class="fas fa-exclamation-triangle me-2"></i>Error</h3>
                <p>${message}</p>
            </div>
        `;
    }
    
    // Global functions for buttons
    window.previewPlan = async function(fileId) {
        try {
            const response = await fetch(`api/preview_plan.php?file_id=${fileId}`);
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('planModalBody').innerHTML = `
                    <div class="plan-content">
                        ${data.html_content}
                    </div>
                `;
                planModal.show();
            } else {
                alert('Error loading plan preview: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading plan preview');
        }
    };
    
    window.downloadPlan = function(fileId) {
        // Create a temporary link to download the file
        const link = document.createElement('a');
        link.href = `api/download_plan.php?file_id=${fileId}`;
        link.download = 'course_plan.md';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
    
    // Handle download button in modal
    downloadPlanBtn.addEventListener('click', function() {
        if (currentPlanData) {
            downloadPlan(currentPlanData.file_id);
            planModal.hide();
        }
    });
    
    // Add some interactive features
    const courseUrlInput = document.getElementById('courseUrl');
    const weeksSelect = document.getElementById('weeks');
    
    // Auto-validate URL format
    courseUrlInput.addEventListener('blur', function() {
        const url = this.value.trim();
        if (url && !url.includes('udemy.com/course/')) {
            this.classList.add('is-invalid');
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Please enter a valid Udemy course URL';
                this.parentNode.appendChild(feedback);
            }
        } else {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        }
    });
    
    // Show estimated daily time based on weeks selection
    weeksSelect.addEventListener('change', function() {
        const weeks = parseInt(this.value);
        const courseUrl = courseUrlInput.value;
        
        if (courseUrl && courseUrl.includes('udemy.com/course/')) {
            // This would typically make an API call to get course duration
            // For now, we'll just show a placeholder
            console.log(`Selected ${weeks} weeks for study period`);
        }
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + Enter to submit form
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
        
        // Escape to close modal
        if (e.key === 'Escape' && planModal._element.classList.contains('show')) {
            planModal.hide();
        }
    });
    
    // Add tooltips for better UX
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}); 