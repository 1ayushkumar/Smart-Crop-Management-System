<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/weather_config.php';
include __DIR__ . '/header.php';
include __DIR__ . '/nav.php';

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Function to get soil characteristics based on soil type
function getSoilCharacteristics($soilType) {
    $soilData = [
        'Black Soil' => ['nitrogen' => 'high', 'phosphorus' => 'medium', 'potassium' => 'high', 'ph' => '6.5-7.5'],
        'Red Soil' => ['nitrogen' => 'low', 'phosphorus' => 'medium', 'potassium' => 'low', 'ph' => '6.0-7.0'],
        'Alluvial Soil' => ['nitrogen' => 'medium', 'phosphorus' => 'medium', 'potassium' => 'medium', 'ph' => '6.5-7.5'],
        'Laterite Soil' => ['nitrogen' => 'low', 'phosphorus' => 'low', 'potassium' => 'low', 'ph' => '5.5-6.5'],
        'Sandy Soil' => ['nitrogen' => 'low', 'phosphorus' => 'low', 'potassium' => 'low', 'ph' => '6.0-7.0']
    ];
    return isset($soilData[$soilType]) ? $soilData[$soilType] : null;
}

// Function to suggest crops based on conditions
function suggestCrops($temperature, $humidity, $soilType) {
    $crops = [];
    $soilCharacteristics = getSoilCharacteristics($soilType);
    
    // Updated crop requirements with wider ranges and more crops
    $cropRequirements = [
        'Rice' => ['temp' => [15, 35], 'humidity' => [60, 95], 'soil' => ['Alluvial Soil', 'Black Soil', 'Red Soil']],
        'Wheat' => ['temp' => [10, 25], 'humidity' => [40, 85], 'soil' => ['Alluvial Soil', 'Black Soil', 'Red Soil']],
        'Cotton' => ['temp' => [15, 35], 'humidity' => [45, 85], 'soil' => ['Black Soil', 'Red Soil']],
        'Sugarcane' => ['temp' => [15, 35], 'humidity' => [55, 95], 'soil' => ['Alluvial Soil', 'Black Soil', 'Red Soil']],
        'Maize' => ['temp' => [15, 35], 'humidity' => [35, 85], 'soil' => ['Alluvial Soil', 'Black Soil', 'Red Soil']],
        'Pulses' => ['temp' => [15, 30], 'humidity' => [35, 85], 'soil' => ['Black Soil', 'Red Soil']],
        'Groundnut' => ['temp' => [15, 35], 'humidity' => [45, 85], 'soil' => ['Sandy Soil', 'Red Soil', 'Black Soil']],
        'Sunflower' => ['temp' => [15, 35], 'humidity' => [35, 85], 'soil' => ['Black Soil', 'Alluvial Soil', 'Red Soil']],
        'Vegetables' => ['temp' => [15, 30], 'humidity' => [50, 90], 'soil' => ['Red Soil', 'Black Soil', 'Alluvial Soil']],
        'Tea' => ['temp' => [15, 30], 'humidity' => [70, 90], 'soil' => ['Red Soil', 'Laterite Soil']],
        'Coffee' => ['temp' => [15, 28], 'humidity' => [70, 90], 'soil' => ['Red Soil', 'Laterite Soil']],
        'Millets' => ['temp' => [15, 35], 'humidity' => [35, 85], 'soil' => ['Red Soil', 'Black Soil', 'Sandy Soil']]
    ];

    foreach ($cropRequirements as $crop => $requirements) {
        if ($temperature >= $requirements['temp'][0] && 
            $temperature <= $requirements['temp'][1] && 
            $humidity >= $requirements['humidity'][0] && 
            $humidity <= $requirements['humidity'][1] && 
            in_array($soilType, $requirements['soil'])) {
            $crops[] = $crop;
        }
    }
    
    return $crops;
}

$message = '';
$suggestions = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $soilType = $_POST['soil_type'] ?? '';
    
    if ($city && $state && $soilType) {
        try {
            // Get real-time weather data using only city name
            $weatherData = getCurrentWeather($city);
            
            if ($weatherData && isset($weatherData['main'])) {
                $temperature = $weatherData['main']['temp'];
                $humidity = $weatherData['main']['humidity'];
                
                // Get crop suggestions
                $suggestions = suggestCrops($temperature, $humidity, $soilType);
                
                // Store the reading in database
                $database = new Database();
                $db = $database->getConnection();
                $query = "INSERT INTO real_time_readings (temperature, humidity, soil_type, location) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $location = "$city, $state";
                $stmt->execute([$temperature, $humidity, $soilType, $location]);
                
                if (empty($suggestions)) {
                    $message = "Current conditions - Temperature: {$temperature}°C, Humidity: {$humidity}%, Soil: {$soilType}. No crops match these exact conditions. Try a different soil type or check again later.";
                }
            } else {
                $message = "Could not fetch weather data for '$city'. Please try Mumbai, Delhi, Bengaluru, Chennai, or Kolkata.";
            }
        } catch (Exception $e) {
            $message = "An error occurred while fetching weather data. Please try again.";
        }
    } else {
        $message = "Please fill in all the required fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart Crop Suggestion</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Smart Crop Suggestion System</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="" id="cropForm">
                    <div class="form-group mb-3">
                        <label for="state">State:</label>
                        <select class="form-control" id="state" name="state" required>
                            <option value="">Select State</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Punjab">Punjab</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="city">City:</label>
                        <select class="form-control" id="city" name="city" required>
                            <option value="">Select City</option>
                            <optgroup label="Maharashtra">
                                <option value="Mumbai">Mumbai</option>
                                <option value="Pune">Pune</option>
                                <option value="Nagpur">Nagpur</option>
                            </optgroup>
                            <optgroup label="Karnataka">
                                <option value="Bengaluru">Bengaluru</option>
                                <option value="Mysore">Mysore</option>
                                <option value="Hubli">Hubli</option>
                            </optgroup>
                            <optgroup label="Tamil Nadu">
                                <option value="Chennai">Chennai</option>
                                <option value="Coimbatore">Coimbatore</option>
                                <option value="Madurai">Madurai</option>
                            </optgroup>
                            <optgroup label="Gujarat">
                                <option value="Ahmedabad">Ahmedabad</option>
                                <option value="Surat">Surat</option>
                                <option value="Vadodara">Vadodara</option>
                            </optgroup>
                            <optgroup label="Punjab">
                                <option value="Chandigarh">Chandigarh</option>
                                <option value="Amritsar">Amritsar</option>
                                <option value="Ludhiana">Ludhiana</option>
                            </optgroup>
                            <optgroup label="West Bengal">
                                <option value="Kolkata">Kolkata</option>
                                <option value="Howrah">Howrah</option>
                                <option value="Durgapur">Durgapur</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="soil_type">Soil Type:</label>
                        <select class="form-control" id="soil_type" name="soil_type" required>
                            <option value="">Select Soil Type</option>
                            <option value="Black Soil">Black Soil</option>
                            <option value="Red Soil">Red Soil</option>
                            <option value="Alluvial Soil">Alluvial Soil</option>
                            <option value="Laterite Soil">Laterite Soil</option>
                            <option value="Sandy Soil">Sandy Soil</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Get Suggestions</button>
                </form>

                <script>
                document.getElementById('state').addEventListener('change', function() {
                    const citySelect = document.getElementById('city');
                    const state = this.value;
                    
                    // Hide all optgroups first
                    Array.from(citySelect.getElementsByTagName('optgroup')).forEach(group => {
                        group.style.display = 'none';
                    });
                    
                    // Show cities only for selected state
                    if (state) {
                        const stateGroup = citySelect.querySelector('optgroup[label="' + state + '"]');
                        if (stateGroup) {
                            stateGroup.style.display = '';
                            citySelect.value = ''; // Reset city selection
                        }
                    }
                });
                </script>

                <?php if ($message): ?>
                    <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if (!empty($suggestions)): ?>
                    <div class="mt-4">
                        <h3>Weather Conditions for <?php echo htmlspecialchars("$city, $state"); ?>:</h3>
                        <p>Temperature: <?php echo isset($temperature) ? number_format($temperature, 1) : '0'; ?>°C</p>
                        <p>Humidity: <?php echo isset($humidity) ? number_format($humidity, 1) : '0'; ?>%</p>
                        <p>Soil Type: <?php echo htmlspecialchars($soilType); ?></p>
                        
                        <h3>Recommended Crops:</h3>
                        <div class="list-group">
                            <?php foreach ($suggestions as $crop): ?>
                                <div class="list-group-item">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($crop); ?></h5>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
