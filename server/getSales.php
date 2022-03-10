<?php

// CORS
header("Access-Control-Allow-Origin: *");

require __DIR__ . "/vendor/autoload.php";
// Import Car class
require_once './class/Car.php';

// Create a Goutte Client instance
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

// Handle post request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Add user agent to receive correct information from LaCentrale
    // https://github.com/FriendsOfPHP/Goutte/issues/401#issuecomment-591760247
    $client = new Client(HttpClient::create(array(
        'headers' => array(
            'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Referer' => 'http://yourtarget.url/',
            'Upgrade-Insecure-Requests' => '1',
            'Save-Data' => 'on',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
        ),
    )));
    $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');

    $baseURL = "https://www.lacentrale.fr/listing";
    // Optional user parameters
    $requestedBrand = $_POST['brand'];
    $requestedMinPrice = $_POST['minPrice'];
    $requestedMaxPrice = $_POST['maxPrice'];
    $requestedEnergy = $_POST['energy'];
    $requestedGearbox = $_POST["gearbox"];

    $fullURL = "$baseURL?makesModelsCommercialNames=$requestedBrand&priceMin=$requestedMinPrice&priceMax=$requestedMaxPrice&energies=$requestedEnergy&gearbox=$requestedGearbox";

    // Perform GET request to specified url
    $crawler = $client->request('GET', $fullURL);

    // Init empty array to store all Cars
    $cars = array();

    // Collect listings qty
    $nbAnnonces = $crawler->filter(".numAnn")->text();
    $nbAnnonces = preg_replace("/[^0-9]/", "", $nbAnnonces);
    $nbAnnonces = intval($nbAnnonces);

    // Extract data:
    // Target every single card item
    $crawler->filter('.searchCard__link')->each(function ($node) {
        // For each card collect model, version, price, location etc...
        $model = $node->filter('.searchCard__makeModel')->text();
        $version = $node->filter('.searchCard__version')->text();
        $price = $node->filter('.searchCard__fieldPrice')->text();
        $goodDeal = $node->filter('.goodDeal-label')->text();
        $location = $node->filter('.searchCard__dptCont')->text();
        $km = $node->filter('.searchCard__mileage')->text();
        $year = $node->filter('.searchCard__year')->text();
        // Collect ad direct link
        $url = "https://www.lacentrale.fr{$node->attr('href')}";
        // Create new Car object to store everything inside it
        $car = new Car($model, $version, $price, $goodDeal, $location, $km, $year, $url);
        // Add created Car object to cars array
        array_push($GLOBALS['cars'], $car);
    });

    // Import getLocations to display chief town close to département number
    include './utils/getLocations.php';
    // Array containing all chief towns in France
    $locations = getLocations();

    // Loop through cars array
    foreach ($cars as &$car) {

        // Convert departement number to integer and remove 1 to use the value as index
        $loc = intval($car->location) - 1;

        // Get the corresponding chief town from locations array and add it to Car object
        // Remove all \t \r \n
        $city = trim(preg_replace('/\s\s+/', ' ', $locations[$loc]));
        $car->city = $city;
    }

    // Send collected data inside the response
    echo json_encode(["nbAnnonces" => $nbAnnonces, "cars" => $cars]);
}
