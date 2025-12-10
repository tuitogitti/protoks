<?php
// Funktiokirjasto
/**
 * answersToJsonArray
 * Muuntaa yksittäiset JSON-muotoiset vastaukset JSON-taulukoksi.
 *
 * @param $jsondata.
 * @return array $json_taulukko.
 */

function answersToJsonArray($jsondata)
{
    // Poistetaan ylimääräiset välilyönnit
    $merkkijono = trim($jsondata);

    // Etsitään kaikki JSON-objektit merkkijonosta
    preg_match_all('/\{(.*?)\}/', $merkkijono, $osat);

    // Luodaan tyhjä taulukko tuloksia varten
    $taulukko = [];

    // Käydään läpi löydetyt JSON-objektien merkkijonot
    if (isset($osat[0])) {
        foreach ($osat[0] as $objekti_merkkijono) {
            // Yritetään purkaa JSON
            $objekti = json_decode($objekti_merkkijono);

            // Jos purkaminen onnistui, lisätään objekti taulukkoon
            if ($objekti !== null) {
                $taulukko[] = $objekti;
            } else {
                // Jos JSON-purku epäonnistui, voidaan käsitellä virhettä tarvittaessa
                // Esimerkiksi kirjata virhe tai jättää viallinen objekti pois
                error_log("Virheellinen JSON-objekti: " . $objekti_merkkijono);
            }
        }
    }

    // Muunnetaan PHP-taulukko takaisin JSON-muotoiseksi merkkijonoksi
    $json_taulukko = json_encode($taulukko);

    return $json_taulukko;
}

/**
 * getUniqueMonthsFromData
 * Poimii kaikki uniikit kuukaudet ja vuodet annetusta JSON-data -taulukosta.
 *
 * @param array $data_array Taulukko JSON-merkkijonoja.
 * @return array Uniikkien kuukausien ja vuosien taulukko (muodossa 'YYYY-MM').
 */
function getUniqueMonthsFromData(array $data_array): array
{
    $all_months = [];
    foreach ($data_array as $data) {
        //$data = json_decode($json_string, true);
        if ($data && !empty($data)) {
            // Hae viimeisen avaimen arvo
            $keys = array_keys($data);
            $last_key = end($keys);
            if (isset($data[$last_key])) {
                $time_value = $data[$last_key];
                $item_date = DateTime::createFromFormat('d-m-Y H:i:s', $time_value);
                if ($item_date) {
                    $all_months[] = $item_date->format('Y-m'); // Format: YYYY-MM
                }
            }
        }
    }
    $unique_months = array_unique($all_months);
    sort($unique_months); // Järjestä kuukaudet nousevasti
    return $unique_months;
}

/**
 * filterDataByMonth
 * Suodattaa JSON-olioita ajan perusteella kuukauden tarkkuudella.
 *
 * @param array $data_array Taulukko JSON-merkkijonoja.
 * @param string $start_month Alkuvuosi-kuukausi-merkkijono (muodossa 'YYYY-MM').
 * @param string $end_month Loppuvuosi-kuukausi-merkkijono (muodossa 'YYYY-MM').
 * @return array Suodatettujen PHP-taulukoiden taulukko.
 */
function filterDataByMonth(array $data_array, string $start_month, string $end_month): array
{
    $filtered_data = [];
    try {
        // Muunna kuukaudet DateTime-objekteiksi vertailua varten
        // Aloituskuukausi: kuukauden ensimmäinen päivä klo 00:00:00
        $start_date = new DateTime($start_month . '-01');
        // Loppukuukausi: kuukauden viimeinen päivä klo 23:59:59
        $end_date = new DateTime($end_month . '-01');
        $end_date->modify('last day of this month')->setTime(23, 59, 59);

        foreach ($data_array as $data) {
            //$data = json_decode($json_string, true);

            // Hae viimeisen avaimen arvo (ajan)
            $keys = array_keys($data);
            $last_key = end($keys);
            $time_value = $data[$last_key];

            // Muunna ajan arvo DateTime-objektiksi
            $item_date = DateTime::createFromFormat('d-m-Y H:i:s', $time_value);

            // Tarkista, onko ajan arvo kelvollinen päivämäärä ja onko se suodatusalueella
            if ($item_date && $item_date >= $start_date && $item_date <= $end_date) {
                $filtered_data[] = $data;
            }
        }
    } catch (Exception $e) {
        // Käsittele virheet, esim. virheellinen päivämäärämuoto
        error_log("Virhe päivämäärän käsittelyssä: " . $e->getMessage());
        return []; // Palauta tyhjä taulukko virheen sattuessa
    }
    if ($start_date < $end_date) {
        return $filtered_data;
    } else {
        return [];
    }
}

/**
 * generatePercentageTable
 * Luo HTML-taulukoita, joihin on laskettu prosenttiosuudet
 *
 * @param array $json_objects Taulukko JSON-merkkijonoja.
 * @return $html.
 */
function generatePercentageTable(array $json_objects, string $target_key = ''): string
{
    $extracted_values = [];

    foreach ($json_objects as $data) {

        if (!empty($data)) {

            if ($target_key !== '' && array_key_exists($target_key, $data)) {
                $extracted_values[] = $data[$target_key];
            } elseif ($target_key === '') {
                $first_key = array_key_first($data);
                $extracted_values[] = $data[$first_key];
            }
        }
    }

    $value_counts = array_count_values($extracted_values);
    $total_entries = count($extracted_values);

    // Lasketaan prosentit ja tallennetaan ne uuteen taulukkoon, jotta ne voidaan järjestää
    $percentages_data = [];
    foreach ($value_counts as $value => $count) {
        $percentage = ($count / $total_entries) * 100;
        $percentages_data[] = [
            'value' => $value,
            'percentage' => $percentage
        ];
    }

    // Järjestetään taulukko prosenttiosuuden mukaan laskevaan järjestykseen
    usort($percentages_data, function ($a, $b) {
        return $b['percentage'] <=> $a['percentage'];
    });

    $html = '<table border="0" width="100%" style="page-break-inside: avoid">';
    $html .= '<thead>';
    $html .= '<tr><th>' . ($target_key !== '' ? htmlspecialchars($target_key) : 'Arvo') . '</th><th>%-osuudet</th></tr>'; // Päivitetty otsikko, jos target_key on tyhjä
    $html .= '</thead>';
    $html .= '<tbody>';

    foreach ($percentages_data as $item) {
        $html .= '<tr>';
        $html .= '<td width="70%">' . htmlspecialchars($item['value']) . '</td>';
        $html .= '<td width="30%">' . number_format($item['percentage'], 2) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<br />';

    return $html;
}

// Luo tulostaulukon
function printResults(array $result_array)
{

    if ($result_array) {
        echo '<table border="1">';
        echo '<tr>';
        foreach (array_keys($result_array[0]) as $key) {
            echo '<th>' . htmlspecialchars($key) . '</th>';
        }
        echo '</tr>';

        foreach ($result_array as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }

        echo '</table>';
        echo '<br />';
    }
}

// Luo taulukon johon tulee vapaa palaute
function createFeedbackTable(array $data): string
{
    $html = '';

    $html .= '<table border="0" width="100%" style="page-break-inside: avoid">';
    $html .= '<thead>';
    $html .= '<tr><th colspan="2">Vapaa palaute</th></tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    $row_count = 0;
    foreach ($data as $item) {
        if (isset($item['Vapaa palaute'])) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($item['Vapaa palaute']) . '</td>';
            $html .= '</tr>';
            $row_count++;
        }
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}
