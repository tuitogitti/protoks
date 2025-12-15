<?php
session_start();
require('data/file.php'); // Tallennetaan tulokset tiedostoon
//require ('saveresults_to_db.php'); // Tallennetaan tulokset tietokantaan
if ($_SESSION['ktun'] === $ktun && $_SESSION['ssana'] === $ssana) { ?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset='utf-8'>
    <title>Tallenna tulokset</title>
  </head>

  <body>

    <?php
    $tiedosto = 'data/results.json';
    $jsondata = file_get_contents('php://input');

    // Dekoodataan data PHP-taulukoksi
    $decodedData = json_decode($jsondata, true);
    $d = fopen($tiedosto, 'w');

    foreach ($decodedData as $phpobj) {

      $jsonobj = json_encode($phpobj);
      // Tallennetaan tulokset tiedostoon
      fwrite($d, $jsonobj . "\n");
    }
    fclose($d);
    
    /*
    // Tallennetaan tulokset tietokantaan 
    $success = saveJsonData($db_server, $db_user, $db_pass, $db_name, $jsondata);

	if ($success === true) {
    echo "Tallennus onnistui.\n";
	} else {
    echo "Tallennusvirhe.\n";
	}     
    */  
    ?>

  </body>

  </html>
<?php } ?>

