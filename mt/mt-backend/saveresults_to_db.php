<?php

/**
 * Tallentaa JSON-datan 'results'-taulun 'results'-kenttään.
 * @param string $palvelin Tietokantapalvelimen osoite.
 * @param string $kayttaja Tietokannan käyttäjänimi.
 * @param string $salasana Tietokannan salasana.
 * @param string $tietokanta Tietokannan nimi.
 * @param string $json_string JSON-muotoinen merkkijono.
 * @return boolean
 */
function saveJsonData(string $palvelin, string $kayttaja, string $salasana, string $tietokanta, string $json_string) {
    
    // 1. Yhteyden luominen (MySQLi)
    $mysqli = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);

    
    if ($mysqli->connect_error) {
        echo "Yhteysvirhe: " . $mysqli->connect_error . "\n";
        return false;
    }

    // 2. Datan lisääminen esivalmistellulla lausekkeella
    $sql = "INSERT INTO results (results) VALUES (?)";

    try {
        // Valmistele SQL-lauseke
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Virhe lausekkeessa: " . $mysqli->error);
        }

        // Sitoo parametriin (?) JSON-merkkijonon ('s' = string)
        $stmt->bind_param('s', $json_string);

        // Suorita lauseke
        $stmt->execute();

        $last_id = $mysqli->insert_id;
        
        // Sulje lauseke
        $stmt->close();

        // Sulje yhteys
        $mysqli->close();

        return true;

    } catch (Exception $e) {
        echo "Virhe lisättäessä dataa: " . $e->getMessage() . "\n";
        $mysqli->close();
        return false;
    }
}

