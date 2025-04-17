<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // Replace with your database username
define('DB_PASS', '');         // Replace with your database password
define('DB_NAME', 'crop_db');  // Changed back to crop_db

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        
        return $this->conn;
    }
}

// Function to save real-time readings
function saveRealtimeReading($temperature, $humidity, $moisture, $soil_type, $crop_type, $nitrogen, $phosphorus, $potassium, $location) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $stmt = $conn->prepare("INSERT INTO real_time_readings (temperature, humidity, moisture, soil_type, crop_type, nitrogen, phosphorus, potassium, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("dddssddds", $temperature, $humidity, $moisture, $soil_type, $crop_type, $nitrogen, $phosphorus, $potassium, $location);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}
?>
