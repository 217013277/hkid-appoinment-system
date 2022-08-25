<html>
<head>
<title>Register Result</title>
</head>
<body>

<?php

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

date_default_timezone_set('Asia/Hong_Kong');
$tomorrow = date('Y-m-d h:i:s', strtotime($stop_date . ' +1 day'));
$enddate = date('Y-m-d h:i:s', strtotime($stop_date . ' +90 day'));

$Data = SplitDate($tomorrow, $enddate, "Y-m-d");

$startTime = date('Y-m-d H:i:s',strtotime('today 8am'));
$endTime = date('Y-m-d H:i:s',strtotime('today 8pm'));

$Time = SplitDate($startTime, $endTime, "H:i", "30");
// echo "<pre>";
// print_r($Data);
// echo "<pre>";

?>

<form action="register_result.php" method="post">
Date: <select name="date">
<option value=""></option>
<?php
foreach($Data as $key => $value)
{
    echo '<option value="'.$key.'">'.$value.'</option>';
}
?>
</select>
Time: <select name="time">
<option value=""></option>
<?php
foreach($Time as $key => $value)
{
    echo '<option value="'.$key.'">'.$value.'</option>';
}
?>
</select>
<input name="submit" type="submit" value="submit">
</form>

<?php
// include "config.php";

// // Create connection
// $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// // Check connection
// if ($conn->connect_error) 
// {
//     die("Connection failed: ". $conn->connect_error);
// } 

// $query = "SELECT `Time` FROM `Appointments`";
// $result = $conn->query($query);

// $items = array();
// while($row = mysqli_fetch_array($result, MYSQLI_NUM))
// {
//     echo ($row[0]."\n");  // The number
//     $items[] = $row[0];

// }

// mysqli_close($conn);

// echo "<pre>";
// print_r($items);
// echo "<pre>";


// if (in_array("2022-08-23 08:16:14", $items)) {
//     echo "Got Irix";
// }
// else
// {
//     echo "no Irix";
// }
?>

</body>
</html>