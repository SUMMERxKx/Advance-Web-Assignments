# COMP3541 – Assignment 3 (SportsPro Technical Support)
**Student:** Samar Khajuria  
**Student ID:** T00714740  
**Course:** COMP 3541 – Web Site Design & Programming  
**Assignment:** PHP/MySQL (10%)

## Installation Instructions

1. **Database Setup:**
   - Import the `tech_support.sql` file into your MySQL database (using phpMyAdmin or command line)
   - **Important:** Configure your database connection settings in `includes/config.php`
   - The database configuration is portable and can be customized for different environments

2. **File Placement:**
   - Place this entire folder in your web server's document root
   - For MAMP: `/Users/<your-username>/Sites/localhost/COMP3541_A3_Samar_Khajuria`
   - For XAMPP: `C:\xampp\htdocs\COMP3541_A3_Samar_Khajuria`

3. **Access the Application:**
   - Open your browser and navigate to the application URL
   - Example: `http://localhost:8888/COMP3541_A3_Samar_Khajuria/`
   - The application uses relative paths to ensure portability

4. **Database Connection Configuration:**
   - Database settings are configured in `includes/config.php` for portability
   - Default settings (no changes needed for most setups):
     - Host: `localhost`
     - Port: (default MySQL port 3306)
     - Database: `tech_support`
     - Username: `root`
     - Password: (empty)
   - **For MAMP with port 8889:** Edit `includes/config.php` and change:
     ```php
     define('DB_PORT', '8889');
     ```
   - **For custom MySQL setup:** Edit `includes/config.php` and modify:
     ```php
     define('DB_HOST', 'your-host');
     define('DB_PORT', 'your-port');  // or '' for default
     define('DB_NAME', 'your-database');
     define('DB_USER', 'your-username');
     define('DB_PASS', 'your-password');
     ```

5. **HTTPS Configuration:**
   - By default, HTTPS enforcement is disabled for local testing
   - To enable HTTPS in production, set `ENFORCE_HTTPS = true` in `includes/auth.php`

## Path Configuration

The application uses automatic path detection:
- `includes/config.php` handles base path calculation
- Use `app_url('path/to/file.php')` for navigation links
- Use `redirect_to('path/to/file.php')` for PHP redirects
- All paths are automatically adjusted based on installation location

## Test Accounts

**Administrator:**
- Username: `admin`
- Password: `sesame`

**Technician:**
- Email: `jason@sportspro.com` or `gunter@sportspro.com`
- Password: `sesame`

**Customer:**
- Email: `kelly@example.com`
- Password: `sesame`

## Project Structure

```
COMP3541_A3_Samar_Khajuria/
├─ index.php                    # Main entry point
├─ README.md                    # This file
├─ tech_support.sql             # Database schema and data
├─ main.css                     # Stylesheet
├─ includes/
│  ├─ auth.php                  # Authentication and HTTPS
│  ├─ database.php               # Database connection and queries
│  ├─ database_error.php         # Error page template
│  ├─ header.php                # Common header
│  ├─ footer.php                # Common footer
│  ├─ session.php                # Session management
│  └─ config.php                 # Configuration and path helpers
├─ admin/
│  ├─ login.php                  # Admin login
│  ├─ menu.php                   # Admin dashboard
│  └─ logout.php                 # Admin logout
├─ create_incident/
│  ├─ get_customer.php           # Customer lookup
│  └─ create_incident.php        # Create new incident
├─ assign_incident/
│  ├─ select_incident.php        # Choose incident to assign
│  ├─ select_technician.php      # Choose technician
│  └─ assign_incident.php        # Complete assignment
├─ display_incidents/
│  ├─ unassigned.php             # View unassigned incidents
│  └─ assigned.php               # View assigned incidents
├─ technician/
│  ├─ login.php                  # Technician login
│  ├─ menu.php                   # Technician menu
│  ├─ select_incident.php        # View assigned incidents
│  ├─ update_incident.php        # Update incident details
│  └─ logout.php                 # Technician logout
├─ register_product/
│  ├─ customer_login.php         # Customer login
│  ├─ register_product.php       # Product registration
│  └─ logout.php                 # Customer logout
├─ customer/
│  └─ menu.php                    # Customer menu
├─ manage_products/
│  ├─ index.php                  # List products
│  └─ add_product.php           # Add product
├─ manage_technicians/
│  ├─ index.php                  # List technicians
│  └─ add_technician.php        # Add technician
└─ manage_customers/
   ├─ select_customer.php        # Search customers
   └─ customer_form.php          # Add/edit customer
```

## Features Implemented

### Task 1: Create Incident
- Admin can search for customers by email
- Create incidents for selected customers
- Only shows products registered by the customer

### Task 2: Register Product with Sessions
- Customers can log in and register products
- Session-based authentication (no need to re-login)
- Logout functionality

### Task 3: Database Error Handling
- All database operations use try/catch blocks
- Prepared statements for security
- User-friendly error pages

### Task 4: Assign Incidents
- Admin can view unassigned incidents
- Select technician with workload display
- Assign incidents to technicians

### Task 5: Update Incidents
- Technicians can view their assigned incidents
- Update incident descriptions
- Close incidents with date

### Task 6: Display Incidents
- View unassigned incidents
- View assigned incidents with technician info
- Shows "OPEN" for unclosed incidents

### Task 7: User Authentication
- Secure login for admin, technician, and customer
- Role-based access control
- Session management
- Home page navigation

## Testing

See `TESTING_GUIDE.md` for detailed step-by-step testing instructions.

**Quick Test:**
1. **Setup:**
   - Import `tech_support.sql` into MySQL
   - Configure database connection in `includes/config.php` (if needed)
   - Place folder in web server directory
   - Access via browser: `http://localhost:8888/COMP3541_A3_Samar_Khajuria/`

2. **Test Admin Functions:**
   - Log in as admin (admin/sesame)
   - Create incidents
   - Assign incidents to technicians
   - View incident reports

3. **Test Technician Functions:**
   - Log in as technician (gunter@sportspro.com/sesame)
   - View assigned incidents
   - Update incident descriptions
   - Close incidents

4. **Test Customer Functions:**
   - Log in as customer (kelly@example.com/sesame)
   - Register products
   - Verify session persistence (close browser, reopen, should still be logged in)

## Notes

- All database queries use prepared statements for security
- Error handling is implemented throughout
- The application is portable and works in any directory structure
- Session cookies expire when browser closes (session cookies)
