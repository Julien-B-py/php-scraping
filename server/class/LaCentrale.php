<?php

require "./vendor/autoload.php";
// Import Car class
require_once './class/Car.php';
// Import getLocations to display chief town close to département number
include './utils/getLocations.php';

// Create a Goutte Client instance
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class LaCentrale
{
    private $baseUrl;
    private $carsList;
    private $client;
    private $crawler;
    private $fullUrl;
    private $nbAnnonces;

    public function __construct()
    {
        // Add user agent to receive correct information from LaCentrale
        // https://github.com/FriendsOfPHP/Goutte/issues/401#issuecomment-591760247
        $this->client = new Client(HttpClient::create(array(
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
        $this->client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');

        // Set results base url
        $this->baseUrl = "https://www.lacentrale.fr/listing";
        // Init empty array to store all Cars
        $this->carsList = array();
    }

    public function setUserQuery($requestedBrand, $requestedMinPrice, $requestedMaxPrice, $requestedEnergy, $requestedGearbox)
    {

        $this->fullUrl = "{$this->baseUrl}?makesModelsCommercialNames={$requestedBrand}&priceMin={$requestedMinPrice}&priceMax={$requestedMaxPrice}&energies={$requestedEnergy}&gearbox={$requestedGearbox}";
    }

    public function search()
    {
        // Perform GET request to specified url
        $this->crawler = $this->client->request('GET', $this->fullUrl);

        // Extract data:
        // Target every single card item
        $this->crawler->filter('.searchCard__link')->each(function ($node) {
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
            array_push($this->carsList, $car);
        });

        // Collect listings qty
        $this->nbAnnonces = $this->crawler->filter(".numAnn")->text();
        // Clear string
        $this->nbAnnonces = preg_replace("/[^0-9]/", "", $this->nbAnnonces);
        // Turn string into an int
        $this->nbAnnonces = intval($this->nbAnnonces);

        // Array containing all chief towns in France
        $locations = getLocations();

        // Loop through cars array
        foreach ($this->carsList as &$car) {

            // Convert departement number to integer and remove 1 to use the value as index
            $loc = intval($car->location) - 1;

            // Get the corresponding chief town from locations array and add it to Car object
            // Remove all \t \r \n
            $city = trim(preg_replace('/\s\s+/', ' ', $locations[$loc]));
            $car->city = $city;
        }

        // Return collected data as JSON
        return json_encode(["nbAnnonces" => $this->nbAnnonces, "cars" => $this->carsList]);
    }
}
