<html>
<head>
<title>Register Result</title>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "root", "mysql", "test");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// Get user input from the form submitted before
$username = $_POST["username"]; 
$password = $_POST["password"];
$email = $_POST["email"];
$engName = $_POST["engName"];
$chiName = $_POST["chiName"];
$gender = $_POST["gender"];
$dateOfBirth = $_POST["dateOfBirth"];
$address = $_POST["address"];
$placeOfBirth = $_POST["placeOfBirth"];
$occupation = $_POST["occupation"];
$hkid = $_POST["hkid"];

// Set role id for enduser
$roleId = 123;

// Set a flag to assume all user input follow the format
$allDataCorrect = true;

// Check all data whether follow the format
if(!preg_match("/^[a-zA-Z0-9]{6,12}$/", $username))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Username should be composed within 6 to 12 alphanumeric characters <br><br>"; 
}

if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-_])[A-Za-z\d@$!%*#?&-_]{8,}$/", $password))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Password should be composed with at least 8 alphanumeric characters, 1 uppercase letter, 1 lowercase letter and 1 @$!%*#?&-_ symbol <br><br>"; 
}

if(!preg_match("/\w+@[a-zA-Z0-9_]+?\.[a-zA-Z]{2,6}/", $email))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Email is invalid<br><br>"; 
}

if(!preg_match("/[A-Z][a-zA-Z]{2,}/", $engName))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with English characters start with capital letter<br><br>"; 
}

if(!preg_match("/\p{Han}{2,}+/u", $chiName))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with at least 2 Chinese characters <br><br>"; 
}

if(!preg_match("/^(?:Male|Female)$/", $gender))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with at least 2 Chinese characters <br><br>"; 
}

if(!preg_match("/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $dateOfBirth))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Please input valid date of birth<br><br>"; 

}

if(!preg_match("/^[A-Z]{1,2}[0-9]{6}\([0-9A]\)$/", $hkid))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Please input valid hkid e.g. A123456(7)<br><br>"; 
}
  
if($allDataCorrect)
{
    // Search user table to see whether user name is exist
    $search_sql = $conn->prepare("SELECT username FROM Users WHERE Username LIKE ? OR Email LIKE ? OR HKID LIKE ?");
    $search_sql->bind_param("sss", $username, $email, $hkid);
    $search_sql->execute();
    $search_sql->store_result();
    
    // If login name can be found in table "user", forbid user register process
    if($search_sql->num_rows > 0) 
    {
        echo "<h2>The username, email or hkid are registered by others. Please use other user name</h2>";
    }
    else
    {
        $search_sql->bind_result($hkid_db);
        $search_sql-> fetch();

        $salt = generateSalt(16);
    
        $hash = hash("sha512", $salt . $password);

        $dateOfBirth_dt=date('Y-m-d H:i:s',strtotime($dateOfBirth)); 

        $insert_sql = $conn->prepare("INSERT INTO Users (Username, Salt, Hash, Email, NameEnglish, NameChinese, Gender, Address, DateOfBirth, PlaceOfBirth, Occupation, HKID, RoleId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_sql->bind_param("ssssssssssssi", $username, $salt, $hash, $email, $engName, $chiName, $gender, $address, $dateOfBirth_dt, $placeOfBirth, $occupation, $hkid, $roleId);
        $insert_sql->execute();

        echo "<h2>Registration Success!!</h2>";
    }
}
else
{
    echo "<h3> $errMsg </h3>";
}
// Close connection
mysqli_close($conn);

// This function generate a random string with particular length
function generateSalt($length)
{
    $rand_str = "";
    $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    
    for ($i=0;$i <= $length; $i++) {
        $rand_str = $rand_str . $chars[rand(0, strlen($chars)-1)];
    }
    
    return $rand_str;
}
?>
<a href="register_form.php">Go back to register page</a>
<br><br>
<a href="login_form.php">Go to login page</a>
</body>
</html>