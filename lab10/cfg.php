<?php

// Ustawienie globalnych zmiennych dla loginu i hasła
$GLOBALS['login'] = 'a';
$GLOBALS['pass'] = 'a';

// Funkcja nawiązująca połączenie z bazą danych.
function connect()
{
    // Dane dostępowe do bazy danych
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "moja_strona164454";

    // Nawiązanie połączenia z bazą danych
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    // Sprawdzenie poprawności połączenia
    if (!$conn) {
        die("Connection failed!" . mysqli_connect_error());
    }

    return $conn;
}
?>
