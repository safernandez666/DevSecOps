<?php
$name = exec('cat /etc/hostname'); // Obtiene el nombre de Servidor
?>
<html>
	<head>
        <title>Stupid security</title>
        <script>
            function add
        </script>
    </head>
    <body  onload="myFunction()">
        <ul>
	    <h1>El Servidor Docker, que atiende,  se llama: <?php echo "<h1>$name</h>";?></h1> 
            <li><a href="sqli_xss/">SQL Injection and Reflected XSS attack</a></li>
            <li><a href="lfi/">Local file inclusion</a></li>
            <li><a href="csrf/">Cross site request forgery</a></li>
            <li><a href="upload/">Unrestricted file upload</a></li>
            <li><a href="sxss/">Stored XSS attack</a></li>
            <li><a id='new'>Click me</a></li>
            <li id="new2"></li>
            <li id="new3"></li>
        </ul>
        <button type="button" onclick="go()">Click me!</button>
        <p id="demo"></p>
        <script>
            function go() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("demo").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "ajax.php?name=" + makeid(), true);
                xhttp.send();
            }
            function makeid() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for (var i = 0; i < 5; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                return text;
            }
            function myFunction() {
                document.getElementById('new').setAttribute('href', 'hiddenlink.php');
                document.getElementById('new2').innerHTML = '<a href="hidden2.php">hello</a>';
                document.getElementById('new3').innerHTML = '<a '+'hr'+'ef'+'="'+'hidden3.php'+'">'+'hello again'+'<'+'/'+'a'+'>';
            }
        </script>
    </body>
</html>
