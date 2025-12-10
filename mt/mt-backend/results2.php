<?php
session_start();
/*
results2.php on admin-tilan toinen web-sivu eli Tulosten vertailu -sivu.
Sivulla esitetään rinnakkain tietyltä aikaväliltä suodatetut tulokset.  
*/
require('data/file.php');
if ($_SESSION['ktun'] === $ktun && $_SESSION['ssana'] === $ssana) { ?>

    <!DOCTYPE html>
    <html>

    <head>
        <link rel="stylesheet" href="style2.css">
    </head>

    <body>
        <p class="title">Tulosten vertailu</p>
        <p>
            <a href='results1.php'>Tulokset</a>
            <a href='results2.php'>Tulosten vertailu</a>
            <a href='#' onclick="window.print()">Tulosta</a>
            <a href='logout.php'>Kirjaudu ulos</a>
        </p>

        <p>Oletuksena näytetään 10 vanhimman ja 10 uusimman tuloksen vertailu.</p>
        <?php

        require('functions.php');

        $jsondata = file_get_contents('data/results.json');

        $json_taulukko = answersToJsonArray($jsondata);

        // Dekoodataan data PHP-taulukoksi
        $decodedData = json_decode((string)$json_taulukko, true);
        // Otetaan tulostaulukon avaimet talteen
        $keys = array_keys($decodedData[0]);

        // Vastausten suodatus ajan perusteella

        $filtered_data_left = [];
        $start_month_left = '';
        $end_month_left = '';

        $filtered_data_right = [];
        $start_month_right = '';
        $end_month_right = '';

        // Kerätään uniikit kuukaudet pudotusvalikoita varten
        $unique_months = getUniqueMonthsFromData($decodedData);

        // Yritetään ladata valinnat istunnosta, jos ne ovat siellä.

        if (isset($_SESSION['start_month_left']) && isset($_SESSION['end_month_left'])) {
            $start_month_left = $_SESSION['start_month_left'];
            $end_month_left = $_SESSION['end_month_left'];
            $filtered_data_left = filterDataByMonth($decodedData, $start_month_left, $end_month_left);
        }

        if (isset($_SESSION['start_month_right']) && isset($_SESSION['end_month_right'])) {
            $start_month_right = $_SESSION['start_month_right'];
            $end_month_right = $_SESSION['end_month_right'];
            $filtered_data_right = filterDataByMonth($decodedData, $start_month_right, $end_month_right);
        }

        // Jos valinnat eivät ole sessiossa näytetään 10 viimeistä ja ensimmäistä prosenttitaulukkoa
        if (empty($start_month_left) || empty($end_month_left) || empty($start_month_right) || empty($end_month_right)) {
            $filtered_data_left = array_slice($decodedData, 0, 10);
            $filtered_data_right = array_slice($decodedData, -10, 10);
        }

        if (isset($_POST['submit1']) || isset($_POST['submit2'])) {
            // Käsitellään lomakkeen lähetys
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Käsitellään vasemmanpuoleisen suodatuksen tiedot POST-datasta
                $start_month_left = $_POST['start_month_left'] ?? $start_month_left; // Käytä POST-arvoa, muuten edellistä (tai oletusta)
                $end_month_left = $_POST['end_month_left'] ?? $end_month_left;

                // Tallennetaan valinnat istuntoon tulevaa sivun latausta varten
                $_SESSION['start_month_left'] = $start_month_left;
                $_SESSION['end_month_left'] = $end_month_left;

                // Suodatetaan vain, jos molemmat arvot on annettu
                if (!empty($start_month_left) && !empty($end_month_left)) {
                    $filtered_data_left = filterDataByMonth($decodedData, $start_month_left, $end_month_left);
                } else {
                    $filtered_data_left = [];
                }

                // Käsitellään oikeanpuoleisen suodatuksen tiedot POST-datasta
                $start_month_right = $_POST['start_month_right'] ?? $start_month_right;
                $end_month_right = $_POST['end_month_right'] ?? $end_month_right;

                // Tallennetaan valinnat istuntoon
                $_SESSION['start_month_right'] = $start_month_right;
                $_SESSION['end_month_right'] = $end_month_right;

                // Suodatetaan vain, jos molemmat arvot on annettu
                if (!empty($start_month_right) && !empty($end_month_right)) {
                    $filtered_data_right = filterDataByMonth($decodedData, $start_month_right, $end_month_right);
                } else {
                    $filtered_data_right = [];
                }
            } else {
                /* Jos sivu ladataan ensimmäistä kertaa (ei POST-pyyntöä),
                   suoritetaan suodatus istunnosta ladatuilla arvoilla.
                   Tämä varmistaa, että tiedot näkyvät heti, jos istunnossa on valintoja.
                */
                if (!empty($start_month_left) && !empty($end_month_left)) {
                    $filtered_data_left = filterDataByMonth($decodedData, $start_month_left, $end_month_left);
                }
                if (!empty($start_month_right) && !empty($end_month_right)) {
                    $filtered_data_right = filterDataByMonth($decodedData, $start_month_right, $end_month_right);
                }
            }
        }
        ?>

        <!---- Suodatuslomake----------->

        <form method="post" action="">

            <table>
                <tr>
                    <td width="350px">

                        <label for="start_month_left">Alku:</label>
                        <select id="start_month_left" name="start_month_left" required>
                            <?php
                            foreach ($unique_months as $month) {
                                $selected = ($month == $start_month_left) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>

                        <label for="end_month_left">Loppu:</label>
                        <select id="end_month_left" name="end_month_left" required>
                            <?php
                            foreach ($unique_months as $month) {
                                $selected = ($month == $end_month_left) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>

                        <button class="gap" type="submit" name="submit1">Suodata</button>

                    </td>

                    <td width="350px">

                        <label for="start_month_right">Alku:</label>
                        <select id="start_month_right" name="start_month_right" required>
                            <?php
                            foreach ($unique_months as $month) {
                                $selected = ($month == $start_month_right) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>

                        <label for="end_month_right">Loppu:</label>
                        <select id="end_month_right" name="end_month_right" required>
                            <?php
                            foreach ($unique_months as $month) {
                                $selected = ($month == $end_month_right) ? 'selected' : '';
                                echo "<option value='{$month}' {$selected}>{$month}</option>";
                            }
                            ?>
                        </select>

                        <button class="gap" type="submit" name="submit2">Suodata</button>

                    </td>
                </tr>
            </table>

        </form>
        <br />

        <?php

        if (!empty($filtered_data_left) && !empty($filtered_data_right)) {

            echo '<div class="grid-container">';

            // kaksi viimeistä saraketta eivät tule prosenttitaulukoihin
            for ($i = 0; $i < (count($keys) - 2); $i++) {
                echo '<div class="grid-item">';
                echo generatePercentageTable($filtered_data_left, $keys[$i]);
                echo '</div>';
            }

            for ($i = 0; $i < (count($keys) - 2); $i++) {
                echo '<div class="grid-item">';
                echo generatePercentageTable($filtered_data_right, $keys[$i]);
                echo '</div>';
            }
            echo '</div>';

            // gridien välissä pieni väli
            echo '<br /><br />';
            echo '<div class="grid-container2">';

            // vapaat palautteet

            echo '<div class="grid-item2">';
            echo createFeedbackTable($filtered_data_left);
            echo '</div>';


            echo '<div class="grid-item2">';
            echo createFeedbackTable($filtered_data_right);
            echo '</div>';
            echo '</div>';
        } else {
            echo '<p>Suodatuksen alkupäivämäärä ei saa olla suurempi kuin loppupäivämäärä.</p>';
        }
        ?>
    </body>

    </html>
<?php } ?>