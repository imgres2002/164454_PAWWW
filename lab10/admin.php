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

function ListaPodstron()
{
    $conn = connect();
    $query = "SELECT id, page_title FROM page_list ORDER BY id DESC LIMIT 100";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($id, $page_title);
    while ($stmt->fetch()) {
        echo '
        <table>
            <tr>
                <td>' . $id . '</td>
                <td>' . htmlspecialchars($page_title) . '</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="submit" name="update" value="edytuj"> 
                        <input type="submit" name="delete" value="usuń">
                    </form>
                </td>
            </tr>
        </table>
        ';
    }
    $conn->close();
}

function EdytujPodstrone($id)
{
    $conn = connect();
    $stmt = $conn->prepare("SELECT page_title, page_content, status FROM page_list WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($page_title, $page_content, $status);
    $stmt->fetch();
    echo '
    <table>
        <tr>
            <td>
                <form method="POST">
                    <input type="hidden" name="update_id" value="' . $id . '">
                    <label for="update_page_title">Tytuł:</label>
                    <input type="text" id="update_page_title" name="update_page_title" value="' . htmlspecialchars($page_title) . '">
                    <label for="update_page_content">Treść:</label>
                    <textarea id="update_page_content" name="update_page_content">' . htmlspecialchars($page_content). '</textarea>
                    <label for="update_status">Czy aktywna:</label>
                    <input type="checkbox" id="update_status" name="update_status" ' . ($status ? 'checked' : '') . ' > 
                    <input type="submit" name="submit_update" value="potwierdź">       
                </form>
            </td>
        </tr>
    </table>
    ';
    $conn->close();
}


function DodajNowaPodstrone()
{
    echo '
    <table>
        <tr>
            <td>
                <form action="" method="post">
                    <label for="add_page_title">Tytuł:</label>
                    <input type="text" id="add_page_title" name="add_page_title">
                    <label for="add_page_content">Treść:</label>
                    <textarea id="add_page_content" name="add_page_content"></textarea>
                    <label for="add_status">Czy aktywna:</label>
                    <input type="checkbox" id="add_status" name="add_status"> 
                    <input type="submit" name="submit_add" value="potwierdź">
                </form>
            </td>
        </tr>
    </table>
    ';
}

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
    <title>CMS</title>
</head>
<body>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    ListaPodstron();
    echo '
    <form method="post">
    <label for="add">Dodaj nową podstonę:</label>
    <input type="submit" name="add" value="dodaj">
    </form>
    ';

    if (isset($_POST['add'])) {
        DodajNowaPodstrone();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
        $id = $_POST['id'];
        UsunPodstrone($id);
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        EdytujPodstrone($id);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_add'])) {
        $page_title = $_POST['add_page_title'];
        $page_content = $_POST['add_page_content'];
        $status = isset($_POST["add_status"]) ? 1 : 0;
        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?) LIMIT 1");
        $stmt->bind_param('ssi', $page_title, $page_content, $status);
        $stmt->execute();
        $conn->close();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_update'])) {
        $id = $_POST['update_id'];
        $page_title = $_POST['update_page_title'];
        $page_content = $_POST['update_page_content'];
        $status = isset($_POST["update_status"]) ? 1 : 0;
        $conn = connect();
        $stmt = $conn->prepare("UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ? LIMIT 1");
        $stmt->bind_param('ssii', $page_title, $page_content, $status, $id);
        $stmt->execute();
        $conn->close();
    }
}
?>
</body>
</html>