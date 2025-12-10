<?php
session_start();
// tunnarit pois sessiosta
unset($_SESSION['ktun']);
unset($_SESSION['ssana']);
session_destroy();

header('Location: index.html');
