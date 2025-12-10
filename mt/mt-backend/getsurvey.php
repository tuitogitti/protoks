<?php
/* 
Kyselyn JSON-datan välitys palvelimelta käyttöliittymään.
Tämä ei ole sovelluksessa käytössä, mutta jos
kysely haettaisiin palvelimelta, tätä tarvittaisiin.
*/
$jsondata = file_get_contents('data/survey.json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json; charset=utf-8');
echo $jsondata;
