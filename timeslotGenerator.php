<?php

function SplitTime($StartTime, $EndTime, $Duration="60"){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp

    $AddMins  = $Duration * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ("Y-m-d H:i", $StartTime);
        $StartTime += $AddMins; //Endtime check
    }
    return $ReturnArray;
}

//Calling the function
$Data = SplitTime("2018-05-12 12:15", "2018-05-12 15:30", "60");
echo "<pre>";
print_r($Data);
echo "<pre>";

?>

<form>
<select name="country">
<option value=""></option>
<?php

foreach($Data as $key => $value)
{
    
    echo '<option value="'.$key.'">'.$value.'</option>';
}
?>
</select>
</form>

<?php
include "config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// $search_sql = $conn->prepare("SELECT `Time` FROM `Appointments`");
// // $result = mysqli_query($db, $search_sql);
// $search_sql->execute();
// $search_sql->store_result();

$query = "SELECT `Time` FROM `Appointments`";
$result = $conn->query($query);


// $row = $result->fetch_array(MYSQLI_ASSOC);
// while($row = $result->fetch_array(MYSQLI_ASSOC))
// {
//     printf("%s (%s)\n", $row['Time']);
// }

$items = array();
while($row = mysqli_fetch_array($result, MYSQLI_NUM))
{
    echo ($row[0]."\n");  // The number
    $items[] = $row[0];

}

echo "<pre>";
print_r($items);
echo "<pre>";

// $search_sql->bind_result($time_db);
// $search_sql->fetch();

// // $search_sql->fetch_assoc();

// // while ($row = mysql_fetch_assoc($search_sql)) {
// //     echo $row["Time"];
// // }

// echo "<p>$time_db</p>";

// while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
// {
//     echo ($row['Time']);  // The number
//     // echo ($row[1]);  // The customer
// }

echo "abc";

mysqli_close($conn);

?>