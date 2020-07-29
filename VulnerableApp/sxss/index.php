<?php
    $posts = '<ul>';
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

    $sql = "SELECT id, text FROM post";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //$posts .= '<li>'.$row['text'].'<form method="post" action="delete.php"><input name="id" type="hidden" value="'.$row['id'].'"><input type="submit" value="-" class="link-button"></form></li>';
            $posts .= '<li>'.$row['text'].'</li>';
        }
    }
    $posts .= '</ul>';
    $conn->close();
?>

<html>
    <head>
        <title>Stupid security</title>
        <style>
        .inline {
          display: inline;
        }

        .link-button {
          background: none;
          border: none;
          color: blue;
          text-decoration: underline;
          cursor: pointer;
          font-size: 1em;
          font-family: serif;
        }
        .link-button:focus {
          outline: none;
        }
        .link-button:active {
          color:red;
        }
        </style>
    </head>
    <body>
        <form action="result.php" method='POST'>
            Post something!
            <input name='post'>
            <input type='submit'>
        </form>
        <?= $posts ?>
    </body>
</html>