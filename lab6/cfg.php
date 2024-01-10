<?php
    function connect()
    {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "moja_strona164454";

        $conn = mysqli_connect($hostname, $username, $password, $dbname);
        //checking if connection is working or not
        if(!$conn)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        return $conn;
    }
?>

