<?php
require_once 'config.php';

header('Content-Type: application/json');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get the city name from POST request
$city_name = isset($_POST['city_name']) ? $conn->real_escape_string($_POST['city_name']) : '';

if (empty($city_name)) {
    echo json_encode(['error' => 'City name is required']);
    exit;
}

/**
 * Fetch weather data from RapidAPI
 */
function fetchWeatherFromAPI($city) {
    if (USE_MOCK_DATA) {
        // Return mock data if API key is not configured
        return [
            'temperature' => rand(15, 35),
            'CONDITIONs' => ['Sunny', 'Cloudy', 'Rainy', 'Partly Cloudy'][rand(0, 3)],
            'rain_chance' => rand(0, 100),
            'wind_speed' => rand(0, 20),
            'humidity' => rand(20, 100),
            'visibility' => rand(5, 15),
            'pressure' => rand(950, 1050)
        ];
    }
    
    // Real API call to RapidAPI WeatherAPI
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://" . RAPIDAPI_HOST . "/current.json?q=" . urlencode($city),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: " . RAPIDAPI_HOST,
            "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);
    
    if ($err) {
        return ['error' => 'API request failed: ' . $err];
    }
    
    if ($httpCode !== 200) {
        return ['error' => 'Weather data not found for this city'];
    }
    
    $data = json_decode($response, true);
    
    if (!$data || !isset($data['current'])) {
        return ['error' => 'Invalid API response'];
    }
    
    // Extract and format weather data
    return [
        'temperature' => round($data['current']['temp_c'], 1),
        'CONDITIONs' => $data['current']['condition']['text'],
        'rain_chance' => isset($data['current']['precip_mm']) ? round($data['current']['precip_mm'] * 10) : 0,
        'wind_speed' => round($data['current']['wind_kph'], 1),
        'humidity' => $data['current']['humidity'],
        'visibility' => round($data['current']['vis_km'], 1),
        'pressure' => round($data['current']['pressure_mb'], 1)
    ];
}

// Fetch weather data from API
$weather_data = fetchWeatherFromAPI($city_name);

// Check if there was an error fetching data
if (isset($weather_data['error'])) {
    echo json_encode(['error' => $weather_data['error']]);
    exit;
}

// Check if similar recent data exists in the database (within last hour)
$stmt = $conn->prepare("SELECT * FROM weather_searches WHERE city_name = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $city_name);
$stmt->execute();
$result = $stmt->get_result();
$existing_data = $result->fetch_assoc();

// If data exists and is less than 1 hour old, return cached data
if ($existing_data && (time() - strtotime($existing_data['created_at'])) < 3600) {
    echo json_encode([
        'city_name' => $existing_data['city_name'],
        'temperature' => $existing_data['temperature'],
        'CONDITIONs' => $existing_data['CONDITIONs'],
        'rain_chance' => $existing_data['rain_chance'],
        'wind_speed' => $existing_data['wind_speed'],
        'humidity' => $existing_data['humidity'],
        'visibility' => $existing_data['visibility'],
        'pressure' => $existing_data['pressure'],
        'cached' => true
    ]);
} else {
    // Insert new data into the database
    $stmt = $conn->prepare("INSERT INTO weather_searches (city_name, temperature, CONDITIONs, rain_chance, wind_speed, humidity, visibility, pressure) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sdsdiddd",
        $city_name,
        $weather_data['temperature'],
        $weather_data['CONDITIONs'],
        $weather_data['rain_chance'],
        $weather_data['wind_speed'],
        $weather_data['humidity'],
        $weather_data['visibility'],
        $weather_data['pressure']
    );
    
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to save weather data: ' . $stmt->error]);
        exit;
    }
    
    // Return the newly fetched data
    echo json_encode([
        'city_name' => $city_name,
        'temperature' => $weather_data['temperature'],
        'CONDITIONs' => $weather_data['CONDITIONs'],
        'rain_chance' => $weather_data['rain_chance'],
        'wind_speed' => $weather_data['wind_speed'],
        'humidity' => $weather_data['humidity'],
        'visibility' => $weather_data['visibility'],
        'pressure' => $weather_data['pressure'],
        'cached' => false
    ]);
}

$stmt->close();
$conn->close();
?>
