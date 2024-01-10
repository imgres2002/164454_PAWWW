<?php

session_start();
echo 'zmienna z $_SESSION przeszla na inna strone:' . $_SESSION["get_session_var"] . '<br/>';

?>
<a href="./lab4_164454_ISI1.php">strona glowna</a>

