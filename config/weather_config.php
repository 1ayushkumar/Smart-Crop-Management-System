<?php
// OpenWeatherMap API configuration
define('OPENWEATHER_API_KEY', '96939fce70946c2eddb25fddd4f74f10'); // Your API key
define('WEATHER_API_ENDPOINT', 'http://api.openweathermap.org/data/2.5/weather');

// City name mapping for common Indian cities
$CITY_MAPPING = [
    'Bangalore' => 'Bengaluru',
    'Mumbai' => 'Mumbai',
    'Delhi' => 'New Delhi',
    'Calcutta' => 'Kolkata'
];

// Function to get current weather data
function getCurrentWeather($city) {
    global $CITY_MAPPING;
    
    // Use mapped city name if available
    $cityName = isset($CITY_MAPPING[$city]) ? $CITY_MAPPING[$city] : $city;
    
    // Add country code for better accuracy
    $cityName .= ',IN';
    
    $url = WEATHER_API_ENDPOINT . "?q=" . urlencode($cityName) . "&units=metric&appid=" . OPENWEATHER_API_KEY;
    
    // Add error handling for the API call
    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true,
            'timeout' => 10
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        error_log("OpenWeatherMap API Error for city: $cityName");
        return null;
    }
    
    $data = json_decode($response, true);
    
    // Check if the API returned an error
    if (isset($data['cod']) && $data['cod'] !== 200) {
        error_log("OpenWeatherMap API Error: " . ($data['message'] ?? 'Unknown error'));
        return null;
    }
    
    return $data;
}
?>
