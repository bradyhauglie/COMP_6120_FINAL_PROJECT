# Database Term Project - Setup Guide

## Files Included

1. **index.php** - Main web interface for querying the database
2. **db_config.php** - Database configuration file (optional)
3. **setup_database.sql** - SQL script to create and populate all tables
4. **sql.txt** - All 19 project queries with correct SQL statements
5. **url.txt** - Your website URL (you need to create this)

## Setup Instructions

### Step 1: Connect to VPN
Before you start, make sure you're connected to Auburn's VPN (GlobalProtect) if you're off-campus or on AU_WiFi.

### Step 2: Access MySQL Database
1. Open MySQL Workbench
2. Create a new connection with these settings:
   - Hostname: mysqllab.auburn.edu
   - Port: 3306
   - Username: [your AU username]
   - Password: [your MySQL password - NOT your AU password]

### Step 3: Create and Populate the Database
Option A - Using MySQL Workbench:
1. Open MySQL Workbench and connect to your database
2. Open the `setup_database.sql` file
3. Execute the entire script (this will create all tables and insert all data)

Option B - Using your Web Interface:
1. Upload the PHP files first (see Step 4)
2. Copy each CREATE TABLE statement from `setup_database.sql` one at a time
3. Paste into your web interface and submit
4. Then copy each INSERT statement and submit them

### Step 4: Upload Files to Mallard Server
1. Connect to mysqllab.auburn.edu using WinSCP (Windows) or scp command (Mac/Linux)
   - Protocol: SFTP
   - Host: mysqllab.auburn.edu
   - Port: 22
   - Username: [your AU username]
   - Password: [your AU password]

2. Navigate to: /home/home$$/[your_username]/public_html/

3. Upload these files:
   - index.php (this MUST be named index.php)
   - db_config.php (optional)

### Step 5: Configure Database Connection
Edit the index.php file and update these lines (around line 52-55):

```php
$servername = "mysqllab.auburn.edu";
$username = "your_username";  // Replace with your AU username
$password = "your_password";  // Replace with your MySQL password
$dbname = "your_username";    // Usually same as your username
```

### Step 6: Test Your Interface
1. Open a web browser
2. Go to: http://auburn.edu/~[your_username]/
3. You should see the "Database Term Project" interface
4. Try running a simple query: `SELECT * FROM db_book;`

### Step 7: Create url.txt
Create a file named `url.txt` with your website URL:
```
http://auburn.edu/~your_username/
```

## Testing the Queries

All 19 project queries are in the `sql.txt` file. To test them:

1. Open `sql.txt`
2. Copy Query 1
3. Paste it into your web interface
4. Click Submit
5. Verify the results
6. Repeat for all 19 queries

## Project Submission Checklist

Create a zip file named `YourAuburnUsername.zip` containing:

1. **url.txt** - Contains your website URL
2. **src/** folder containing:
   - index.php
   - db_config.php (if you used it)
   - Any other PHP files you created
3. **sql.txt** - All 19 queries (already created for you)

## Common Issues and Solutions

### Issue: "Connection failed"
- Make sure you're connected to VPN
- Verify your database credentials are correct
- Check that your database has been created by OIT

### Issue: "Table doesn't exist"
- Run the setup_database.sql script first
- Make sure all CREATE TABLE statements executed successfully

### Issue: "Access denied"
- You're probably using your AU password instead of your MySQL password
- Your MySQL password is the one you set when requesting the database

### Issue: Website shows PHP code instead of running
- Make sure the file is named `index.php` (not .html)
- Verify you uploaded to the correct directory: public_html
- Check file permissions (should be readable)

### Issue: "Forbidden" or "403 Error"
- Check file permissions in WinSCP
- Make sure you're in the public_html directory
- The file should have read permissions for "others"

## Query Explanations

### Query 1: Subject names of books supplied by supplier2
Joins books with subjects and suppliers to find subjects for supplier2's books.

### Query 2: Most expensive book from supplier3
Finds the book with highest price from supplier3 using ORDER BY and LIMIT.

### Query 3: Books ordered by lastname1 firstname1
Uses DISTINCT to show unique book titles ordered by a specific customer.

### Query 4: Books with more than 10 units in stock
Simple WHERE clause filtering books by quantity.

### Query 5: Total price paid by lastname1 firstname1
Sums up (price × quantity) for all books ordered by the customer.

### Query 6: Customers who paid less than $80
Groups by customer and uses HAVING to filter by total spending.

### Query 7: Books supplied by supplier2
Simple join between books and suppliers.

### Query 8: Total price per customer (descending)
Groups by customer, sums total spending, orders descending.

### Query 9: Books shipped on 08/04/2016
Filters orders by shipped date and shows books with their shippers.

### Query 10: Books ordered by BOTH customers
Uses subquery to find books that appear in both customers' orders.

### Query 11: Books handled by employee lastname6 firstname6
Joins orders with employees to find books the employee processed.

### Query 12: Total quantities of ordered books (ascending)
Groups by book and sums quantities, ordered ascending.

### Query 13: Customers who ordered at least 2 different books
Uses COUNT(DISTINCT) with HAVING to find customers with 2+ different books.

### Query 14: Customers who ordered category3 or category4 books
Filters by subject category using IN clause.

### Query 15: Customers who ordered author1's books
Filters books by author and shows customers who ordered them.

### Query 16: Total sales per employee
Sums sales value for each employee who processed orders.

### Query 17: Open orders at midnight 08/04/2016
Finds orders not yet shipped by the given date.

### Query 18: Customers with multiple books (descending quantity)
Shows customers who ordered more than 1 book type with total quantities.

### Query 19: Customers who ordered more than 3 books
Filters customers with more than 3 different books ordered.

## Tips for Success

1. **Test incrementally**: Test each query individually before moving to the next
2. **Check your data**: Verify all tables are populated correctly
3. **Use DISTINCT carefully**: Some queries need it, others don't
4. **Pay attention to NULL values**: Query 17 specifically looks for NULL shipped dates
5. **Read error messages**: MySQL error messages usually tell you exactly what's wrong
6. **Double-check joins**: Make sure you're joining on the correct foreign keys

## Contact Information

If you encounter issues:
- Email OIT for database/server issues: bransby@auburn.edu
- Check project documentation for requirements
- Review the project PDF for specific details

Good luck with your project!
