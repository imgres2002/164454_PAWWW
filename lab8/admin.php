<?php
include('cfg.php');
global $login, $pass;

// Funkcja generująca formularz logowania
function FormularzLogowania()
{
    $wynik = '
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
    return $wynik;
}

// Funkcja generująca listę podstron
function ListaPodstron()
{
    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Szczegóły stron</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tytuł Strony</th>
                                </tr>
                            </thead>
                            <tbody>';

    // Połączenie z bazą danych i pobranie listy stron
    $conn = connect();
    $query = "SELECT id, page_title FROM page_list ORDER BY id DESC LIMIT 100";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($id, $page_title);

    // Wyświetlenie listy stron w tabeli
    while ($stmt->fetch()) {
        echo '
            <form method="POST">
                <tr>
                    <td>' . $id . '</td>
                    <td>' . htmlspecialchars($page_title) . '</td>
                    <td>
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="submit" name="update" value="edytuj" class="btn btn-success btn-sm">
                        <input type="submit" name="delete" value="usuń" class="btn btn-danger btn-sm">
                    </td>
                </tr>
            </form>
        ';
    }

    echo '
                            </tbody>
                        </table>
                        <form method="post">
                            <label for="add">Dodaj nową podstronę:</label>
                            <input type="submit" name="add" value="dodaj" class="btn btn-primary btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    $conn->close();
}

// Funkcja edytująca wybraną podstronę
function EdytujPodstrone($id)
{
    $conn = connect();
    $stmt = $conn->prepare("SELECT page_title, page_content, status FROM page_list WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($page_title, $page_content, $status);
    $stmt->fetch();

    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edytuj stronę</h4>
                        <form action="" method="post">
                            <input type="submit" name="close_form" value="zamknij" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <input type="hidden" name="update_id" value="' . $id . '">
                            <div class="mb-3">
                                <label for="update_page_title">Tytuł:</label>
                                <input type="text" id="update_page_title" name="update_page_title" value="' . htmlspecialchars($page_title) . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_page_content">Treść:</label>
                                <textarea id="update_page_content" name="update_page_content">' . htmlspecialchars($page_content). '</textarea>
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

// Funkcja dodająca nową podstronę
function DodajNowaPodstrone()
{
    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Dodaj stronę</h4>
                        <form action="" method="post">
                            <input type="submit" name="close_form" value="zamknij" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
    
                            <div class="mb-3">
                                <label for="add_page_title">Tytuł:</label>
                                <input type="text" id="add_page_title" name="add_page_title">
                            </div>
                            <div class="mb-3">
                                <label for="add_page_content">Treść:</label>
                                <textarea id="add_page_content" name="add_page_content"></textarea>
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

// Funkcja usuwająca wybraną podstronę
function UsunPodstrone($id)
{
    $conn = connect();
    $stmt = $conn->prepare("DELETE FROM page_list WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $conn->close();
}

session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
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
    <link rel="stylesheet" href="css/style.css">
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
// Sprawdzenie, czy użytkownik jest zalogowany
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    // Wywołanie funkcji generującej listę podstron
    ListaPodstron();

    // Sprawdzenie, czy formularz dodawania został przesłany
    if (isset($_POST['add'])) {
        // Wywołanie funkcji dodającej nową podstronę
        DodajNowaPodstrone();
    }

    // Sprawdzenie, czy żądanie jest typu POST i czy przycisk usuwania został naciśnięty
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
        // Pobranie identyfikatora podstrony do usunięcia
        $id = $_POST['id'];
        // Wywołanie funkcji usuwającej podstronę
        UsunPodstrone($id);
    }

    // Sprawdzenie, czy przycisk edycji został naciśnięty
    if (isset($_POST['update'])) {
        // Pobranie identyfikatora podstrony do edycji
        $id = $_POST['id'];
        // Wywołanie funkcji edytującej podstronę
        EdytujPodstrone($id);
    }

    // Obsługa zamknięcia formularza (pusta blokada)
    if (isset($_POST['close_form'])) {

    }

    // Sprawdzenie, czy żądanie jest typu POST i czy przycisk dodawania został naciśnięty
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_add'])) {
        // Pobranie danych z formularza dodawania
        $page_title = $_POST['add_page_title'];
        $page_content = $_POST['add_page_content'];
        $status = isset($_POST["add_status"]) ? 1 : 0;
        // Połączenie z bazą danych i dodanie nowej podstrony
        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?) LIMIT 1");
        $stmt->bind_param('ssi', $page_title, $page_content, $status);
        $stmt->execute();
        $conn->close();
        // Wywołanie funkcji generującej listę podstron po dodaniu
    }

    // Sprawdzenie, czy żądanie jest typu POST i czy przycisk aktualizacji został naciśnięty
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_update'])) {
        // Pobranie danych z formularza aktualizacji
        $id = $_POST['update_id'];
        $page_title = $_POST['update_page_title'];
        $page_content = $_POST['update_page_content'];
        $status = isset($_POST["update_status"]) ? 1 : 0;
        // Połączenie z bazą danych i aktualizacja danych podstrony
        $conn = connect();
        $stmt = $conn->prepare("UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ? LIMIT 1");
        $stmt->bind_param('ssii', $page_title, $page_content, $status, $id);
        $stmt->execute();
        $conn->close();
        // Wywołanie funkcji generującej listę podstron po aktualizacji
    }
}
?>

</body>

</html>
