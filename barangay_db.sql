-- Create database
CREATE DATABASE IF NOT EXISTS barangay_db;
USE barangay_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create residents table
CREATE TABLE IF NOT EXISTS residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL
);

-- Create complaints table
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_name VARCHAR(100) NOT NULL,
    details TEXT NOT NULL,
    status ENUM('Pending', 'Approved') DEFAULT 'Pending'
);

-- Create announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('Pending', 'Approved') DEFAULT 'Pending',
    FOREIGN KEY (resident_id) REFERENCES residents(id)
);

-- Create businesses table
CREATE TABLE IF NOT EXISTS businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(100) NOT NULL,
    owner_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    status ENUM('Pending', 'Approved') DEFAULT 'Pending'
);

-- Insert a sample user (password is 'password' hashed using bcrypt)
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFupQeX9Y6Y0lZ8l5h8y5l5l5l5l5l5l');

-- Add status column to appointments table
ALTER TABLE appointments ADD COLUMN status ENUM('Pending', 'Approved') DEFAULT 'Pending';

-- Add status column to businesses table
ALTER TABLE businesses ADD COLUMN status ENUM('Pending', 'Approved') DEFAULT 'Pending';