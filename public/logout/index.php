<?php
session_start();
session_destroy();
?>
<html>
    <body>
        <p>You are logged out.</p>
        <a href="../register">Go to register page</a>
        <br><br>
        <a href="../login">Go to login page</a>
    </body>
</html>