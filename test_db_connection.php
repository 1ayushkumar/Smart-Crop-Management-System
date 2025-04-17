<?php
require_once 'config/database.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h3>Database Connection Test Results:</h3>";
echo "✓ Database connection successful<br>";

// Check if table exists
$table_check = $conn->query("SHOW TABLES LIKE 'real_time_readings'");
if ($table_check->num_rows > 0) {
    echo "✓ Table 'real_time_readings' exists<br>";
    
    // Check table structure
    $result = $conn->query("DESCRIBE real_time_readings");
    if ($result) {
        echo "✓ Table structure:<br>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']}</li>";
        }
        echo "</ul>";
    }
} else {
    echo "✗ Table 'real_time_readings' does not exist<br>";
    echo "Creating table...<br>";
    
    // Create table if it doesn't exist
    $create_table = "CREATE TABLE real_time_readings (
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
    )";
    
    if ($conn->query($create_table) === TRUE) {
        echo "✓ Table created successfully<br>";
    } else {
        echo "✗ Error creating table: " . $conn->error . "<br>";
    }
}

$conn->close();
?>