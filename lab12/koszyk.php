<?php
include('cfg.php');
if (!isset($_SESSION['count']))
{
    $_SESSION['count'] = 1;
} else {
    $_SESSION['count']++;
}



function addToCard($id_prod){
    $nr = $_SESSION['count'];

    $prod[$nr]['id_prod'] = $id_prod;
    $prod[$nr]['data'] = time();

    $nr_0=$nr. '_0';
    $nr_1=$nr. '_1';
    $nr_2=$nr. '_2';

    $_SESSION[$nr_0] = $nr;
    $_SESSION[$nr_1] = $prod[$nr]['id_prod'];
    $_SESSION[$nr_2] = $prod[$nr]['data'];
}

function removeFromCard(){
//    $_SESSION[$nr_0] = $nr;
//    $_SESSION[$nr_1] = $prod[$nr]['id_prod'];
//    $_SESSION[$nr_2] = $prod[$nr]['ile_sztuk'];
//    $_SESSION[$nr_3] = $prod[$nr]['wielkosc'];
//    $_SESSION[$nr_4] = $prod[$nr]['data'];
}

function showCard(){
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
                                        <th>Nazwa</th>
                                        <th>Zdjęcie</th>
                                    </tr>
                                </thead>
                                <tbody>';
        $nr = $_SESSION['count'];
        $nr_0=$nr. '_0';
        if (isset($_SESSION[$nr_0])){
            echo $_SESSION[$nr_0];
            $conn = connect();
            $query = "SELECT id, tytul, opis, zdjecie FROM produkty WHERE id='.$_SESSION[$nr_0].' ORDER BY id DESC LIMIT 100";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stmt->bind_result($id, $tytul, $opis, $zdjecie);

            while ($stmt->fetch()) {
                echo '
            <form method="POST">
                <tr>
                    <td>' . htmlspecialchars($tytul) . '</td>
                    <td><img src="' . $zdjecie . '" max-height="500px" max-width="50px"></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="' . $id . '">
                            <input type="submit" name="increse_value" value="zmień ilość" class="btn btn-primary btn-sm">
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
                        <form method="post">
                            <input type="submit" name="pay" value="zapłac" class="btn btn-success btn-sm">
                        </form>
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
    <title>Koszyk</title>
</head>

<body>
<div class="navbar">
    <a href="sklep.php">Home</a>
    <a href="koszyk.php">Koszyk</a>
</div>
<?php
ShowCard();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete']))
?>
</body>
</html>
