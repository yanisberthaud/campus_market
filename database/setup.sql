-- Create the Database
CREATE DATABASE IF NOT EXISTS campus_market;
USE campus_market;

-- 1. Categories table
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- 2. Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'staff', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

-- 3. Listings table
CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    user_id INT,
    category_id INT,
    status ENUM('active', 'sold', 'archived') DEFAULT 'active',
    item_condition ENUM('new', 'used', 'refurbished') DEFAULT 'used',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Default categories for a US college 
INSERT INTO categories(name) VALUES ('Textbooks', 'Electronics', 'Dorm Decor', 'School Supplies', 'Clothing');