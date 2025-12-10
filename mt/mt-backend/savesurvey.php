<?php
session_start();
require('data/file.php');
if ($_SESSION['ktun'] === $ktun && $_SESSION['ssana'] === $ssana) { ?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset='utf-8'>
    <title>Tallenna kysely</title>
  </head>

  <body>

    <?php
    // if ($_SESSION['ktun'] === 'xxxxx' && $_SESSION['salasana'] === 'xxxxx') {
    $tiedosto = 'data/survey.json';
    $jsondata = file_get_contents('php://input');

    $d = fopen($tiedosto, 'w');
    fwrite($d, $jsondata);
    fclose($d);

    echo '<meta http-equiv=refresh content="0; url=editsurvey.php">';
    ?>

  </body>

  </html>
<?php } ?>