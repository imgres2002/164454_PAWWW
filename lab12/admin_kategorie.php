<?php
// Załączenie pliku konfiguracyjnego
include('cfg.php');
// Zdefiniowanie zmiennych globalnych dla loginu i hasła
global $login, $pass;

// Funkcja generująca formularz logowania
function FormularzLogowania()
{
    return '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
                <table class="logowanie">
                    <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie"/></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie"/></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj"/></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';
}

// Funkcja rekurencyjnie wyświetlająca kategorie w postaci tabeli HTML
function PokazKategorie()
{
    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Szczegóły kategorii</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>nazwa</th>
                                </tr>
                            </thead>
                            <tbody>';
    $matka = 0;
    $poziom = 0;
    $conn = connect();
    $query = "SELECT id, matka, nazwa FROM kategorie WHERE matka = ? ORDER BY id ASC LIMIT 100";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $matka);
    $stmt->execute();
    $stmt->bind_result($id, $matka, $nazwa);
    while ($stmt->fetch()) {

        echo '
            <form method="POST">
                <tr>
                    <td>' . $id . '</td>
                    <td>' . $nazwa . '</td>
                    <td>
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="submit" name="update" value="edytuj" class="btn btn-success btn-sm"> 
                        <input type="submit" name="delete" value="usuń" class="btn btn-danger btn-sm">
                    </td>
                </tr>
            </form>
        ';

        // Rekurencyjnie wywołaj funkcję dla podkategorii
        PokazPodKategorie($id, $poziom + 1);
    }

    echo '
                            </tbody>
                        </table>
                        <form method="post">
                            <label for="add">Dodaj nową kategorię:</label>
                            <input type="submit" name="add" value="dodaj" class="btn btn-primary btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    $conn->close();
}
function PokazPodKategorie($matka = 0, $poziom = 0)
{
    $conn = connect();
    $query = "SELECT id, matka, nazwa FROM kategorie WHERE matka = ? ORDER BY id ASC LIMIT 100";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $matka);
    $stmt->execute();
    $stmt->bind_result($id, $matka, $nazwa);

    while ($stmt->fetch()) {
        // Dodaj wcięcia dla czytelności
        $wciecie = str_repeat('&nbsp;', $poziom * 4);

        echo '
            <form method="POST">
                <tr>
                    <td>'. $wciecie . $id . '</td>
                    <td>' . $nazwa . '</td>
                    <td>
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="submit" name="update" value="edytuj" class="btn btn-success btn-sm"> 
                        <input type="submit" name="delete" value="usuń" class="btn btn-danger btn-sm">
                    </td>
                </tr>
            </form>
        ';

        // Rekurencyjnie wywołaj funkcję dla podkategorii
        PokazPodKategorie($id, $poziom + 1);
    }
}
// Funkcja generująca formularz edycji kategorii w postaci tabeli HTML
function EdytujKategorie($id)
{
    $conn = connect();
    $stmt = $conn->prepare("SELECT matka, nazwa, status FROM kategorie WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($matka, $nazwa, $status);
    $stmt->fetch();

    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edytuj kategorię</h4>
                        <form action="" method="post">
                            <input type="submit" name="close_form" value="zamknij" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <input type="hidden" name="update_id" value="' . $id . '">
                            <div class="mb-3">
                                <label for="update_matka">Matka:</label>
                                <input type="text" id="update_matka" name="update_matka" value="' . $matka . '"> 
                            </div>
                            <div class="mb-3">
                                <label for="update_nazwa">Nazwa:</label>
                                <textarea id="update_nazwa" name="update_nazwa">' . htmlspecialchars($nazwa). '</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="update_status">Czy aktywna:</label>
                                <input type="checkbox" id="update_status" name="update_status" ' . ($status ? 'checked' : '') . ' > 
                            </div>    
                            <div class="mb-3">
                                <input type="submit" name="submit_update" value="potwierdź" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
    $conn->close();
}

// Funkcja generująca formularz dodawania nowej kategorii w postaci tabeli HTML
function DodajKategorie()
{
    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Dodaj kategorię</h4>
                        <form action="" method="post">
                            <input type="submit" name="close_form" value="zamknij" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
    
                            <div class="mb-3">
                                <label for="add_matka">Matka:</label>
                                <input type="text" id="add_matka" name="add_matka">
                            </div>
                            <div class="mb-3">
                                <label for="add_nazwa">Nazwa:</label>
                    <textarea id="add_nazwa" name="add_nazwa"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="add_status">Czy aktywna:</label>
                                <input type="checkbox" id="add_status" name="add_status"> 
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="submit_add" class="btn btn-primary">potwierdź</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
}

// Funkcja usuwająca kategorię o określonym ID, wywołująca rekurencyjnie funkcję UsunPodkategorie
function UsunKategorie($id)
{
    $conn = connect();

    // Usunięcie kategorii o podanym ID
    $stmt = $conn->prepare("DELETE FROM kategorie WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Rekurencyjne usunięcie podkategorii
    UsunPodkategorie($id, $conn);

    $conn->close();
}

// Funkcja rekurencyjna usuwająca podkategorie danej kategorii
function UsunPodkategorie($matka, $conn)
{
    $stmt = $conn->prepare("SELECT id FROM kategorie WHERE matka = ? LIMIT 1");
    $stmt->bind_param('i', $matka);
    $stmt->execute();
    $result = $stmt->get_result();

    // Przechodzenie przez wszystkie podkategorie i ich rekurencyjne usunięcie
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        UsunKategorie($id);
    }

    $stmt->close();
}

// Rozpoczęcie sesji
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    // Sprawdzenie logowania
    if ($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['login_email']) && $_POST['login_email'] == $login
        && isset($_POST['login_pass']) && $_POST['login_pass'] == $pass) {
        $_SESSION['loggedin'] = true;
    } else {
        $_SESSION['loggedin'] = false;
        echo FormularzLogowania();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Szymon Bieniaszewski" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <title>CMS</title>
</head>
<body>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    // Wyświetlanie kategorii dla zalogowanego użytkownika
    PokazKategorie();

    // Wywołanie funkcji DodajKategorie po naciśnięciu przycisku "Dodaj"
    if (isset($_POST['add'])) {
        DodajKategorie();
    }

    // Usunięcie kategorii po naciśnięciu przycisku "usuń"
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
        $id = $_POST['id'];
        UsunKategorie($id);
    }

    // Edycja kategorii po naciśnięciu przycisku "edytuj"
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        EdytujKategorie($id);
    }

    // Dodanie nowej kategorii po potwierdzeniu formularza
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_add'])) {
        $matka = $_POST['add_matka'];
        $nazwa = $_POST['add_nazwa'];
        $status = isset($_POST["add_status"]) ? 1 : 0;
        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO kategorie (matka, nazwa, status) VALUES (?, ?, ?) LIMIT 1");
        $stmt->bind_param('isi', $matka, $nazwa, $status);
        $stmt->execute();
        $conn->close();
    }

    // Aktualizacja kategorii po potwierdzeniu formularza
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_update'])) {
        $id = $_POST['update_id'];
        $matka = $_POST['update_matka'];
        $nazwa = $_POST['update_nazwa'];
        $status = isset($_POST["update_status"]) ? 1 : 0;
        $conn = connect();
        $stmt = $conn->prepare("UPDATE kategorie SET matka = ?, nazwa = ?, status = ? WHERE id = ? LIMIT 1");
        $stmt->bind_param('isii', $matka, $nazwa, $status, $id);
        $stmt->execute();
        $conn->close();
    }
}
?>
</body>
</html>
