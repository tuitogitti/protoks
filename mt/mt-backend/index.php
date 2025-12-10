<?php
/* 
 * Tallennetaan JSON-muodossa saadut kyselytulokset tiedostoon.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
// Asetetaan Content-Type, jotta selain antaa tekstimuotoisen vastauksen
header('Content-Type: text/plain');

// Otetaan vastaan JSON-data
$json = file_get_contents('php://input');
date_default_timezone_set('Europe/Helsinki');


$arr1 = json_decode($json, true);

if (!isset($arr1["Vapaa palaute"])) {
  $arr1["Vapaa palaute"] = 'Ei vastausta';
}

if ($arr1["Tunnistautuminen"] === 'mt' || 'MT' || 'Mt') {

  $arr1['Aika'] = date("d-m-Y H:i:s");
  unset($arr1["Tunnistautuminen"]);
  $json_data = json_encode($arr1);

  $arr2 = json_decode($json_data, true);


  $json_data2 = json_encode($arr2);
  $file = 'data/results.json';

  // Avataan tiedosto liittämistä varten ('a' - append)
  $openfile = fopen($file, 'a');

  if (count($arr2) == 13) {

    if ($openfile) {
      // Kirjoitetaan uusi JSON-data tiedostoon ja lisää rivinvaihto
      fwrite($openfile, $json_data2 . "\n");

      fclose($openfile);
    }
  }
}
