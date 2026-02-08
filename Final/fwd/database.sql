-- Database: POSTMAN
-- Table: weather_searches

CREATE DATABASE IF NOT EXISTS POSTMAN;
USE POSTMAN;

-- Create weather_searches table
CREATE TABLE IF NOT EXISTS weather_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL,
    temperature DECIMAL(5,2) NOT NULL,
    CONDITIONs VARCHAR(100) NOT NULL,
    rain_chance INT NOT NULL,
    wind_speed DECIMAL(5,2) NOT NULL,
    humidity INT NOT NULL,
    visibility DECIMAL(5,2) NOT NULL,
    pressure DECIMAL(6,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_city_name (city_name),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Display table structure
DESCRIBE weather_searches;
