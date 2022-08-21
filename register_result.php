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
$engName = $_POST["engName"];
$chiName = $_POST["chiName"];
$gender = $_POST["gender"];
$dateOfBirth = $_POST["dateOfBirth"];
$address = $_POST["address"];
$placeOfBirth = $_POST["placeOfBirth"];
$occupation = $_POST["occupation"];
$hkid = $_POST["hkid"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;
  
if($allDataCorrect)
{
    // Search user table to see whether user name is exist
    $search_sql = $conn->prepare("SELECT hkid FROM Users WHERE Username LIKE ?");
    $search_sql->bind_param("s", $username);
    $search_sql->execute();
    $search_sql->store_result();
    
    // If login name can be found in table "user", forbid user register process
    if($search_sql->num_rows > 0) 
    {
        echo "<h2>The username is registered by others. Please use other user name</h2>";
    }
    else
    {
        $search_sql->bind_result($hkid_db);
        $search_sql-> fetch();
        if($hkid_db == $hkid)
        {
            echo "<h2>Please confirm your HKID is correct</h2>";
        }
        else
        {
            $salt = generateSalt(16);
        
            $hash = hash("sha512", $salt . $password);

            $dateOfBirth_dt=date('Y-m-d H:i:s',strtotime($dateOfBirth));
            echo $dateOfBirth_dt;  

            $roleId = 123;

            $insert_sql = $conn->prepare("INSERT INTO Users (Username, Salt, Hash, NameEnglish, NameChinese, Gender, Address, DateOfBirth, PlaceOfBirth, Occupation, HKID, RoleId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_sql->bind_param("sssssssssssi", $username, $salt, $hash, $engName, $chiName, $gender, $address, $dateOfBirth_dt, $placeOfBirth, $occupation, $hkid, $roleId);

            if ($insert_sql === false)
            {
                die("bind param fail". $insert_sql->error);
            }
            
            $insert_sql->execute();

            if ($insert_sql === false)
            {
                die("execute fail". $insert_sql->error);
            }

            echo "<h2>Registration Success!!</h2>";
        }
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