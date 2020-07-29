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

    $sql = "INSERT INTO post(text) VALUES (\"".$_POST['post']."\")";
    $conn->query($sql);
    $conn->close();

    header('Location:'.'index.php')
?>