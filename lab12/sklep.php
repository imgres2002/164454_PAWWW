<?php
include('cfg.php');

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
                                        <th>opis</th>
                                        <th>zdjecie</th>
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
            <form method="POST">
                <tr>
                    <td>' . htmlspecialchars($tytul) . '</td>
                    <td>' . htmlspecialchars($opis) . '</td>
                    <td><img src="' . $zdjecie . '" max-height="500px" max-width="50px"></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="' . $id . '">
                            <input type="submit" name="add_to_cart" value="dodaj do koszyka" class="btn btn-primary btn-sm">
                        </form>
                    </td>
                </tr>
            </form>
            </form>
        ';
        }
    }
    echo '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    $conn->close();
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
    <a href="sklep.php">Home</a>
    <a href="koszyk.php">Koszyk</a>
</div>
<?php
ListaProduktow();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    addToCard($id);
}
?>
</body>
</html>