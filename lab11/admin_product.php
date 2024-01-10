<?php
include('cfg.php');
global $login, $pass;

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

function ListaProduktow()
{
    echo '
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Szczegóły produktów</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>tytul</th>
                                        <th>zdjecie</th>
                                    </tr>
                                </thead>
                                <tbody>';
        $conn = connect();
        $query = "SELECT id, tytul, zdjecie FROM produkty ORDER BY id DESC LIMIT 100";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($id, $tytul, $zdjecie);

        while ($stmt->fetch()) {
        echo '
            <form method="POST">
                <tr>
                    <td>' . $id . '</td>
                    <td>' . htmlspecialchars($tytul) . '</td>
                    <td><img src="' . $zdjecie . '" max-height="500px" max-width="50px"></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="' . $id . '">
                            <input type="submit" name="update" value="edytuj" class="btn btn-success btn-sm"> 
                            <input type="submit" name="delete" value="usuń" class="btn btn-danger btn-sm">
                        </form>
                    </td>
                </tr>
            </form>
        ';
    }
        echo '
                            </tbody>
                        </table>
                        <form method="post">
                            <label for="add">Dodaj nowy produkt:</label>
                            <input type="submit" name="add" value="dodaj" class="btn btn-primary btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
        $conn->close();
}

function EdytujProdukt($id)
{
    $conn = connect();
    $stmt = $conn->prepare("SELECT tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu, data_wygasniecia, zdjecie FROM produkty WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($tytul, $opis, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk, $status_dostepnosci, $kategoria, $gabaryt_produktu, $data_wygasniecia, $zdjecie);
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
                                <label for="update_tytul">Tytuł:</label>
                    <input type="text" id="update_tytul" name="update_tytul" value="' . htmlspecialchars($tytul) . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_opis">Opis:</label>
                    <textarea id="update_opis" name="update_opis">' . htmlspecialchars($opis). '</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="update_cena_netto">Cena netto:</label>
                    <input type="text" id="update_cena_netto" name="update_cena_netto" value="' . $cena_netto . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_podatek_vat">Podatek VAT:</label>
                    <input type="text" id="update_podatek_vat" name="update_podatek_vat" value="' . $podatek_vat . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_ilosc_dostepnych_sztuk">Ilość dostępnych sztuk:</label>
                    <input type="text" id="update_ilosc_dostepnych_sztuk" name="update_ilosc_dostepnych_sztuk" value="' . $ilosc_dostepnych_sztuk . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_status_dostepnosci">Status dostępności:</label>
                    <input type="checkbox" id="update_status_dostepnosci" name="update_status_dostepnosci" ' . ($status_dostepnosci ? 'checked' : '') . '>
                            </div>
                            <div class="mb-3">
                                <label for="update_kategoria">Kategoria:</label>
                    <input type="text" id="update_kategoria" name="update_kategoria" value="' . $kategoria . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_gabaryt_produktu">Gabaryt produktu:</label>
                    <input type="text" id="update_gabaryt_produktu" name="update_gabaryt_produktu" value="' . $gabaryt_produktu . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_data_wygasniecia">Data wygaśnięcia:</label>
                    <input type="date" id="update_data_wygasniecia" name="update_data_wygasniecia" value="' . $data_wygasniecia . '">
                            </div>
                            <div class="mb-3">
                                <label for="update_zdjecie">Zdjecie:</label>
                    <input type="text" id="update_zdjecie" name="update_zdjecie" value="' . $zdjecie . '">
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


function DodajNowyProdukt()
{
    echo '
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Dodaj produkt</h4>
                        <form action="" method="post">
                            <input type="submit" name="close_form" value="zamknij" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="add_tytul">Tytuł:</label>
                                <input type="text" id="add_tytul" name="add_tytul">
                            </div>
                            <div class="mb-3">
                                <label for="add_opis">Opis:</label>
                                <textarea id="add_opis" name="add_opis"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="add_cena_netto">Cena netto:</label>
                                <input type="text" id="add_cena_netto" name="add_cena_netto">
                            </div>
                            <div class="mb-3">
                                <label for="add_podatek_vat">Podatek VAT:</label>
                                <input type="text" id="add_podatek_vat" name="add_podatek_vat">
                            </div>
                            <div class="mb-3">
                                <label for="add_ilosc_dostepnych_sztuk">Ilość dostępnych sztuk:</label>
                                <input type="text" id="add_ilosc_dostepnych_sztuk" name="add_ilosc_dostepnych_sztuk">
                            </div>
                            <div class="mb-3">
                                <label for="add_status_dostepnosci">Status dostępności:</label>
                                <input type="checkbox" id="add_status_dostepnosci" name="add_status_dostepnosci"> 
                            </div>
                            <div class="mb-3">
                                <label for="add_gabaryt_produktu">Gabaryt produktu:</label>
                                <input type="text" id="add_gabaryt_produktu" name="add_gabaryt_produktu">
                            </div>
                            <div class="mb-3">
                                <label for="add_data_wygasniecia">Data wygaśnięcia:</label>
                                <input type="date" id="add_data_wygasniecia" name="add_data_wygasniecia">
                            </div>
                            <div class="mb-3">
                                <label for="add_zdjecie">Zdjecie:</label>
                                <input type="text" id="add_zdjecie" name="add_zdjecie">
                            </div>
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

function UsunProdukt($id)
{
    $conn = connect();
    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ? LIMIT 1");
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
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    ListaProduktow();

    if (isset($_POST['add'])) {
        DodajNowyProdukt();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
        $id = $_POST['id'];
        UsunProdukt($id);
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        EdytujProdukt($id);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_add'])) {
        $tytul = $_POST['add_tytul'];
        $opis = $_POST['add_opis'];
        $cena_netto = $_POST['add_cena_netto'];
        $podatek_vat = $_POST['add_podatek_vat'];
        $ilosc_dostepnych_sztuk = $_POST['add_ilosc_dostepnych_sztuk'];
        $status_dostepnosci = isset($_POST["add_status_dostepnosci"]) ? 1 : 0;
        $kategoria = $_POST['add_kategoria'];
        $gabaryt_produktu = $_POST['add_gabaryt_produktu'];
        $data_wygasniecia = $_POST['add_data_wygasniecia'];
        $zdjecie = $_POST['add_zdjecie'];

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu, data_wygasniecia, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssddiissss', $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk, $status_dostepnosci, $kategoria, $gabaryt_produktu, $data_wygasniecia, $zdjecie);
        $stmt->execute();
        $conn->close();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_update'])) {
        $id = $_POST['update_id'];
        $tytul = $_POST['update_tytul'];
        $opis = $_POST['update_opis'];
        $cena_netto = $_POST['update_cena_netto'];
        $podatek_vat = $_POST['update_podatek_vat'];
        $ilosc_dostepnych_sztuk = $_POST['update_ilosc_dostepnych_sztuk'];
        $status_dostepnosci = isset($_POST["update_status_dostepnosci"]) ? 1 : 0;
        $kategoria = $_POST['update_kategoria'];
        $gabaryt_produktu = $_POST['update_gabaryt_produktu'];
        $data_wygasniecia = $_POST['update_data_wygasniecia'];
        $zdjecie = $_POST['update_zdjecie'];

        $conn = connect();
        $stmt = $conn->prepare("UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_dostepnych_sztuk = ?, status_dostepnosci = ?, kategoria = ?, gabaryt_produktu = ?, data_wygasniecia = ?, zdjecie = ? WHERE id = ?");
        $stmt->bind_param('ssddiissssi', $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk, $status_dostepnosci, $kategoria, $gabaryt_produktu, $data_wygasniecia, $zdjecie, $id);
        $stmt->execute();
        $conn->close();
    }

}
?>
</body>
</html>