CREATE DATABASE IF NOT EXISTS roxnor_db;
USE roxnor_db;

-- User Table (for Authentication)
CREATE TABLE IF NOT EXISTS users (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Submission Table (for Data Submission)
CREATE TABLE IF NOT EXISTS submissions (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    amount INT(10) NOT NULL,
    buyer VARCHAR(255) NOT NULL,
    receipt_id VARCHAR(20) NOT NULL,
    items VARCHAR(255) NOT NULL,
    buyer_email VARCHAR(50) NOT NULL,
    buyer_ip VARCHAR(20) NOT NULL,
    note TEXT,
    city VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    hash_key VARCHAR(255) NOT NULL,
    entry_at DATE NOT NULL,
    entry_by INT(10) NOT NULL,
    -- Foreign key to link submission to the user who made it
    FOREIGN KEY (entry_by) REFERENCES users(id) 
);