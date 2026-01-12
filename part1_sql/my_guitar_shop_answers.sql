/*
 * Assignment 2 - Part 1
 * 
 * Student: Samar Khajuria
 * Student ID: T00714740
 * 
 * This SQL file contains answers to Part 1 of Assignment 2
 * for the My Guitar Shop database (my_guitar_shop2).
 * 
 */

-- 1. Find all customers whose first or last name contains 'brown' (case insensitive)
SELECT customerID, firstName, lastName, emailAddress
FROM customers
WHERE LOWER(firstName) LIKE '%brown%'
   OR LOWER(lastName)  LIKE '%brown%';


-- 2. Update all products with a list price greater than $2000
--    Set discountPercent to 25 and update dateAdded to the current date
UPDATE products
SET discountPercent = 25,
    dateAdded = CURDATE()
WHERE listPrice > 2000;


-- 3. Insert a new order for customerID 3 using the current date
INSERT INTO orders
(customerID, orderDate, shipAmount, taxAmount, shipDate,
 shipAddressID, cardType, cardNumber, cardExpires, billingAddressID)
VALUES
(3, CURDATE(), 20.00, 23.56, DATE_ADD(CURDATE(), INTERVAL 7 DAY), 3, 2, '4123123412341234', '05/2024', 3);

-- Verify that the order was inserted correctly
SELECT * FROM orders;


-- 4. Delete all orders created between June 1, 2017 and June 7, 2017 (inclusive)
DELETE FROM orders
WHERE orderDate BETWEEN '2017-06-01' AND '2017-06-07';
