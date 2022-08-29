<?php
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
    die();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header("Location:../logout");
    die();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stampÍ
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