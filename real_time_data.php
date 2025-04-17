<?php
require_once 'config/database.php';
require_once 'config/weather_config.php';

class RealTimeDataManager {
    private $location;
    private $soil_type;
    private $crop_type;
    
    public function __construct($location, $soil_type, $crop_type) {
        $this->location = $location;
        $this->soil_type = $soil_type;
        $this->crop_type = $crop_type;
    }
    
    public function updateRealTimeData() {
        // Get weather data from OpenWeatherMap
        $weatherData = getCurrentWeather($this->location);
        
        if ($weatherData) {
            $temperature = $weatherData['main']['temp'];
            $humidity = $weatherData['main']['humidity'];
            
            // For soil moisture and NPK values, you would typically get these from soil sensors
            // For this example, we'll use placeholder values that you should replace with actual sensor data
            $moisture = 65.0;  // Replace with actual soil moisture sensor reading
            $nitrogen = 45.0;  // Replace with actual nitrogen sensor reading
            $phosphorus = 35.0;  // Replace with actual phosphorus sensor reading
            $potassium = 40.0;  // Replace with actual potassium sensor reading
            
            // Save the real-time reading to database
            $result = saveRealtimeReading(
                $temperature,
                $humidity,
                $moisture,
                $this->soil_type,
                $this->crop_type,
                $nitrogen,
                $phosphorus,
                $potassium,
                $this->location
            );
            
            return $result;
        }
        return false;
    }
    
    public function getLatestReading() {
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT * FROM real_time_readings WHERE location = ? ORDER BY reading_timestamp DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $this->location);
        $stmt->execute();
        $result = $stmt->get_result();
        $reading = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return $reading;
    }
}

// Example usage:
// $rtManager = new RealTimeDataManager("Mumbai", "Clay", "Wheat");
// $rtManager->updateRealTimeData();
// $latestReading = $rtManager->getLatestReading();
?>
