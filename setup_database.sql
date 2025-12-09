-- Database Setup Script for Bookstore Database
-- This script creates all tables and populates them with data

-- Create db_subject table
CREATE TABLE IF NOT EXISTS db_subject (
    SubjectID INT PRIMARY KEY,
    CategoryName VARCHAR(50)
);

-- Create db_supplier table
CREATE TABLE IF NOT EXISTS db_supplier (
    SupplierID INT PRIMARY KEY,
    CompanyName VARCHAR(100),
    ContactLastName VARCHAR(50),
    ContactFirstName VARCHAR(50),
    Phone VARCHAR(20)
);

-- Create db_book table
CREATE TABLE IF NOT EXISTS db_book (
    BookID INT PRIMARY KEY,
    Title VARCHAR(100),
    UnitPrice DECIMAL(10,2),
    Author VARCHAR(100),
    Quantity INT,
    SupplierID INT,
    SubjectID INT,
    FOREIGN KEY (SupplierID) REFERENCES db_supplier(SupplierID),
    FOREIGN KEY (SubjectID) REFERENCES db_subject(SubjectID)
);

-- Create db_customer table
CREATE TABLE IF NOT EXISTS db_customer (
    CustomerID INT PRIMARY KEY,
    LastName VARCHAR(50),
    FirstName VARCHAR(50),
    Phone VARCHAR(20)
);

-- Create db_employee table
CREATE TABLE IF NOT EXISTS db_employee (
    EmployeeID INT PRIMARY KEY,
    LastName VARCHAR(50),
    FirstName VARCHAR(50)
);

-- Create db_shipper table
CREATE TABLE IF NOT EXISTS db_shipper (
    ShipperID INT PRIMARY KEY,
    ShpperName VARCHAR(50)
);

-- Create db_order table
CREATE TABLE IF NOT EXISTS db_order (
    OrderID INT PRIMARY KEY,
    CustomerID INT,
    EmployeeID INT,
    OrderDate VARCHAR(20),
    ShippedDate VARCHAR(20),
    ShipperID INT,
    FOREIGN KEY (CustomerID) REFERENCES db_customer(CustomerID),
    FOREIGN KEY (EmployeeID) REFERENCES db_employee(EmployeeID),
    FOREIGN KEY (ShipperID) REFERENCES db_shipper(ShipperID)
);

-- Create db_order_detail table
CREATE TABLE IF NOT EXISTS db_order_detail (
    BookID INT,
    OrderID INT,
    Quantity INT,
    PRIMARY KEY (BookID, OrderID),
    FOREIGN KEY (BookID) REFERENCES db_book(BookID),
    FOREIGN KEY (OrderID) REFERENCES db_order(OrderID)
);

-- Populate db_subject
INSERT INTO db_subject (SubjectID, CategoryName) VALUES
(1, 'category1'),
(2, 'category2'),
(3, 'category3'),
(4, 'category4'),
(5, 'category5');

-- Populate db_supplier
INSERT INTO db_supplier (SupplierID, CompanyName, ContactLastName, ContactFirstName, Phone) VALUES
(1, 'supplier1', 'company1', 'company1', '1111111111'),
(2, 'supplier2', 'company2', 'company2', '2222222222'),
(3, 'supplier3', 'company3', 'company3', '3333333333'),
(4, 'supplier4', 'company4', '', '4444444444');

-- Populate db_book
INSERT INTO db_book (BookID, Title, UnitPrice, Author, Quantity, SupplierID, SubjectID) VALUES
(1, 'book1', 12.34, 'author1', 5, 3, 1),
(2, 'book2', 56.78, 'author2', 2, 3, 1),
(3, 'book3', 90.12, 'author3', 10, 2, 1),
(4, 'book4', 34.56, 'author4', 12, 3, 2),
(5, 'book5', 78.90, 'author5', 5, 2, 2),
(6, 'book6', 12.34, 'author6', 30, 1, 3),
(7, 'book7', 56.90, 'author2', 17, 3, 4),
(8, 'book8', 33.44, 'author7', 2, 1, 3);

-- Populate db_customer
INSERT INTO db_customer (CustomerID, LastName, FirstName, Phone) VALUES
(1, 'lastname1', 'firstname1', '334-001-001'),
(2, 'lastname2', 'firstname2', '334-002-002'),
(3, 'lastname3', 'firstname3', '334-003-003'),
(4, 'lastname4', 'firstname4', '334-004-004');

-- Populate db_employee
INSERT INTO db_employee (EmployeeID, LastName, FirstName) VALUES
(1, 'lastname5', 'firstname5'),
(2, 'lastname6', 'firstname6'),
(3, 'lastname6', 'firstname9');

-- Populate db_shipper
INSERT INTO db_shipper (ShipperID, ShpperName) VALUES
(1, 'shipper1'),
(2, 'shipper2'),
(3, 'shipper3'),
(4, 'shipper4');

-- Populate db_order
INSERT INTO db_order (OrderID, CustomerID, EmployeeID, OrderDate, ShippedDate, ShipperID) VALUES
(1, 1, 1, '8/1/2016', '8/3/2016', 1),
(2, 1, 2, '8/4/2016', NULL, NULL),
(3, 2, 1, '8/1/2016', '8/4/2016', 2),
(4, 4, 2, '8/4/2016', '8/4/2016', 1),
(5, 1, 1, '8/4/2016', '8/5/2016', 1),
(6, 4, 2, '8/4/2016', '8/5/2016', 1),
(7, 3, 1, '8/4/2016', '8/4/2016', 1);

-- Populate db_order_detail
INSERT INTO db_order_detail (BookID, OrderID, Quantity) VALUES
(1, 1, 2),
(4, 1, 1),
(6, 2, 2),
(7, 2, 3),
(5, 3, 1),
(3, 4, 2),
(4, 4, 1),
(7, 4, 1),
(1, 5, 1),
(1, 6, 2),
(1, 7, 1);
