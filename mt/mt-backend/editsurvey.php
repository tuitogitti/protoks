<?php
/*
Kyselyn kysymysten muokkaus toteutettuna JSON-editorilla. Tämä
ei ole sovelluksessa oletuksena käytössä.
*/
session_start();
require('data/file.php');
if ($_SESSION['ktun'] === $ktun && $_SESSION['ssana'] === $ssana) { ?>

  <!DOCTYPE html>
  <html>

  <head>
    <title>Muokkaa sivua</title>
    <meta charset='utf-8'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.3/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.3/jsoneditor.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
      body {
        font-size: 14px;
        font-family: arial;

      }

      p.title {
        font-family: arial;
        font-weight: bold;
        margin-left: 0px;
        font-size: 32px;
      }

      a {
        display: inline-block;
        font-family: arial;
        padding: 7px;
        border: 1px solid black;
        text-decoration: none;
        color: black;
      }

      a:hover {
        color: grey;
      }
    </style>
  </head>

  <body>

    <p class="title">Kyselyn muokkaus</p>

    <p>
      <a href='results1.php'>Tulokset</a>
      <a href='results2.php'>Tulosten vertailu</a>
      <a href='editsurvey.php'>Kyselyn muokkaus</a>
      <a href='logout.php'>Kirjaudu ulos</a>
    </p>

    <p>Kokeile kyselyn muokkausta. Oikeaa kyselyä ei muokata.</p>
    <?php

    $tiedosto = 'data/survey.json';
    $avattu = fopen($tiedosto, 'rb');
    $data = fread($avattu, filesize($tiedosto));
    fclose($avattu);
    ?>

    <!-- Käytetään JSON-editoria -->
    <div id="jsoneditor" style="width: 800px; height: 800px"></div>

    <script>
      // editorin luonti
      const container = document.getElementById("jsoneditor");
      const options = {
        mode: 'tree',
        modes: ['tree', 'code'],
      };

      const editor = new JSONEditor(container, options);

      editor.set(<?php
                  echo $data;
                  ?>);

      function savejson() {
        const jsondata = editor.get();
        axios.post('savesurvey.php', JSON.stringify(jsondata))
          .then(response => {
            console.log('Success:', response.data);
          })
          .catch(error => {
            console.error('Error:', error);
          });
      }
    </script>

    <br />
    <button id="sbutton" onclick="savejson()">Tallenna</button>
  </body>

  </html>
<?php } ?>