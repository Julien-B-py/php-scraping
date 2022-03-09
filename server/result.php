<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Centrale</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>

<body>


    <div class="container">

        <h1>Toutes les annonces</h1>

        <?php

        require __DIR__ . "/vendor/autoload.php";
        // Import Car class
        require_once './class/Car.php';

        // Create a Goutte Client instance
        use Goutte\Client;
        use Symfony\Component\HttpClient\HttpClient;

        // Add user agent to get receive correct information
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
        // Optional parameters
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


        echo '<h2>Total : ' . $crawler->filter(".numAnn")->text() . ' annonces</h2>';


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
        // Array containing all chief town in France
        $locations = getLocations();

        // Loop through cars array
        foreach ($cars as &$car) {

            // Determine which icon to display depending on how good the deal is
            $dealIcon;
            if (str_contains($car->goodDeal, 'marché')) {
                $dealIcon = '<i class="fa-solid fa-thumbs-down" style="color:#D40000;"></i>';
            } elseif (str_contains($car->goodDeal, 'indisponible')) {
                $dealIcon = "";
            } else {
                $dealIcon = '<i class="fa-solid fa-thumbs-up" style="color:#00aa0e;"></i>';
            }

            // Convert departement number to integer and remove 1 to use the value as index
            $loc = intval($car->location) - 1;
            // Get the corresponding chief town from locations array
            $city = $locations[$loc];

            echo "        
            <div class='car'>
            <img src='./car-img.jpg'>

            <div class='right-part'>
                <div>       
                    <h2>$car->model</h2>
                    <h3>$car->version</h3>
                    <div>$car->price</div>
                    <div>$dealIcon $car->goodDeal</div>
                    <div><i class='fa-solid fa-location-dot'></i> $car->location - $city</div>
                    <div>$car->year</div>   
                    <div>$car->km</div>             
                </div>
                <a href='$car->url'><button>Voir l'annonce</button></a>
            </div>

            </div>";
        }

        ?>

    </div>



</body>

</html>