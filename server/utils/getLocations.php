<?php

// Return an array containing all French locations
function getLocations()
{
    // Open file as array
    $array = file("./assets/departments.txt");
    // Create new empty array
    $final = array();

    // Loop through all values from original array
    foreach ($array as &$value) {
        // Remove all numbers from every single string to keep names only
        $value = preg_replace('/[0-9]+/', '', $value);
        // Push cleaned value in final array
        array_push($final, $value);
    }
    return $final;
}
