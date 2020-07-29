<?php
    $arr = scandir('uploads/');
?>

<html>
	<head>
        <title>Stupid security</title>
    </head>
    <body>
    	<form action="result.php" method='POST' enctype="multipart/form-data">
    		Upload file:
    		<input name='fileToUpload' type='file'>
    		<input type='submit'>
    	</form>
        <p>All files:</p>
        <?php
        foreach ($arr as $file) {
            echo '<p><a href="uploads/'.$file.'">'.$file.'</a></p>';
        }
        ?>
    </body>
</html>