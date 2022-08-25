<html>
<head>
<title>Login Result</title>
</head>
<body>
<?php

include "config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

/* Get user input which name is "id" and "pwd" 
(assume the id and pwd has correct format) */
$username = $_POST["username"]; 
$password = $_POST["password"]; 

// save login attempt to prevent brute force attack
$ip = $_SERVER["REMOTE_ADDR"];
$action = "login";
$insert_log_sql = $conn->prepare("INSERT INTO `AuthLog` (`address`,`username`,`action`,`timestamp`)VALUES (?,?,?,now())");
$insert_log_sql->bind_param("sss", $ip, $username, $action);
$insert_log_sql->execute();

// check login attempt
$search_log_sql = $conn->prepare("SELECT COUNT(*) FROM `AuthLog` WHERE `username`=? AND `action`= '$action' AND `timestamp` > (now() - interval 5 minute)");
$search_log_sql->bind_param("s", $username);
$search_log_sql->execute();
$search_log_sql->store_result();
$search_log_sql->bind_result($countLog);
$search_log_sql->fetch();

if($countLog > 10)
{
  echo "<h2>Too many failed login attempts, please try again later.</h2>";
}
else
{
    // Write prepare statements to retrieve the columns salt and hash with the corresponding user  
    $search_sql = $conn->prepare("SELECT `salt`, `hash` FROM Users WHERE username=?");
    $search_sql->bind_param("s", $username);
    $search_sql->execute();
    $search_sql->store_result();

    // If login name can be found in table "userhash"
    if($search_sql->num_rows < 1) 
    {       
        echo "<h2>Username not exist, authentication failed</h2>";
    }
    else
    {
        // Write a statement to generate a hash by using SHA512 algorithm and store it into variable $pwdhash (salt + password)
        $search_sql->bind_result($salt_db, $hash_db);
        $search_sql-> fetch();

        $passwordHash = hash("sha512", $salt_db . $password);

        $test = "abc123**";
        $passwordHashTest = hash("sha512", $salt_db . $test);

        echo "
        <p>password: $password</p>
        <p>hash from dn: $hash_db</p>
        <p>salt from dn: $salt_db</p>
        <p>password hash: $passwordHash</p><br>
        
        <p>$passwordHashTest</p>";

        /* Write a statement to compare the string content of $pwdhash and the hash value retrieved from database is equal (hint: use build in function strcmp) */

        if(strcmp($hash_db, $passwordHash) != 0)
        {
            echo "<h2>The password is wrong, authentication failed</h2>";
        }
        else
        {
            date_default_timezone_set('Asia/Hong_Kong');
            ?>
            
            <h2>Authentication success!</h2>

            <form action="register_result.php" method="post">
            Date: <select name="date">
            <option value=""></option>
            <?php
            $tomorrow = date('Y-m-d h:i:s', strtotime($stop_date . ' +1 day'));
            $enddate = date('Y-m-d h:i:s', strtotime($stop_date . ' +90 day'));
            $Data = SplitDate($tomorrow, $enddate, "Y-m-d");
            foreach($Data as $key => $value)
            {
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
            ?>
            </select>
            Time: <select name="time">
            <option value=""></option>
            <?php
            $startTime = date('Y-m-d H:i:s',strtotime('today 8am'));
            $endTime = date('Y-m-d H:i:s',strtotime('today 8pm'));
            $Time = SplitDate($startTime, $endTime, "H:i", "30");
            foreach($Time as $key => $value)
            {
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
            ?>
            </select>
            <input name="submit" type="submit" value="submit">
            </form>
<?php
        }
    }
}

// Close connection
mysqli_close($conn);

// Generate time slot
function SplitDate($StartTime, $EndTime, $Format, $Duration="1440"){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp

    $AddMins  = $Duration * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ($Format, $StartTime);
        // $StartTime += $AddMins; //Endtime check
        $StartTime += $AddMins; //Endtime check
    }
    return $ReturnArray;   
}

?>
</body>
</html>

