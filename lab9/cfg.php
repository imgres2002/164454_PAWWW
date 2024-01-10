<?php
// Ustawienie globalnych zmiennych przechowujących dane do logowania
$GLOBALS['login'] = 'a@a';
$GLOBALS['pass'] = 'a';

// Funkcja łącząca się z bazą danych
function connect()
{
    // Dane do połączenia z bazą danych
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "moja_strona164454";

    // Utworzenie połączenia z bazą danych
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    // Sprawdzenie, czy połączenie zostało poprawnie nawiązane
    if (!$conn) {
        // Zakończenie działania skryptu i wyświetlenie błędu w przypadku nieudanego połączenia
        die("Connection failed!" . mysqli_connect_error());
    }

    // Zwrócenie obiektu reprezentującego połączenie z bazą danych
    return $conn;
}
?>
