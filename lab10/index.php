<?php

// Wyłączenie raportowania błędów związanych z noticami i warningami
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Import plików konfiguracyjnego i funkcji wyświetlającej podstronę
include('cfg.php');
include('showpage.php');

// Nawiązanie połączenia z bazą danych
$conn = connect();

// Dynamiczne ładowanie stron na podstawie parametru GET 'idp'
if ($_GET['idp'] == '') $strona = PokazPodstrone(1, $conn);
elseif ($_GET['idp'] == 'ciekawostki') $strona = PokazPodstrone(3, $conn);
elseif ($_GET['idp'] == 'komputer_analogowy') $strona = PokazPodstrone(4, $conn);
elseif ($_GET['idp'] == 'komputer_cyfrowy') $strona = PokazPodstrone(5, $conn);
elseif ($_GET['idp'] == 'komputer_elektromechaniczny') $strona = PokazPodstrone(6, $conn);
elseif ($_GET['idp'] == 'kontakt') $strona = PokazPodstrone(7, $conn);
elseif ($_GET['idp'] == 'pierwszy_komputer') $strona = PokazPodstrone(8, $conn);
elseif ($_GET['idp'] == 'filmy') $strona = PokazPodstrone(2, $conn);

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Szymon Bieniaszewski" />
    <script src="js/kolorujtlo.js"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Komputer moją pasją</title>
</head>
<body onload="startclock()">
<div class="navbar">
    <a href="index.php?idp=">Home</a>
    <a href="index.php?idp=ciekawostki">Ciekawostki</a>
    <div class="dropdown">
        <button class="dropbtn">Historia
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="index.php?idp=pierwszy_komputer">pierwszy komputer</a>
            <a href="index.php?idp=komputer_elektromechaniczny">elektromechaniczna maszyna licząca</a>
            <a href="index.php?idp=komputer_analogowy">komputer analogowy</a>
            <a href="index.php?idp=komputer_cyfrowy">komputer cyfrowy</a>
        </div>
    </div>
    <a href="index.php?idp=kontakt">kontakt</a>
    <a href="index.php?idp=filmy">filmy</a>
</div>

<?php
// wyświetlanie strony którą wybrał użytkownik
echo $strona;
?>

<?php
// Dodatkowe informacje na dole strony
$nr_indeksu = '164454';
$nrGrupy = '1';
echo 'Autor: Szymon Bieniaszewski ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br/>';
?>
</body>
</html>
