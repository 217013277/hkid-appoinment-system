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
$Data = SplitTime("2018-05-12 12:15", "2018-05-12 15:30", "30");
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

$query = "SELECT `Time` FROM `Appointments`";
$result = $conn->query($query);

$items = array();
while($row = mysqli_fetch_array($result, MYSQLI_NUM))
{
    echo ($row[0]."\n");  // The number
    $items[] = $row[0];

}

mysqli_close($conn);

echo "<pre>";
print_r($items);
echo "<pre>";


if (in_array("2022-08-23 08:16:14", $items)) {
    echo "Got Irix";
}
else
{
    echo "no Irix";
}

?>