-- Database schema for Vantage Point International website
-- This file is auto-executed by the MySQL container the first time it starts
-- (mounted into /docker-entrypoint-initdb.d/ by docker-compose.yml)

CREATE DATABASE IF NOT EXISTS vantage_point_international;
USE vantage_point_international;

-- Used by Login_Form.php (user login)
CREATE TABLE IF NOT EXISTS user_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- A demo account so you can log in and demonstrate the site during the viva
INSERT INTO user_information (username, password, full_name)
VALUES ('demo', 'demo123', 'Demo User')
ON DUPLICATE KEY UPDATE username = username;

-- Used by Ticket Submission.php and view_tickets.php
CREATE TABLE IF NOT EXISTS software_issue_tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_title VARCHAR(200) NOT NULL,
    issue_description TEXT NOT NULL,
    sap_number VARCHAR(100) NOT NULL,
    outlet_location VARCHAR(150) NOT NULL,
    priority VARCHAR(20) NOT NULL DEFAULT 'Low',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Used by contact.php
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    service VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
