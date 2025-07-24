<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Udemy Course Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg mt-4">
                    <div class="card-header bg-primary text-white">
                        <h1 class="text-center mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Udemy Course Planner
                        </h1>
                    </div>
                    <div class="card-body">
                        <form id="plannerForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="courseUrl" class="form-label">
                                            <i class="fas fa-link me-2"></i>Udemy Course URL:
                                        </label>
                                        <input type="url" class="form-control" id="courseUrl" name="courseUrl" 
                                               placeholder="https://www.udemy.com/course/your-course-url/" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="weeks" class="form-label">
                                            <i class="fas fa-calendar-week me-2"></i>Study Period:
                                        </label>
                                        <select class="form-select" id="weeks" name="weeks">
                                            <option value="1">1 week</option>
                                            <option value="2" selected>2 weeks</option>
                                            <option value="3">3 weeks</option>
                                            <option value="4">4 weeks</option>
                                            <option value="6">6 weeks</option>
                                            <option value="8">8 weeks</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="startDate" class="form-label">
                                            <i class="fas fa-calendar-alt me-2"></i>Start Date:
                                        </label>
                                        <input type="date" class="form-control" id="startDate" name="startDate">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="generateBtn">
                                    <i class="fas fa-magic me-2"></i>Generate Study Plan
                                </button>
                            </div>
                        </form>
                        
                        <div id="loading" class="text-center mt-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Generating your study plan...</p>
                        </div>
                        
                        <div id="result" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Plan Preview -->
    <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planModalLabel">Study Plan Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="planModalBody">
                    <!-- Plan content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="downloadPlanBtn">
                        <i class="fas fa-download me-2"></i>Download Markdown
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html> 