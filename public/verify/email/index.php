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
<title>Enter Captcha</title>
</head>
<body>
<form action="result.php" method="post">
<h1>Enter Captcha</h1>
<img src="../../../captcha/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'>
</p>
Captcha: <input type="text" name="captcha" />
<p>
<p>Can't read the image?
<a href='javascript: refreshCaptcha();'>click here</a>
to refresh</p>
<input name="submit" type="submit" value="submit">
</form>
</body>
<footer>
<a href="../">Go back to verify page</a>
</footer>
</html>

<script>
//Refresh Captcha
function refreshCaptcha(){
    var img = document.images['captcha_image'];
    img.src = img.src.substring(
		0,img.src.lastIndexOf("?")
		)+"?rand="+Math.random()*1000;
}
</script>