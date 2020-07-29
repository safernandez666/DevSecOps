<?php
    $servername = "localhost";
    $username = "test";
    $password = "";
    $dbname = "stupid";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM post WHERE id = ".$_POST['id'].";";
    $conn->query($sql);
    $conn->close();

    header('Location:'.'index.php')
?>