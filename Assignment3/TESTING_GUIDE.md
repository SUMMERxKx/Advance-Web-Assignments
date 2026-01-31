# Testing Guide for Assignment 3

## Step 1: Setup

### 1.1 Database Setup
1. Open phpMyAdmin (or your MySQL client)
2. Import the `tech_support.sql` file
   - In phpMyAdmin: Click "Import" tab → Choose file → Select `tech_support.sql` → Go
3. Verify the database was created:
   - You should see a `tech_support` database with tables: administrators, customers, incidents, products, registrations, technicians, countries

### 1.2 Configure Database Connection
1. Open `includes/config.php`
2. The database configuration is located at the top of the file (lines 11-29)
3. Default settings should work for most setups (no changes needed):
   - Host: `localhost`
   - Port: (default MySQL port 3306)
   - Database: `tech_support`
   - Username: `root`
   - Password: (empty)
4. **For MAMP with port 8889:** Change line 19 to:
   ```php
   define('DB_PORT', '8889');
   ```
5. **For MAMP with root/root:** Change line 28 to:
   ```php
   define('DB_PASS', 'root');
   ```
6. **For custom setup:** Modify the `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS` constants as needed

### 1.3 File Placement
1. Copy the entire `COMP3541_A3_Samar_Khajuria` folder to your web server:
   - **MAMP**: `/Users/yourname/Sites/localhost/COMP3541_A3_Samar_Khajuria`
   - **XAMPP**: `C:\xampp\htdocs\COMP3541_A3_Samar_Khajuria`
   - Or any location your web server can access

### 1.4 Start Web Server
1. Start MAMP/XAMPP (make sure Apache and MySQL are running)
2. Open browser and go to: `http://localhost:8888/COMP3541_A3_Samar_Khajuria/`
   - Adjust port if different (MAMP default is 8888, XAMPP is 80)

## Step 2: Test Each Task

### Task 1: Create Incident (10 marks)

**Test Steps:**
1. Go to main page → Click "Administrators"
2. Login: username=`admin`, password=`sesame`
3. Click "Create Incident"
4. Enter customer email: `kelly@example.com` → Click "Get Customer"
5. Verify: Should show "Create Incident" page with customer name
6. Check: Product dropdown should only show products that customer has registered
7. Fill in:
   - Product: Select one from dropdown
   - Title: "Test Incident"
   - Description: "Testing incident creation"
8. Click "Create Incident"
9. **Verify**: Should see message "Incident added to the database."

**What to Check:**
- ✅ Customer lookup works
- ✅ Only registered products appear in dropdown
- ✅ Success message displays
- ✅ Incident appears in database

---

### Task 2: Register Product with Sessions (10 marks)

**Test Steps:**
1. Go to main page → Click "Customers"
2. Login: email=`kelly@example.com`, password=`sesame`
3. Should go directly to "Register Product" page (session working)
4. Select a product from dropdown
5. Click "Register Product"
6. **Verify**: Should see success message with product code
7. **Test Session**: Close browser tab, open new tab, go to Customers link
8. **Verify**: Should skip login and go directly to Register Product (session persisted)
9. Click "Logout"
10. **Verify**: Should go back to Customer Login page

**What to Check:**
- ✅ Login works
- ✅ Session persists (can skip login on return)
- ✅ Product registration works
- ✅ Logout works
- ✅ Session ends when browser closes (test by closing all tabs)

---

### Task 3: Database Error Handling (10 marks)

**Test Steps:**
1. To test error handling, temporarily break a query:
   - Open `includes/config.php`
   - Change line 22: `define('DB_NAME', 'wrong_db_name');`
2. Try to access any page
3. **Verify**: Should see "Database Error" page
4. Change it back to `'tech_support'`
5. All pages should work normally

**What to Check:**
- ✅ Error page displays when database fails
- ✅ All queries use try/catch (check code)
- ✅ All queries use prepared statements (check code)

---

### Task 4: Assign Incidents (10 marks)

**Test Steps:**
1. Login as admin: username=`admin`, password=`sesame`
2. Click "Assign Incident"
3. **Verify**: Should see table of unassigned incidents (techID IS NULL)
4. **Verify**: Table shows customer name, product name, incident details (JOIN working)
5. Click "Select" on an incident
6. **Verify**: Should see "Select Technician" page with incident number
7. **Verify**: Table shows technician name, email, and "Open Incidents" count (correlated subquery)
8. Click "Select" on a technician
9. **Verify**: Should see "Incident #X assigned successfully."
10. Click "Select Another Incident"
11. **Verify**: Should go back to incident selection

**What to Check:**
- ✅ JOIN query shows customer and product names
- ✅ Correlated subquery shows open incident count
- ✅ Assignment works
- ✅ Success message displays
- ✅ Incident ID stored in session (check code)

---

### Task 5: Update Incidents (10 marks)

**Test Steps:**
1. First, assign an incident to a technician (use Task 4)
2. Go to main page → Click "Technicians"
3. Login: email=`gunter@sportspro.com`, password=`sesame`
4. **Verify**: Should see "Select Incident" page with assigned incidents
5. **Verify**: Only shows incidents assigned to this technician AND not closed
6. Click "Select" on an incident
7. **Verify**: Should see "Update Incident" page
8. Modify the description
9. Optionally enter a date in "Date Closed" field (YYYY-MM-DD format)
10. Click "Update Incident"
11. **Verify**: Should see "Incident updated." message
12. If you closed it, go back to "Select Incident"
13. **Verify**: Closed incident should NOT appear (filtered out)
14. Click "Logout"
15. **Verify**: Should go to Technician Login page

**What to Check:**
- ✅ Only shows assigned incidents for logged-in technician
- ✅ Only shows open incidents (dateClosed IS NULL)
- ✅ Update description works
- ✅ Close incident works
- ✅ Closed incidents don't appear in list
- ✅ Login status and logout button visible

---

### Task 6: Display Incidents (10 marks)

**Test Steps:**
1. Login as admin: username=`admin`, password=`sesame`
2. Click "Display Incidents"
3. **Verify**: Should see "Unassigned Incidents" page
4. **Verify**: Table shows: ID, Opened, Customer, Product, Title, Description
5. Click "View Assigned Incidents"
6. **Verify**: Should see "Assigned Incidents" page
7. **Verify**: Table shows: ID, Opened, Customer, Product, Technician, Title, Description, Closed
8. **Verify**: Closed column shows date if closed, or "OPEN" if not closed
9. Click "View Unassigned Incidents"
10. **Verify**: Should go back to unassigned page

**What to Check:**
- ✅ Unassigned page shows all required fields
- ✅ Assigned page shows technician name
- ✅ "OPEN" displays for unclosed incidents
- ✅ Date displays for closed incidents

---

### Task 7: User Authentication (20 marks)

**Test Admin:**
1. Go to main page → Click "Administrators"
2. **Verify**: Should see Admin Login page
3. Login: username=`admin`, password=`sesame`
4. **Verify**: Should see Admin Menu
5. **Verify**: Should see "You are logged in as admin" message
6. **Verify**: Header shows "Home" button
7. Click "Home" button
8. **Verify**: Should go to main menu
9. Go back to Admin Menu
10. Click "Logout"
11. **Verify**: Should go back to Admin Login page
12. Try to access `admin/menu.php` directly
13. **Verify**: Should redirect to index.php (unauthorized access blocked)

**Test Technician:**
1. Go to main page → Click "Technicians"
2. **Verify**: Should see Technician Login page
3. Login: email=`gunter@sportspro.com`, password=`sesame`
4. **Verify**: Should see "Select Incident" page (skips menu)
5. **Verify**: Should see "You are logged in as gunter@sportspro.com"
6. **Verify**: Should see Logout button
7. Click "Logout"
8. **Verify**: Should go to Technician Login page

**Test Customer:**
1. Go to main page → Click "Customers"
2. **Verify**: Should see Customer Login page
3. Login: email=`kelly@example.com`, password=`sesame`
4. **Verify**: Should see Register Product page
5. **Verify**: Should see "You are logged in as kelly@example.com"
6. **Verify**: Should see Logout button
7. Click "Logout"
8. **Verify**: Should go to Customer Login page

**What to Check:**
- ✅ All login pages work
- ✅ Unauthorized access is blocked (try accessing admin/menu.php without login)
- ✅ Home button appears in header on all pages
- ✅ Login status displays correctly
- ✅ Logout works for all user types

---

## Step 3: Quick Test Checklist

Run through this quick checklist:

- [ ] Main page loads
- [ ] Admin login works (admin/sesame)
- [ ] Technician login works (gunter@sportspro.com/sesame)
- [ ] Customer login works (kelly@example.com/sesame)
- [ ] Create incident works (Task 1)
- [ ] Register product works (Task 2)
- [ ] Session persists (Task 2)
- [ ] Assign incident works (Task 4)
- [ ] Update incident works (Task 5)
- [ ] Display incidents shows all fields (Task 6)
- [ ] "OPEN" displays correctly (Task 6)
- [ ] All pages have Home button (Task 7)
- [ ] Unauthorized access blocked (Task 7)
- [ ] CSS styling looks correct
- [ ] No PHP errors displayed
- [ ] All forms submit correctly

## Step 4: Common Issues

**Database Connection Error:**
- Check `includes/config.php` database configuration (DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS)
- Make sure MySQL is running
- Verify database name is `tech_support`
- For MAMP with port 8889, ensure `DB_PORT` is set to `'8889'`

**Page Not Found:**
- Check file paths are correct
- Make sure web server is running
- Verify folder is in correct location

**Session Not Working:**
- Check `includes/session.php` is included
- Verify session cookie settings
- Clear browser cache and try again

**CSS Not Loading:**
- Check `main.css` file exists
- Verify path in `includes/header.php`
- Check browser console for errors

## Step 5: Final Verification

Before submitting, verify:
1. ✅ All 7 tasks work correctly
2. ✅ No PHP errors or warnings
3. ✅ All pages display correctly
4. ✅ CSS styling is applied
5. ✅ Database operations work
6. ✅ Session management works
7. ✅ Error handling works
8. ✅ All links and navigation work
