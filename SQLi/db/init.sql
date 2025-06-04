-- Create database
CREATE DATABASE IF NOT EXISTS vulnweb;
USE vulnweb;

-- Create and configure user
CREATE USER IF NOT EXISTS 'vulnuser'@'%' IDENTIFIED BY 'vulnpass';
GRANT ALL PRIVILEGES ON vulnweb.* TO 'vulnuser'@'%' WITH GRANT OPTION;
GRANT FILE ON *.* TO 'vulnuser'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- Create tables
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS blind_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255),
    business_type VARCHAR(255),
    city VARCHAR(255),
    country VARCHAR(255)
);

-- Insert data
INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('user1', 'password123'),
('test', 'test123');

INSERT INTO blind_data (business_name, business_type, city, country) VALUES
('Acme Corp', 'Manufacturing', 'New York', 'USA'),
('Globex Inc', 'Technology', 'San Francisco', 'USA'),
('Initech', 'Software', 'Austin', 'USA'),
('Umbrella Corp', 'Pharmaceuticals', 'London', 'UK'),
('Wayne Enterprises', 'Conglomerate', 'Gotham', 'USA'),
('Stark Industries', 'Technology', 'New York', 'USA'),
('Oscorp', 'Biotechnology', 'New York', 'USA'),
('LexCorp', 'Conglomerate', 'Metropolis', 'USA'),
('Cyberdyne Systems', 'Technology', 'Sunnyvale', 'USA'),
('Weyland-Yutani', 'Technology', 'Tokyo', 'Japan'),
('Soylent Corp', 'Food', 'New York', 'USA'),
('Tyrell Corp', 'Technology', 'Los Angeles', 'USA'),
('Nakatomi Corp', 'Real Estate', 'Los Angeles', 'USA'),
('Omni Consumer Products', 'Conglomerate', 'Detroit', 'USA'),
('Dunder Mifflin', 'Paper', 'Scranton', 'USA'),
('Prestige Worldwide', 'Entertainment', 'San Diego', 'USA'),
('Vandelay Industries', 'Latex', 'New York', 'USA'),
('Hooli', 'Technology', 'Palo Alto', 'USA'),
('Pied Piper', 'Technology', 'Palo Alto', 'USA'),
('Aviato', 'Technology', 'Silicon Valley', 'USA'); 