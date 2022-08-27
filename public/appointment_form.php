<?php
    date_default_timezone_set('Asia/Hong_Kong');
    $tomorrow = date('Y-m-d h:i:s', strtotime($stop_date . ' +1 day'));
    $enddate = date('Y-m-d h:i:s', strtotime($stop_date . ' +90 day'));
    $Data = SplitDate($tomorrow, $enddate, "Y-m-d");

    $startTime = date('Y-m-d H:i:s',strtotime('today 8am'));
    $endTime = date('Y-m-d H:i:s',strtotime('today 8pm'));
    $Time = SplitDate($startTime, $endTime, "H:i", "30"); 
    
    
?>

<html>
<head>
<title>Appointment Form</title>
</head>
<body>
<form action="appointment_result.php" method="post">
<h1>appointment</h1>
Email: <input name="email" type="text" size="30" maxlength="100"><br><br>
Password: <input name="password" type="text" size="30" maxlength="100"><br><br>
Date: <select name="date">
    <?php
    foreach($Data as $key => $value)
    {
        echo '<option value="'.$value.'">'.$value.'</option>';
    }
    ?>
</select>
Time: <select name="time">>
    <?php
    foreach($Time as $key => $value)
    {
        echo '<option value="'.$value.'">'.$value.'</option>';
    }
    ?>
</select><br><br>
Location: <select name="location">
	<option value="East Kowloon">East Kowloon</option>
	<option value="Hong Kong Island">Hong Kong Island</option>
	<option value="Sha Ti">Sha Tin</option>
    <option value="Sheung Shui">Sheung Shui</option>
    <option value="Tseung Kwan O">Tseung Kwan O</option>
    <option value="Tsuen Wan">Tsuen Wan</option>
    <option value="Tuen Mun">Tuen Mun</option>
    <option value="West Kowloon">West Kowloon</option>
    <option value="Yuen Long">Yuen Long</option>
</select><br><br>

<input name="submit" type="submit" value="submit">
</form>
</body>
</html>

<?php
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