<!DOCTYPE html>
<html lang="en">

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

        $client = new Client();
        // Perform GET request to specified url
        $crawler = $client->request('GET', 'https://www.lacentrale.fr/listing');




        $cars = array();




        // Extract data:

        $crawler->filter('.searchCard__link')->each(function ($node) {


            $model = $node->filter('.searchCard__makeModel')->text();
            $version = $node->filter('.searchCard__version')->text();
            $price = $node->filter('.searchCard__fieldPrice')->text();
            $goodDeal = $node->filter('.goodDeal-label')->text();
            $location = $node->filter('.searchCard__dptCont')->text();
            $km = $node->filter('.searchCard__mileage')->text();
            $year = $node->filter('.searchCard__year')->text();
            $url = "https://www.lacentrale.fr{$node->attr('href')}";

            $car = new Car($model, $version, $price, $goodDeal, $location, $km, $year, $url);

            array_push($GLOBALS['cars'], $car);
        });




        include './getLocations.php';
        $locations = getLocations();



        foreach ($cars as &$car) {

            $dealIcon;
            if (str_contains($car->goodDeal, 'marché')) {
                $dealIcon = '<i class="fa-solid fa-thumbs-down" style="color:#D40000;"></i>';
            } elseif (str_contains($car->goodDeal, 'indisponible')) {
                $dealIcon = "";
            } else {
                $dealIcon = '<i class="fa-solid fa-thumbs-up" style="color:#00aa0e;"></i>';
            }

            $loc = intval($car->location) - 1;
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