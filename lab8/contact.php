<?php
include('cfg.php');
// Nagłówek
function PokazKontakt()
{
    // Kod HTML formularza kontaktowego
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
        <p>przypomnij haslo</p>
        <!-- Przycisk "Przypomnij hasło" -->
        <form action="" method="post">
            <label for="email_admin">Email:</label>
            <input type="email" name="email_admin" id="email_admin"><br>
            <input type="submit" name="przypomnij" value="przypomnij">
        </form>
    ';

    echo $formularz;
}

// Funkcja do wysyłania maila kontaktowego
function WyslijMailKontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        PokazKontakt();
    } else {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca;

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

// Funkcja do przypomnienia hasła
function PrzypomnijHaslo()
{
    echo $GLOBALS['login'];
    $mail['subject'] = 'Przypomnienie hasła';
    $mail['body'] = 'Twoje hasło to: ' . $GLOBALS['pass']; // Pobierz hasło z pliku cfg.php
    $mail['sender'] = $GLOBALS['login'];
    $mail['recipient'] = $GLOBALS['login'];
    $header = "From: Przypomnienie hasła dla<" . $mail['recipient'] . ">\n";
    $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n Content-Transfer-Encoding: 8bit\n";
    $header .= "X-Sender: <" . $mail['sender'] . ">\n";
    $header .= "X-Mailer: PRapwww mail 1.2\n";
    $header .= "X-Priority: 3\n";
    $header .= "Return-Path: <" . $mail['sender'] . ">";

    // Wysłanie maila z hasłem
    mail($mail['recipient'], $mail['subject'], $mail['body'], $header);
}

// Funkcja do wyświetlania formularza przypomnienia hasła
function PokazFormularz()
{
    // Kod HTML formularza przypomnienia hasła
    $formularz = '
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <input type="submit" value="Przypomnij hasło">
        </form>
    ';

    echo $formularz;
}

echo PokazKontakt();
// Sprawdź, czy przycisk "Przypomnij hasło" został kliknięty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['przypomnij']) ) {
    if (isset($_POST["email_admin"]) && $_POST["email_admin"] == $GLOBALS['login']){
        PrzypomnijHaslo();
    }
}

// Sprawdź, czy przycisk "Wyślij" został kliknięty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['wyslij'])) {
    WyslijMailKontakt('adres_odbiorcy@example.com');
}
?>
