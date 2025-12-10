<?php
// tunnarit sessioon
session_start();
$_SESSION['ktun'] = filter_input(INPUT_POST, "ktun", FILTER_UNSAFE_RAW);
$_SESSION['ssana'] = filter_input(INPUT_POST, "ssana", FILTER_UNSAFE_RAW);
// siirrytään tulossivulle
header('Location: results1.php');
