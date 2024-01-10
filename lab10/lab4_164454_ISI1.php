<?php
session_start();
$nr_indeksu = '164454';
$nrGrupy = 'ISI1';
echo 'Szymon Bieniaszewski' . $nr_indeksu . 'grupa' . $nrGrupy . '<br/><br/>';

$color = '';
$fruit = '';

echo 'Zastosowanie metody include() <br/>';
include 'test.php';
echo '<br/>';
echo '<br/>';

echo 'Zastosowanie metody require_once() <br/>';
require_once 'test.php';
echo '<br/>';
echo '<br/>';
echo 'nic się nie wyświetla ponieważ wcześniej użyliśmy metodę include <br/>';
echo '<br/>';

echo 'Zastosowanie metody if, else, elseif, switch <br/>';
$a = 1;
$b = 2;
$c = 2;
$i = 0;
echo 'zmienne: <br/>';
echo "a = $a<br/>";
echo "b = $b<br/>";
echo "c = $c<br/>";
echo '<br/>';

echo 'zastosowania metody if i else <br/>';
if ($a == $b) {
    echo 'a = b<br/>';
} else {
    echo 'a != b<br/>';
}
echo '<br/>';

echo 'zastosowania metody if i elsif <br/>';
if ($a == $c) {
    echo 'a=c<br/>';
} elseif ($b == $c) {
    echo 'b = c<br/>';
}
echo '<br/>';

echo 'zastosowania metody switch <br/>';
switch ($a) {
    case 0:
        echo "a równa się 0<br/>";
        break;
    case 1:
        echo "a równa się 1<br/>";
        break;
    case 2:
        echo "a równa 2<br/>";
        break;
}
echo '<br/>';

echo 'Zastosowanie metody while()<br/>';
$i = 1;
while ($i <= 10) {
    echo $i++;
}
echo '<br />';
echo '<br />';

echo 'Zastosowanie metody for()<br/>';
for ($i = 1; $i <= 10; $i++) {
    echo $i;
}
echo '<br />';
?>
<html>
    <body>
        <p>Zastosowanie metody $_GET</p>
        <form id="GET_test" method="get">
            <label for="first_name">wprowadź imię:</label>
            <input type="text" id="first_name" name="first_name">
            <button type="submit" name="submit_first_name">potwierdź</button>
        </form>
        <?php
        if(isset($_GET["submit_first_name"])){
            $first_name = $_GET["first_name"];
            echo "$first_name</br>";
        }
        ?>
        <p>Zastosowanie metody $_POST</p>
        <form id="POST_test" method="post">
            <label for="last_name">wprwadź nazwisko:</label>
            <input type="text" id="last_name" name="last_name">
            <button type="submit" name="submit_last_name">potwierdź</button>
        </form>
        <?php
        if(isset($_POST["submit_last_name"])){
            $last_name = $_POST["last_name"];
            echo "$last_name</br>";
        }
        ?>
        <p>Zastosowanie metody $_SESSION</p>
        <form id="SESSION_test" method="get">
            <label for="session_var">wprowadź zmienną sesji:</label>
            <input type="text" id="session_var" name="session_var">
            <button type="submit" name="submit_session_var">potwierdź</button>
        </form>
        <?php
        if(isset($_GET["submit_session_var"])){
            $session_var = $_GET["session_var"];
            $_SESSION["get_session_var"] = $session_var;
            echo $_SESSION["get_session_var"] . "<br/>";
        }
        ?>
        <p>przejdź na inną stronę aby przetestować $_SESSION</p>
        <a href="./session_test.php">strona testowa</a>
    </body>
</html>
