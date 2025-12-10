<?php
session_start();
require('data/file.php');
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
      fwrite($d, $jsonobj . "\n");
    }
    fclose($d);
    ?>

  </body>

  </html>
<?php } ?>