<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
/* po tym komentarzu będzie kod do dynamicznego ładowania stron */
if($_GET['idp'] == '') $strona = 'html/glowna.html';
if($_GET['idp'] == 'ciekawostki') $strona = 'html/ciekawostki.html';
if($_GET['idp'] == 'komputer_analogowy') $strona = 'html/komputer_analogowy.html';
if($_GET['idp'] == 'komputer_cyfrowy') $strona = 'html/komputer_cyfrowy.html';
if($_GET['idp'] == 'komputer_elektromechaniczny') $strona = 'html/komputer_elektromechaniczny.html';
if($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
if($_GET['idp'] == 'pierwszy_komputer') $strona = 'html/pierwszy_komputer.html';
if($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="pl" />
        <meta name="Author" content="Szymon Bieniaszewski" />
        <script src="js/kolorujtlo.js" type="text/javascript"> </script>
        <script src="js/timedate.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        if(file_exists($strona)){
            include($strona);
        }
        ?>

        <?php
        $nr_indeksu = '164454';
        $nrGrupy = '1';
        echo 'Autor: Szymon Bieniaszewski '.$nr_indeksu.' grupa '.$nrGrupy.'<br><br/>';
        ?>
    </body>
</html>
