<?php
    if (!isset($_COOKIE['session']))
        setcookie('session', 'random', 0, "/")
?>
<html>
	<head>
        <title>Stupid security - CSRF</title>
    </head>
    <body>
    	<form action="result.php" method='GET'>
    		<p>Enter recipient:</p>
    		<input name='name'>
    		<p>Enter amount:</p>
    		<input name='amount'>
    		<input type='submit'>
    	</form>
    </body>
</html>