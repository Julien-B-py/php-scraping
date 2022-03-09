<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>

<body>

    <?php $brands = file("./brands.txt"); ?>

    <form action="result.php" method="post">

        <h2>Rechercher un véhicule d'occasion</h2>

        <div>
            <img src="./cars.jpg" alt="">

            <div class="inputs">

                <div>

                    <select name="brand" id="brand">

                        <option value="">-- Marque --</option>
                        <?php
                        foreach ($brands as &$brand) {
                            echo '<option value="' . substr_replace($brand, "", -2) . '">' . substr_replace($brand, "", -2) . '</option>';
                        }
                        ?>

                    </select>

                    <select name="energy" id="energy">

                        <option value="">-- Energie --</option>
                        <option value="dies">Diesel</option>
                        <option value="ess">Essence</option>
                        <option value="elec">Electrique</option>
                        <option value="hyb">Hybride</option>

                    </select>

                    <input name="minPrice" type="number" placeholder="Prix min">
                    <input name="maxPrice" type="number" placeholder="Prix max">

                    <select name="gearbox" id="gearbox">

                        <option value="">-- Boîte de vitesse --</option>
                        <option value="AUTO">Automatique</option>
                        <option value="MANUAL">Mécanique</option>

                    </select>

                </div>

                <button type="submit">Rechercher</button>
            </div>
        </div>

    </form>
</body>

</html>