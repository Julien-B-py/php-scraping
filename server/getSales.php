<?php

// CORS
header("Access-Control-Allow-Origin: *");

// Import LaCentrale class
require_once './class/LaCentrale.php';

// Handle post request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect user form inputs
    $requestedBrand = $_POST['brand'];
    $requestedMinPrice = $_POST['minPrice'];
    $requestedMaxPrice = $_POST['maxPrice'];
    $requestedEnergy = $_POST['energy'];
    $requestedGearbox = $_POST["gearbox"];

    // Create a new LaCentrale object
    $laCentrale = new LaCentrale();
    // Send user query to LaCentrale
    $laCentrale->setUserQuery($requestedBrand, $requestedMinPrice, $requestedMaxPrice, $requestedEnergy, $requestedGearbox);
    // Collect search results
    $result = $laCentrale->search();
    // Send results in the response
    echo $result;
}
