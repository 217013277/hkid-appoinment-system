<html>
<head>
<title>Login Result</title>
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

/* Get user input which name is "id" and "pwd" 
(assume the id and pwd has correct format) */
$username = $_POST["username"]; 
$password = $_POST["password"]; 

// (2a) Write prepare statements to retrieve the columns salt and hash with the corresponding user  
    $search_sql = $conn->prepare("SELECT username, salt, hash FROM userhash WHERE username=?");
    $search_sql->bind_param("s", $username);
    $search_sql->execute();
    $search_sql->store_result();

// If login name can be found in table "userhash"
if($search_sql->num_rows > 0) 
{       
    // (2b) Write a statement to generate a hash by using SHA512 algorithm and store it into variable $pwdhash (salt + password)
    $search_sql->bind_result($username_db, $salt_db, $hash_db);
    $search_sql-> fetch();
    
    $passwordHash = hash("sha512", $salt_db . $password);

    /* (2c)	Write a statement to compare the string content of $pwdhash and the hash value retrieved from database is equal (hint: use build in function strcmp) */
    
    if(strcmp($hash_db, $passwordHash) == 0)
    {
	   echo "<h2>Authentication success!</h2>";
    }
    else
    {
        echo "<h2>The password is wrong, authentication failed</h2>";
    }
}
else
{
	echo "<h2>User name not exist, authentication failed</h2>";
}

// Close connection
mysqli_close($conn);
?>
</body>
</html>

