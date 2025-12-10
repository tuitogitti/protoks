<?php
session_start();
/*
Results1.php on admin-tilan ensimmäinen sivu, eli Tulokset-sivu. 
Sivulla esitetään kaikki tulokset, joita voi suodattaa ajan perusteella.  
*/
// Data-kansion tulee olla www-kansion ulkopuolella, esim. /home/username/data/file.php
require('data/file.php');
if ($_SESSION['ktun'] === $ktun && $_SESSION['ssana'] === $ssana) { ?>

    <!DOCTYPE html>
    <html>

    <head>
        <link rel="stylesheet" href="style1.css">
    </head>

    <body>

        <p class="title">Tulokset</p>

        <p> <a href='results1.php'>Tulokset</a>
            <a href='results2.php'>Tulosten vertailu</a>
            <a href='#' onclick="window.print()">Tulosta</a>
            <a href='logout.php'>Kirjaudu ulos</a>
        </p>

        <p>Oletuksena näytetään 10 uusinta tulosta.</p>

        <?php

        require('functions.php');

        $jsondata = file_get_contents('data/results.json');
        // Tehdään datasta taulukkomuotoista validia JSON:ia
        $json_taulukko = answersToJsonArray($jsondata);

        // Dekoodataan data PHP-taulukoksi
        $decodedData = json_decode((string)$json_taulukko, true);

        // print_r($decodedData);

        // Vastausten suodatus ajan perusteella
        $filtered_data = [];
        $start_month = '';
        $end_month = '';

        // Kerätään uniikit kuukaudet pudotusvalikoita varten
        $unique_months = getUniqueMonthsFromData($decodedData);

        // Yritetään ladata valinnat istunnosta, jos ne ovat siellä.
        if (isset($_SESSION['start_month']) && isset($_SESSION['end_month'])) {
            $start_month = $_SESSION['start_month'];
            $end_month = $_SESSION['end_month'];
            $filtered_data = filterDataByMonth($decodedData, $start_month, $end_month);
        }

        // Oletuksena näytetään 10 viimeistä tulosta
        if (empty($start_month) && empty($end_month)) {
            $filtered_data = array_slice($decodedData, -10, 10);
        }

        if (isset($_POST['submit'])) {
            // Käsitellään lomakkeen lähetys
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $start_month = $_POST['start_month'] ?? '';
                $end_month = $_POST['end_month'] ?? '';

                // Tallennetaan valinnat istuntoon tulevaa sivun latausta varten
                $_SESSION['start_month'] = $start_month;
                $_SESSION['end_month'] = $end_month;

                if (!empty($start_month) && !empty($end_month)) {
                    $filtered_data = filterDataByMonth($decodedData, $start_month, $end_month);
                } else {
                    $filtered_data = [];
                }
            } else {
                // Jos sivu ladataan ensimmäistä kertaa (ei POST-pyyntöä),
                // suoritetaan suodatus istunnosta ladatuilla arvoilla jos niitä on.
                if (!empty($start_month) && !empty($end_month)) {
                    $filtered_data = filterDataByMonth($decodedData, $start_month, $end_month);
                }
            }
        }

        ?>

        <form method="post" action="">

            <label for="start_month">Alku:</label>
            <select id="start_month" name="start_month">
                <?php
                foreach ($unique_months as $month) {
                    $selected = ($month == $start_month) ? 'selected' : '';
                    echo "<option value='{$month}' {$selected}>{$month}</option>";
                }
                ?>
            </select>

            <label for="end_month">Loppu:</label>
            <select id="end_month" name="end_month">
                <?php
                foreach ($unique_months as $month) {
                    $selected = ($month == $end_month) ? 'selected' : '';
                    echo "<option value='{$month}' {$selected}>{$month}</option>";
                }
                ?>
            </select>

            <input class="gap" type="submit" name="submit" value="Suodata">

        </form>
        <br />

        <?php

        if (!empty($filtered_data)) {
            printResults($filtered_data);
        } else {
            echo '<p>Suodatuksen alkupäivämäärä ei saa olla suurempi kuin loppupäivämäärä.</p>';
        }

        ?>

    </body>

    </html>
<?php } ?>