-- Complete database setup
-- Save as: database/portfolio.sql

-- Create database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Drop existing tables if they exist (be careful with this in production)
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS admins;

-- Create admins table
CREATE TABLE admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create messages table
CREATE TABLE messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);

-- Create projects table
CREATE TABLE projects (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    technologies VARCHAR(255),
    image_url VARCHAR(500),
    project_url VARCHAR(500),
    github_url VARCHAR(500),
    display_order INT(11) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_display_order (display_order)
);

-- Insert default admin (password: admin123)
-- You need to generate your own hash using: echo password_hash('admin123', PASSWORD_DEFAULT);
-- Replace the hash below with your generated hash
INSERT INTO admins (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'eliasmugisha@gmail.com');

-- Insert sample projects (optional)
INSERT INTO projects (title, description, technologies, display_order, status) VALUES
('E-Commerce Website', 'A fully functional e-commerce website with shopping cart and payment integration', 'PHP, MySQL, JavaScript, Stripe API', 1, 1),
('Task Management App', 'A task management application with real-time updates', 'React, Node.js, MongoDB, Socket.io', 2, 1),
('Portfolio Website', 'Modern portfolio website with admin dashboard', 'PHP, MySQL, HTML5, CSS3, JavaScript', 3, 1);

-- Sample message (optional)
INSERT INTO messages (name, email, subject, message, is_read) VALUES
('John Visitor', 'john@example.com', 'Great website!', 'I really like your portfolio website. The design is very professional.', 0);

-- Display success message
SELECT 'Database setup completed successfully!' AS message;