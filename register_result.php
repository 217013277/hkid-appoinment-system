<html>
<head>
<title>Register Result</title>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "root", "", "test");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// Get user input from the form submitted before
$id = $_POST["id"]; 
$pwd = $_POST["pwd"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;
  
if($allDataCorrect)
{
    // Search user table to see whether user name is exist
    $search_sql = $conn->prepare("SELECT * FROM userhash WHERE id LIKE ?");
    $search_sql->bind_param("s", $id);
    $search_sql->execute();
    $search_sql->store_result();
    
    // If login name can be found in table "user", forbid user register process
    if($search_sql->num_rows > 0) 
    {
        echo "<h2>The user name is registered by others. Please use other user name</h2>";
    }
    else
    {
        $salt = generateSalt(16);
        
        $hash = hash("sha512", $salt . $pwd);
        
        $insert_sql = $conn->prepare("INSERT INTO userhash (id, salt, hash) VALUES (?, ?, ?)");
        $insert_sql->bind_param("sss", $id, $salt, $pwd);
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