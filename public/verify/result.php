<?php
//Prevent Direct URL Access to PHP Form Files
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
    die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
    }
    
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
}
?>
<html>
<head>
<title>Verify Email Result</title>
</head>
<body>

</form>
</body>
<footer>
<a href="../appointment">appointment</a>
<a href="../logout">logout</a>
</footer>
</html>