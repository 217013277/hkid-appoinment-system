<?php
session_start(); 
if(isset($_SESSION['user']))
{
    header("Location:../appointment_form.php"); 
}
?>
<html>
<head>
<title>Register Form</title>
</head>
<body>
<form action="result.php" method="post">
<h1>Register</h1>
Email: <input name="email" type="text" size="30" maxlength="100"><br><br>
Password: <input name="password" type="text" size="30" maxlength="100"><br>At least 8 alphanumeric characters, 1 uppercase letter, 1 lowercase letter and 1 @$!%*#?&-_ symbol<br><br>
English Name: <input name="engName" type="text" size="30" maxlength="100"> At least 2 English characters start with capital letter<br><br>
Chinese Name: <input name="chiName" type="text" size="30" maxlength="100"> At least 2 Chinese characters<br><br>
Gender: <input name="gender" type="text" size="30" maxlength="12"> Male or Female<br><br>
Date of Birth: <input name="dateOfBirth" type="date" size="30"><br><br>
Address: <input name="address" type="text" size="100" maxlength="100"><br><br>
Place of Birth: <input name="placeOfBirth" type="text" size="30" maxlength="100"><br><br>
Occupation: <input name="occupation" type="text" size="30" maxlength="100"><br><br>
HKID: <input name="hkid" type="text" size="30" maxlength="100"> e.g. A123456(7)<br><br>
<img src="../../captcha/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'>
</p>
Captcha: <input type="text" name="captcha" />
<p>
<p>Can't read the image?
<a href='javascript: refreshCaptcha();'>click here</a>
to refresh</p>
<input name="submit" type="submit" value="submit">
</form>
<a href="../login">Go to login page</a>
</body>
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
