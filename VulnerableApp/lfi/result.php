<?php
    if (!array_key_exists('name', $_GET) || $_GET['name'] == null)
        $_GET['name'] = 'default.php'
    ?>

<html>
	<head>
        <title>Stupid security</title>
    </head>
    <body>
        Content of file:
        <div style="border: 1px solid #000">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/lfi/'.$_GET['name']; ?>
        </div>
    </body>
</html>