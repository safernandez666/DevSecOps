<html>
	<head>
        <title>Stupid security</title>
    </head>
    <body>
    	<form action='result.php'>
    		Search title:
    		<input name='title'>
    		<input type='submit'>
    	</form>
        <p>Result</p>
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

            $sql = "SELECT id, title FROM book WHERE title like '%" . $_GET['title'] . "%'";
            //$sql = "SELECT id, title FROM book";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                    </tr>
                <?php
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['title']?></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
            } else {
                echo "Cannot find book with title ".$_GET['title'];
                echo $conn->error;
            }
            $conn->close();
        ?>
    </body>
</html>