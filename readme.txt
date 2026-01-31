================================================================================
                    RECIPE MANAGEMENT AND MEAL PLANNER
                           README / Installation Guide
================================================================================

Student: Samar Khajuria
Student ID: T00714740
Course: COMP 3541 - Server-Side Web Development
Institution: Thompson Rivers University
Date: January 2026

================================================================================
                              PROJECT OVERVIEW
================================================================================

A web application for managing personal recipes and planning meals. Users can:
- Register an account and login securely
- Create, view, edit, and delete personal recipes
- Search and sort recipes by various criteria
- Plan meals by assigning recipes to specific dates and meal types
- View upcoming meals in a calendar-style layout

================================================================================
                            SYSTEM REQUIREMENTS
================================================================================

- PHP 7.4 or higher (PDO extension required)
- MySQL 5.7 or higher / MariaDB 10.x
- Web server with PHP support (Apache, Nginx, etc.)
- XAMPP v8.x or similar local development environment

================================================================================
                          INSTALLATION INSTRUCTIONS
================================================================================

1. EXTRACT FILES
   - Unzip the project folder to your web server's document root
   - For XAMPP: place in C:\xampp\htdocs\ (Windows) or /Applications/XAMPP/htdocs/ (Mac)
   - The folder structure should be: htdocs/Final/

2. CREATE DATABASE
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "New" to create a new database
   - Name it: recipe_planner
   - Collation: utf8mb4_general_ci
   - Click "Create"

3. IMPORT DATABASE SCHEMA
   - Select the recipe_planner database
   - Click the "Import" tab
   - Click "Choose File" and select "recipe_planner.sql" from the project folder
   - Click "Import" at the bottom
   - You should see 3 tables created: users, recipes, meal_plans

4. CONFIGURE DATABASE CONNECTION (if needed)
   - Open includes/database.php in a text editor
   - Modify the following lines if your setup differs:
     
     $dsn = 'mysql:host=localhost;dbname=recipe_planner';
     $username = 'root';
     $password = 'root';
   
   - For MAMP on Mac, use password: 'root'
   - For some XAMPP installs, password may be empty: ''

5. ACCESS THE APPLICATION
   - Open your browser and navigate to:
     http://localhost/Final/
   - You should see the home page

================================================================================
                              TEST ACCOUNTS
================================================================================

The database includes sample test accounts:

Account 1:
  Email: test@example.com
  Password: password

Account 2:
  Email: samar@example.com
  Password: password

================================================================================
                            FOLDER STRUCTURE
================================================================================

Final/
├── includes/                  # Shared PHP components
│   ├── database.php          # Database connection and helpers
│   ├── database_error.php    # Error display page
│   ├── header.php            # Header template
│   └── footer.php            # Footer template
├── auth/                      # Authentication pages
│   ├── register.php          # User registration
│   ├── login.php             # User login
│   └── logout.php            # User logout
├── recipes/                   # Recipe management
│   ├── list.php              # View all recipes
│   ├── add.php               # Add new recipe
│   ├── edit.php              # Edit recipe
│   └── delete.php            # Delete recipe
├── meals/                     # Meal planning
│   ├── planner.php           # View meal calendar
│   ├── add.php               # Plan a meal
│   ├── edit.php              # Edit planned meal
│   └── delete.php            # Remove planned meal
├── index.php                  # Home/landing page
├── dashboard.php              # User dashboard
├── main.css                   # Global stylesheet
├── recipe_planner.sql         # Database schema and seed data
├── readme.txt                 # This file
└── Proposal.txt               # Project proposal

================================================================================
                           FEATURES IMPLEMENTED
================================================================================

AUTHENTICATION
- User registration with validation
- Secure password hashing (bcrypt via password_hash)
- Session-based login/logout
- Protected pages redirect to login

RECIPE MANAGEMENT (CRUD)
- Create recipes with title, description, ingredients, instructions, prep time
- Read/view list of all user's recipes
- Update/edit existing recipes
- Delete recipes with confirmation
- Search by title, description, or ingredients
- Sort by date added, alphabetical, or prep time

MEAL PLANNING (CRUD)
- Create meal plans by selecting recipe, date, and meal type
- Read/view meals in calendar grid layout
- Update meal plans
- Delete meal plans with confirmation
- Filter by date range

SECURITY MEASURES
- Prepared statements for all database queries (SQL injection prevention)
- Output escaping with htmlspecialchars (XSS prevention)
- Session-based authentication
- User data isolation (users can only access their own data)

================================================================================
                            TECHNOLOGIES USED
================================================================================

Backend:
- PHP 7.4+ with PDO for database access
- MySQL/MariaDB relational database
- Session-based authentication

Frontend:
- HTML5 semantic markup
- CSS3 with custom properties (design system)
- Vanilla JavaScript (no frameworks)

Design:
- Modern, minimalistic Apple-inspired aesthetics
- Responsive layout
- Custom confirmation dialogs

================================================================================
                              REFERENCES
================================================================================

- Course materials from COMP 3541 - Thompson Rivers University
- PHP Manual: https://www.php.net/docs.php
- MySQL Documentation: https://dev.mysql.com/doc/
- MDN Web Docs: https://developer.mozilla.org/

================================================================================
                        CODE PORTABILITY NOTES
================================================================================

- All paths are relative (no absolute paths)
- No environment variables required
- No server settings modifications needed
- Works with standard XAMPP/MAMP/WAMP configurations
- Database credentials can be easily modified in includes/database.php

================================================================================
                           END OF README
================================================================================
