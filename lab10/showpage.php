<?php

// Funkcja wyświetlająca treść podstrony na podstawie podanego identyfikatora.
function PokazPodstrone($id, $conn)
{
    // Bezpieczne przetworzenie identyfikatora
    $id_clear = mysqli_real_escape_string($conn, htmlspecialchars($id));

    // Zapytanie do bazy danych
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    // Sprawdzenie czy strona została znaleziona
    if (empty($row['id']))
    {
        $web = '[nie_znaleziono_strony]';
    }
    else
    {
        $web = $row["page_content"];
    }

    return $web;
}

?>
