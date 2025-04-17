CREATE TABLE real_time_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT,
    humidity FLOAT,
    moisture FLOAT,
    soil_type VARCHAR(50),
    crop_type VARCHAR(50),
    nitrogen FLOAT,
    phosphorus FLOAT,
    potassium FLOAT,
    reading_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR(100)
);
