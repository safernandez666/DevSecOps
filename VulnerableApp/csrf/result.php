<?php
    if (isset($_COOKIE['session'] )&& $_COOKIE['session'] == 'random')
        $result = 'Transfer success';
    else {
        $result = "You're not logged in";
        http_response_code(400);
    }
?>

<html>
	<head>
        <title>Stupid security</title>
    </head>
    <body>
        <?= $result ?>
    </body>
</html>