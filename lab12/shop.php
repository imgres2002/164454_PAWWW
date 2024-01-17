<?php

session_start();
// Załączenie pliku konfiguracyjnego
include_once 'cfg.php';
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
function ListaProduktow()
{
    echo '
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lista produktów</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Opis</th>
                                        <th>Zdjęcie</th>
                                    </tr>
                                </thead>
                                <tbody>';
    $conn = connect();
    $query = "SELECT id, tytul, opis, zdjecie, status_dostepnosci FROM produkty ORDER BY id DESC LIMIT 100";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($id, $tytul, $opis, $zdjecie, $status_dostepnosci);

    while ($stmt->fetch()) {
        if ($status_dostepnosci == 1) {
            echo '
                <tr>
                    <td>' . htmlspecialchars($tytul) . '</td>
                    <td>' . htmlspecialchars($opis) . '</td>
                    <td><img  src="' . $zdjecie . '" style="height: 20;"></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="' . $id . '">
                            <input type="submit" name="add_to_cart" value="Dodaj do koszyka" class="btn btn-primary btn-sm">
                        </form>
                    </td>
                </tr>
            ';
        }
    }
    $stmt->close();
    echo '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

// koszyk -------------------------------------------------------------------------------------------------------------------------------------------------------------
function AddToCard($id_prod)
{
    if (!isset($_SESSION['count'])) {
        $_SESSION['count'] = 1;
    } else {
        // sprawdzenie czy produkt jest w koszyku
        $nr = 1;
        while ($nr <= $_SESSION['count']) {
            $nr_1 = $nr . '_1';
            if (isset($_SESSION[$nr_1]) && $_SESSION[$nr_1] == $id_prod) {
                echo '
                    <form method="get">
                        <label for="show_home_page">Produkt już jest w koszyku:</label>
                        <input type="submit" name="show_cart" value="Przejdź do koszyka" class="btn btn-success btn-sm">
                        <input type="submit" name="show_home_page" value="Kontynuj zakupy" class="btn btn-success btn-sm">
                    </form>
                ';
                return;
            }
            $nr++;
        }
        $_SESSION['count']++;
    }

    $nr = $_SESSION['count'];

    $prod[$nr]['id_prod'] = $id_prod;
    $prod[$nr]['ile_sztuk'] = 1;

    $nr_0 = $nr . '_0';
    $nr_1 = $nr . '_1';
    $nr_2 = $nr . '_2';

    $_SESSION[$nr_0] = $nr;
    $_SESSION[$nr_1] = $prod[$nr]['id_prod'];
    $_SESSION[$nr_2] = $prod[$nr]['ile_sztuk'];
    echo '
            <form method="get">
                <label for="show_home_page">Pomyślnie dodano do koszyka:</label>
                <input type="submit" name="show_cart" value="Przejdź do koszyka" class="btn btn-success btn-sm">
                <input type="submit" name="show_home_page" value="Kontynuj zakupy" class="btn btn-success btn-sm">
            </form>
        ';
}

function RemoveFromCard($nr)
{
    $nr_0 = $nr . '_0';
    $nr_1 = $nr . '_1';
    $nr_2 = $nr . '_2';
    unset($_SESSION[$nr_0]);
    unset($_SESSION[$nr_1]);
    unset($_SESSION[$nr_2]);
}

function Pay()
{
    $is_done_one_time = False;
    if (!isset($_SESSION['count'])) {
        // sprawdza czy funckcja się wywołała z jakimś produktem
        $is_done_one_time = False;
    } else {
        // zmienne inicjujące początek iterowania po produktach
        $nr = 1;
        // iterowanie po produktach w sesji
        while ($nr <= $_SESSION['count']) {
            $nr_0 = $nr . '_0';
            if (isset($_SESSION[$nr_0])) {
                // sprawdza czy funckcja się wywołała z jakimś produktem
                $is_done_one_time = True;
                $nr_1 = $nr . '_1';
                $nr_2 = $nr . '_2';

                $conn = connect();
                $query = "SELECT * FROM produkty WHERE id='$_SESSION[$nr_1]' LIMIT 1";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_array($result);
                $ilosc_dostepnych_sztuk_produkt = $row["ilosc_dostepnych_sztuk"] - $_SESSION[$nr_2];
                $conn->close();

                // usunięcie kupionych produktów
                $conn = connect();
                $stmt = $conn->prepare("UPDATE produkty SET ilosc_dostepnych_sztuk = ? WHERE id = ? LIMIT 1");
                $stmt->bind_param('ii', $ilosc_dostepnych_sztuk_produkt, $_SESSION[$nr_1]);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
            // zmienne do następnej iteracji
            $nr++;
        }
    }
    // usuwa wszystki produkty z koszyka

    if ($is_done_one_time) {
        RemoveAllProducts();
        echo '
        <form method="get">
            <label for="show_home_page">Pomyślnie zapłacono</label>
            <input type="submit" name="show_home_page" value="Zaakceptuj" class="btn btn-success btn-sm">
        </form>
    ';
    } else {
        echo '
        <form method="get">
            <label for="show_home_page">Musisz mieć produkty w koszyku aby zapłacić</label>
            <input type="submit" name="show_home_page" value="Wróć na stronę główną" class="btn btn-success btn-sm">
        </form>
    ';
    }
}

function RemoveAllProducts()
{
    if (!isset($_SESSION['count'])) {
        echo "Twój koszyk jest pusty";
    } else {
        // zmienne inicjujące początek iterowania po produktach
        $nr = 1;
        // iterowanie po produktach w sesji
        while ($nr <= $_SESSION['count']) {
            $nr_0 = $nr . '_0';
            $nr_1 = $nr . '_1';
            $nr_2 = $nr . '_2';
            if (isset($_SESSION[$nr_0])) {
                unset($_SESSION[$nr_0]);
            }
            if (isset($_SESSION[$nr_1])) {
                unset($_SESSION[$nr_1]);
            }
            if (isset($_SESSION[$nr_2])) {
                unset($_SESSION[$nr_2]);
            }
            $nr ++;
        }
    }
}

function ShowCard()
{
    echo '
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Koszyk</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Zdjęcie</th>
                                        <th>Tytuł</th>
                                        <th>Cena</th>
                                        <th>Ilość</th>
                                        <th>łącznie</th>
                                    </tr>
                                </thead>
                                <tbody>';
    // warunek sprawdzający czy zostały stworzone jakiekolwiek sesje
    if (!isset($_SESSION['count'])) {
        echo "Twój koszyk jest pusty";
    } else {
        // zmienne inicjujące początek iterowania po produktach
        $nr = 1;
        // łączna wartośc koszyka
        $total_price = 0;
        // iterowanie po produktach w sesji
        while ($nr <= $_SESSION['count']) {
            $nr_0 = $nr . '_0';
            if (isset($_SESSION[$nr_0])) {
                $nr_0 = $nr . '_0';
                $nr_1 = $nr . '_1';
                $nr_2 = $nr . '_2';

                $conn = connect();
                $stmt = $conn->prepare("SELECT zdjecie, tytul, cena_netto, podatek_vat FROM produkty WHERE id=? LIMIT 1");
                $stmt->bind_param('i', $_SESSION[$nr_1]);
                $stmt->execute();
                $stmt->bind_result($zdjecie, $tytul, $cena_netto, $podatek_vat);
                $stmt->fetch();
                $stmt->close();
                $conn->close();
                $price =  $cena_netto + $cena_netto * $podatek_vat / 100;
                $total_price_product =  $price * $_SESSION[$nr_2];
                $total_price += $total_price_product;

                echo '
                    <tr>
                        <td><img src="' . htmlspecialchars($zdjecie) . '" style="max-height: 500px; max-width: 50px;"></td>
                        <td>' . htmlspecialchars($tytul) . '</td>
                        <td>' . $price . '</td>
                        <td>' . $_SESSION[$nr_2] . '</td>
                        <td>' . $total_price_product . '</td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="nr" value="' . $_SESSION[$nr_0] . '">
                                <label for="update_ilosc">Ilość:</label>
                                <input type="number" id="update_ilosc" name="update_ilosc">
                            </form>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="nr" value="' . $_SESSION[$nr_0] . '">
                                <input type="submit" name="remove_product" value="Usuń" class="btn btn-danger btn-sm">
                            </form>
                        </td>
                        
                    </tr>
                ';
            }
            // zmienne do następnej iteracji
            $nr++;
        }
    }
    echo '
                                </tbody>
                            </table>
                            <form method="post">
                                <div align="right">'; if ($total_price != 0) {echo "suma: " . $total_price;} echo' <input type="submit" name="pay" value="Zapłać" class="btn btn-success btn-sm"> </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
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
    <title>Strona główna</title>
</head>

<body>
<div class="navbar">
    <form method="get">
        <input type="submit" name="show_home_page" value="Główna" class="btn btn-success btn-sm">
        <input type="submit" name="show_cart" value="Koszyk" class="btn btn-success btn-sm">
    </form>
    <form method="post">
        <input type="submit" name="cookies" value="Cookies" class="btn btn-danger btn-sm">
    </form>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" &&isset($_POST['cookies'])) {
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach ($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time() - 1000);
            setcookie($name, '', time() - 1000, '/');
        }
    }
    echo '
        <form method="get">
            <label for="show_home_page">pomyśłnie usunięto cookies:</label>
            <input type="submit" name="show_home_page" value="Główna" class="btn btn-success btn-sm">
        </form>
    ';
}
elseif ($_SERVER["REQUEST_METHOD"] == "GET" &&isset($_GET['show_cart'])) {
    ShowCard();
}
elseif ($_SERVER["REQUEST_METHOD"] == "GET" &&isset($_GET['show_home_page'])) {
    ListaProduktow();
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST" &&isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    AddToCard($id);
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST" &&isset($_POST['pay'])) {
    Pay();
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST" &&isset($_POST['update_ilosc'])) {
    $nr = $_POST['nr'];
    $nr_1 = $nr . '_1';
    $nr_2 = $nr . '_2';

    $conn = connect();
    $query = "SELECT * FROM produkty WHERE id='$_SESSION[$nr_1]' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    if ($_POST['update_ilosc'] <= $row["ilosc_dostepnych_sztuk"]) {
        $_SESSION[$nr_2] = $_POST['update_ilosc'];
        echo '
        <form method="get">
            <label for="show_home_page">Pomyślnie zmieniono ilość:</label>
            <input type="submit" name="show_cart" value="Wróć do koszyka" class="btn btn-success btn-sm">
        </form>
    ';
    } else {
        echo '
        <form method="get">
            <label for="show_home_page">Zbyt duża ilość produktów:</label>
            <input type="submit" name="show_cart" value="Wróć do koszyka" class="btn btn-success btn-sm">
        </form>
    ';
    }

}
elseif ($_SERVER["REQUEST_METHOD"] == "POST" &&isset($_POST['remove_product'])) {
    $nr = $_POST['nr'];
    RemoveFromCard($nr);
    echo '
        <form method="get">
            <label for="show_home_page">Pomyślnie usunięto produkt:</label>
            <input type="submit" name="show_cart" value="Wróć do koszyka" class="btn btn-success btn-sm">
        </form>
    ';
}
?>
</body>
</html>
