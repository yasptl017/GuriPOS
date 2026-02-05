<?php

// Sample data representing nearby restaurants
$restaurants = [
    ["name" => "Restaurant 1", "latitude" => 23.59312822417694, "longitude" => 72.37604216659656],
    ["name" => "Restaurant 2", "latitude" => 40.7211, "longitude" => -74.0042],
    ["name" => "Restaurant 3", "latitude" => 40.7306, "longitude" => -74.0027],
    // Add more restaurants as needed
];

// Function to calculate distance between two points (latitude and longitude)
function distance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return $miles;
}

// Get latitude and longitude parameters from the request
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 0;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 0;

// Filter nearby restaurants based on a certain radius (for demo purposes, let's say 10 miles)
$nearby_restaurants = array_filter($restaurants, function($restaurant) use ($lat, $lng) {
    return distance($lat, $lng, $restaurant['latitude'], $restaurant['longitude']) <= 10;
});

// Return nearby restaurants in JSON format
header('Content-Type: application/json');
echo json_encode($nearby_restaurants);
?>