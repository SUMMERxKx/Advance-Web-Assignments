# Assignment 2 - PHP and MySQL

**Student:** Samar Khajuria  
**Student ID:** T00714740

## Overview

This assignment has two parts:
- Part 1: SQL queries for the my_guitar_shop2 database
- Part 2: SportsPro Technical Support web application

## Project Structure

```
COMP3541_A2_Samar_Khajuria/
├── includes/              # Shared includes (database, header, footer)
├── manage_products/       # Task 1
├── manage_technicians/    # Task 2
├── manage_customers/      # Task 3
├── register_product/      # Task 4
├── create_incident/       # Task 5
├── part1_sql/             # Part 1 SQL queries
│   ├── my_guitar_shop_answers.sql
│   └── my_guitar_shop2.sql
├── tech_support.sql       # Part 2 database
├── main.css
├── index.php
└── under_construction.php
```

## Setup

1. **Database Setup:**
   - Import `part1_sql/my_guitar_shop2.sql` for Part 1
   - Import `tech_support.sql` for Part 2
   - Both can be imported through phpMyAdmin

2. **Server Configuration:**
   - Works with MAMP, XAMPP, or similar
   - Database connection automatically tries multiple configurations
   - No need to modify database.php for standard setups

3. **Access:**
   - Place folder in htdocs (MAMP/XAMPP) or web root
   - Open in browser: `http://localhost/COMP3541_A2_Samar_Khajuria/`

## Part 1 - SQL Queries

Four SQL queries located in `part1_sql/my_guitar_shop_answers.sql`:
1. Find customers with 'brown' in name
2. Update products with price > $2000
3. Insert new order
4. Delete orders from date range

Test each query in phpMyAdmin SQL tab.

## Part 2 - Application Tasks

Five tasks implementing the SportsPro Technical Support system:

1. **Manage Products** - Add, view, delete products
2. **Manage Technicians** - Add, view, delete technicians
3. **Manage Customers** - Search, add, update customers
4. **Register Product** - Customer login and product registration
5. **Create Incident** - Create support incidents

## Features

- Relative paths throughout (portable)
- Prepared statements for all database queries
- Field-specific validation error messages
- Session management for workflows
- Custom confirmation dialogs

## Testing

After importing databases:
1. Start web server
2. Navigate to application URL
3. Test each task from main menu
4. Verify CRUD operations work correctly
