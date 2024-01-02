<?php
// Start the session
session_start();
?>

<?php
    $nr_indeksu = '164454';
    $nrGrupy = 'ISI1';
    echo 'Szymon Bieniaszewski'.$nr_indeksu.'grupa'.$nrGrupy. '<br /><br />';
    echo 'Zastosowanie metody include() <br />';
    $color = '';
    $fruit = '';

    include 'vars.php';

    echo "A $color $fruit <br />";

    echo 'Zastosowanie metody require_once() <br />';

    require_once 'vars.php';

    echo "A $color $fruit<br />";

    echo 'Zastosowanie metody if, else, elseif, switch <br />';

    $a = 1;
    $b = 2;
    $c = 2;
    $i = 0;
    echo "a = $a<br />";
    echo "b = $b<br />";
    echo "c = $c<br />";
    echo "i = $i<br />";

    if ($a == $b) {
        echo 'a=b<br />';
    } else {
        echo 'a != b<br />';
    }
    if ($a == $c) {
        echo 'a=c<br />';
    } elseif ($b == $c) {
        echo 'b = c<br />';
    }

    switch ($i) {
        case 0:
            echo "i equals 0<br />";
            break;
        case 1:
            echo "i equals 1<br />";
            break;
        case 2:
            echo "i equals 2<br />";
            break;
    }

    echo 'Zastosowanie metody  while() i for() <br />';

    $i = 1;
    while ($i <= 10) {
        echo $i++;
    }

    echo '<br />';
    for ($i = 1; $i <= 10; $i++) {
        echo $i;
    }
    echo '<br />';

    echo 'Zastosowanie metody   $_GET, $_POST, $_SESSION <br />';

echo "Favorite color is " . $_SESSION["favcolor"] . ".<br>";
echo "Favorite animal is " . $_SESSION["favanimal"] . ".<br />";

?>
<html>
<body>

<a href="test_get.php?subject=PHP&web=W3schools.com">Test $GET</a>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Name: <input type="text" name="fname">
    <input type="submit">
</form>

</body>
</html>