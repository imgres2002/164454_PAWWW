<?php

function PokazKontakt()
{
    // Nagłówek kodu dla formularza kontaktowego
    $formularz = '
        <form action="" method="post">
            <label for="temat">Temat:</label>
            <input type="text" name="temat" id="temat" required><br>

            <label for="tresc">Treść:</label>
            <textarea name="tresc" id="tresc" required></textarea><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <input type="submit" name="wyslij" value="Wyślij">
        </form>
        
        <br>
        
        <form action="" method="post">
            <input type="hidden" name="przypomnij_haslo" value="true">
            <input type="submit" value="Przypomnij hasło">
        </form>
    ';

    echo $formularz;

    // Sprawdzenie, czy formularz przypomnienia hasła został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['przypomnij_haslo']) && $_POST['przypomnij_haslo'] === 'true') {
        PrzypomnijHaslo();
    }

    // Sprawdzenie, czy formularz kontaktowy został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['wyslij'])) {
        WyslijMailKontakt('adres_odbiorcy@example.com');
    }
}

// Funkcja do wysyłania maila z formularza kontaktowego
function WyslijMailKontakt($odbiorca)
{
    // Sprawdzenie, czy formularz został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sprawdzenie, czy pola są wypełnione
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo '[nie_wypelniles_pola]';
            PokazKontakt();
        } else {
            // Utworzenie tablicy z danymi do wysłania
            $mail['subject'] = $_POST['temat'];
            $mail['body'] = $_POST['tresc'];
            $mail['sender'] = $_POST['email'];
            $mail['recipient'] = $odbiorca;

            // Utworzenie nagłówka wiadomości
            $header = "From: Formularz kontaktowy<" . $mail['sender'] . ">\n";
            $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n Content-Transfer-Encoding: 8bit\n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\n";
            $header .= "X-Mailer: PRapwww mail 1.2\n";
            $header .= "X-Priority: 3\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">";

            // Wysłanie maila
            mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

            echo '[wiadomosc_wyslana]';
        }
    }
}

// Funkcja do przypomnienia hasła
function PrzypomnijHaslo()
{
    require_once 'cfg.php';

    // Sprawdzenie, czy formularz został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sprawdzenie, czy pole email jest wypełnione
        if (empty($_POST['email'])) {
            echo '[nie_wypelniles_pola]';
            PokazFormularz();
        } else {
            // Utworzenie tablicy z danymi do wysłania
            $mail['subject'] = 'Przypomnienie hasła';
            $mail['body'] = 'Twoje hasło to: ' . $GLOBALS['pass']; // Pobierz hasło z pliku cfg.php
            $mail['recipient'] = $GLOBALS['login'];
            $mail['sender'] = $_POST['email'];

            // Utworzenie nagłówka wiadomości
            $header = "From: Przypomnienie hasła<" . $mail['sender'] . ">\n";
            $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n Content-Transfer-Encoding: 8bit\n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\n";
            $header .= "X-Mailer: PRapwww mail 1.2\n";
            $header .= "X-Priority: 3\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">";

            // Wywołanie funkcji wysyłającej maila
            WyslijMailKontakt($mail['recipient'], $mail['subject'], $mail['body'], $header);
        }
    } else {
        // Wyświetlenie formularza
        PokazFormularz();
    }
}

// Funkcja do wyświetlenia formularza przypomnienia hasła
function PokazFormularz()
{
    // Formularz przypomnienia hasła
    $formularz = '
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <input type="submit" value="Przypomnij hasło">
        </form>
    ';

    echo $formularz;
}

// Wywołanie funkcji PokazKontakt
PokazKontakt();

?>
