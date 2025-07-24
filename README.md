# 📚 Udemy Course Planner (PHP Version)

A modern web application that generates structured study plans for Udemy courses using PHP, JavaScript, HTML, CSS, and Bootstrap.

## 🚀 Features

- **Modern Web Interface**: Beautiful, responsive design with Bootstrap 5
- **Study Plan Generation**: Creates personalized daily study schedules
- **Progress Tracking**: Markdown files with checkboxes for progress tracking
- **Preview Functionality**: View generated plans in a modal before downloading
- **File Management**: Automatic file generation with unique IDs
- **Cross-Platform**: Works on any device with a web browser

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.0
- **Icons**: Font Awesome 6.0.0
- **File Format**: Markdown (.md)

## 📁 Project Structure

```
udemy-planner/
├── index.php                 # Main application interface
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles
│   └── js/
│       └── script.js        # Frontend JavaScript
├── api/
│   ├── StudyPlanGenerator.php  # Core study plan logic
│   ├── generate_plan.php       # API endpoint for plan generation
│   ├── preview_plan.php        # API endpoint for plan preview
│   └── download_plan.php       # API endpoint for file download
├── generated_plans/         # Directory for generated markdown files
└── README.md               # This file
```

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- Modern web browser

### Installation

1. **Clone or download the project**:
   ```bash
   cd udemy-planner
   ```

2. **Set up a web server**:
   
   **Option A: PHP Built-in Server (Development)**
   ```bash
   php -S localhost:8000
   ```
   
   **Option B: Apache/Nginx**
   - Copy files to your web server directory
   - Ensure PHP is configured and enabled

3. **Set permissions** (if needed):
   ```bash
   chmod 755 generated_plans/
   chmod 644 api/*.php
   ```

4. **Access the application**:
   - Open your browser and navigate to `http://localhost:8000`
   - Or your web server URL

## 📖 Usage

### Basic Usage

1. **Enter Course URL**: Paste any Udemy course URL
2. **Select Study Period**: Choose how many weeks you want to complete the course
3. **Set Start Date**: Optionally set a start date (defaults to today)
4. **Generate Plan**: Click "Generate Study Plan"
5. **Preview & Download**: View the plan in the modal and download the markdown file

### Features Explained

#### Study Plan Generation
- Automatically calculates daily study time based on course duration
- Distributes lectures evenly across the selected timeframe
- Groups lectures by course sections for better organization
- Provides realistic daily time commitments

#### Progress Tracking
- Generated markdown files include checkboxes for each lecture
- Daily breakdown with specific dates
- Progress summary table
- Notes sections for personal study notes

#### File Management
- Each generated plan gets a unique ID
- Files are stored in the `generated_plans/` directory
- Automatic cleanup can be implemented (not included by default)

## 🔧 Configuration

### Customizing Study Plans

You can modify the sample course data in `api/StudyPlanGenerator.php`:

```php
function getSampleCompTIACourse() {
    return [
        'title' => 'Your Course Title',
        'instructor' => 'Instructor Name',
        'curriculum' => [
            // Your course structure here
        ]
    ];
}
```

### Styling Customization

Edit `assets/css/style.css` to customize the appearance:

```css
/* Custom color scheme */
.btn-primary {
    background: linear-gradient(135deg, #your-color-1 0%, #your-color-2 100%);
}
```

## 🎯 Study Tips

### Using Your Study Plan

1. **Check Off Progress**: Mark each lecture as complete: `- [x] Lecture Title`
2. **Stay on Schedule**: Follow the daily time allocations
3. **Take Notes**: Use the notes section for each day
4. **Review Regularly**: Revisit previous sections
5. **Be Consistent**: Study daily, even if just for 30 minutes

### Best Practices

- **Start Small**: Begin with shorter sessions
- **Take Breaks**: Don't try to complete everything in one sitting
- **Practice**: Apply what you learn through hands-on exercises
- **Don't Rush**: Understanding is more important than speed

## 🔧 Advanced Features

### Real Course Integration

To integrate with real Udemy courses, you would need to:

1. **Web Scraping**: Implement course data extraction from Udemy URLs
2. **API Integration**: Use Udemy's API (if available)
3. **Authentication**: Handle user login and course access

### Database Integration

For a production environment, consider adding:

- User accounts and authentication
- Database storage for study plans
- Progress tracking across sessions
- Social features and sharing

### Performance Optimization

- Implement caching for generated plans
- Add file cleanup for old plans
- Optimize markdown generation
- Add CDN for static assets

## 🐛 Troubleshooting

### Common Issues

**"Error generating study plan"**
- Check PHP error logs
- Ensure all required files are present
- Verify file permissions

**"File not found" errors**
- Check that `generated_plans/` directory exists
- Verify write permissions
- Ensure unique file IDs are being generated

**JavaScript errors**
- Check browser console for errors
- Ensure all assets are loading correctly
- Verify API endpoints are accessible

### Debug Mode

Add error reporting to debug issues:

```php
// Add to the top of PHP files for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📝 API Documentation

### Generate Plan Endpoint

**POST** `/api/generate_plan.php`

**Request Body:**
```json
{
    "course_url": "https://www.udemy.com/course/example/",
    "weeks": 2,
    "start_date": "2024-01-15"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Study plan generated successfully!",
    "file_id": "a1b2c3d4",
    "course_info": {
        "title": "Course Title",
        "instructor": "Instructor Name",
        "total_duration": "15h 30m",
        "target_daily_average": "1h 4m",
        "actual_daily_average": "1h 4m"
    }
}
```

### Preview Plan Endpoint

**GET** `/api/preview_plan.php?file_id=a1b2c3d4`

**Response:**
```json
{
    "success": true,
    "html_content": "<h1>Study Plan HTML</h1>",
    "file_id": "a1b2c3d4"
}
```

### Download Plan Endpoint

**GET** `/api/download_plan.php?file_id=a1b2c3d4`

Returns the markdown file for download.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Original Python version by the project creator
- Bootstrap team for the excellent CSS framework
- Font Awesome for the beautiful icons
- The open source community for inspiration and tools

## 📞 Support

If you encounter any issues or have questions:

1. Check the troubleshooting section above
2. Review the API documentation
3. Check PHP error logs
4. Create an issue in the project repository

---

**Happy Learning! 📚✨** 
# 📚 Udemy Course Planner (PHP Version)

A modern web application that generates structured study plans for Udemy courses using PHP, JavaScript, HTML, CSS, and Bootstrap.

## 🚀 Features

- **Modern Web Interface**: Beautiful, responsive design with Bootstrap 5
- **Study Plan Generation**: Creates personalized daily study schedules
- **Progress Tracking**: Markdown files with checkboxes for progress tracking
- **Preview Functionality**: View generated plans in a modal before downloading
- **File Management**: Automatic file generation with unique IDs
- **Cross-Platform**: Works on any device with a web browser

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.0
- **Icons**: Font Awesome 6.0.0
- **File Format**: Markdown (.md)

## 📁 Project Structure

```
udemy-planner/
├── index.php                 # Main application interface
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles
│   └── js/
│       └── script.js        # Frontend JavaScript
├── api/
│   ├── StudyPlanGenerator.php  # Core study plan logic
│   ├── generate_plan.php       # API endpoint for plan generation
│   ├── preview_plan.php        # API endpoint for plan preview
│   └── download_plan.php       # API endpoint for file download
├── generated_plans/         # Directory for generated markdown files
└── README.md               # This file
```

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- Modern web browser

### Installation

1. **Clone or download the project**:
   ```bash
   cd udemy-planner
   ```

2. **Set up a web server**:
   
   **Option A: PHP Built-in Server (Development)**
   ```bash
   php -S localhost:8000
   ```
   
   **Option B: Apache/Nginx**
   - Copy files to your web server directory
   - Ensure PHP is configured and enabled

3. **Set permissions** (if needed):
   ```bash
   chmod 755 generated_plans/
   chmod 644 api/*.php
   ```

4. **Access the application**:
   - Open your browser and navigate to `http://localhost:8000`
   - Or your web server URL

## 📖 Usage

### Basic Usage

1. **Enter Course URL**: Paste any Udemy course URL
2. **Select Study Period**: Choose how many weeks you want to complete the course
3. **Set Start Date**: Optionally set a start date (defaults to today)
4. **Generate Plan**: Click "Generate Study Plan"
5. **Preview & Download**: View the plan in the modal and download the markdown file

### Features Explained

#### Study Plan Generation
- Automatically calculates daily study time based on course duration
- Distributes lectures evenly across the selected timeframe
- Groups lectures by course sections for better organization
- Provides realistic daily time commitments

#### Progress Tracking
- Generated markdown files include checkboxes for each lecture
- Daily breakdown with specific dates
- Progress summary table
- Notes sections for personal study notes

#### File Management
- Each generated plan gets a unique ID
- Files are stored in the `generated_plans/` directory
- Automatic cleanup can be implemented (not included by default)

## 🔧 Configuration

### Customizing Study Plans

You can modify the sample course data in `api/StudyPlanGenerator.php`:

```php
function getSampleCompTIACourse() {
    return [
        'title' => 'Your Course Title',
        'instructor' => 'Instructor Name',
        'curriculum' => [
            // Your course structure here
        ]
    ];
}
```

### Styling Customization

Edit `assets/css/style.css` to customize the appearance:

```css
/* Custom color scheme */
.btn-primary {
    background: linear-gradient(135deg, #your-color-1 0%, #your-color-2 100%);
}
```

## 🎯 Study Tips

### Using Your Study Plan

1. **Check Off Progress**: Mark each lecture as complete: `- [x] Lecture Title`
2. **Stay on Schedule**: Follow the daily time allocations
3. **Take Notes**: Use the notes section for each day
4. **Review Regularly**: Revisit previous sections
5. **Be Consistent**: Study daily, even if just for 30 minutes

### Best Practices

- **Start Small**: Begin with shorter sessions
- **Take Breaks**: Don't try to complete everything in one sitting
- **Practice**: Apply what you learn through hands-on exercises
- **Don't Rush**: Understanding is more important than speed

## 🔧 Advanced Features

### Real Course Integration

To integrate with real Udemy courses, you would need to:

1. **Web Scraping**: Implement course data extraction from Udemy URLs
2. **API Integration**: Use Udemy's API (if available)
3. **Authentication**: Handle user login and course access

### Database Integration

For a production environment, consider adding:

- User accounts and authentication
- Database storage for study plans
- Progress tracking across sessions
- Social features and sharing

### Performance Optimization

- Implement caching for generated plans
- Add file cleanup for old plans
- Optimize markdown generation
- Add CDN for static assets

## 🐛 Troubleshooting

### Common Issues

**"Error generating study plan"**
- Check PHP error logs
- Ensure all required files are present
- Verify file permissions

**"File not found" errors**
- Check that `generated_plans/` directory exists
- Verify write permissions
- Ensure unique file IDs are being generated

**JavaScript errors**
- Check browser console for errors
- Ensure all assets are loading correctly
- Verify API endpoints are accessible

### Debug Mode

Add error reporting to debug issues:

```php
// Add to the top of PHP files for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📝 API Documentation

### Generate Plan Endpoint

**POST** `/api/generate_plan.php`

**Request Body:**
```json
{
    "course_url": "https://www.udemy.com/course/example/",
    "weeks": 2,
    "start_date": "2024-01-15"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Study plan generated successfully!",
    "file_id": "a1b2c3d4",
    "course_info": {
        "title": "Course Title",
        "instructor": "Instructor Name",
        "total_duration": "15h 30m",
        "target_daily_average": "1h 4m",
        "actual_daily_average": "1h 4m"
    }
}
```

### Preview Plan Endpoint

**GET** `/api/preview_plan.php?file_id=a1b2c3d4`

**Response:**
```json
{
    "success": true,
    "html_content": "<h1>Study Plan HTML</h1>",
    "file_id": "a1b2c3d4"
}
```

### Download Plan Endpoint

**GET** `/api/download_plan.php?file_id=a1b2c3d4`

Returns the markdown file for download.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Original Python version by the project creator
- Bootstrap team for the excellent CSS framework
- Font Awesome for the beautiful icons
- The open source community for inspiration and tools

## 📞 Support

If you encounter any issues or have questions:

1. Check the troubleshooting section above
2. Review the API documentation
3. Check PHP error logs
4. Create an issue in the project repository

---

**Happy Learning! 📚✨** 