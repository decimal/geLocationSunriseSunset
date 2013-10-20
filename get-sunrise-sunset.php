<?php 
$time = $_GET['time'];
// $time = time();
$longitude = $_GET['longitude'];
$latitude = $_GET['latitude'];
$gmtOffset = $_GET['gmtOffset'];


$zenith = 90+(50/60);
$sunrise = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);
$sunset = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);

$zenith = 96;
$civil_twilight_morning = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);
$civil_twilight_night = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);

$zenith = 102;
$nautical_twilight_morning = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);
$nautical_twilight_night = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);

$zenith = 108;
$astronomical_twilight_morning = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);
$astronomical_twilight_night = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, $zenith, $gmtOffset);


$results = array(
	"astronomicalTwilightMorning" => $astronomical_twilight_morning,
	"nauticalTwilightMorning" => $nautical_twilight_morning,
	"civilTwilightMorning" => $civil_twilight_morning,
	"sunrise" => $sunrise, 
	"sunset" => $sunset,
	"civilTwilightNight" => $civil_twilight_night,
	"nauticalTwilightNight" => $nautical_twilight_night,
	"astronomicalTwilightNight" => $astronomical_twilight_night
	);

//this var will hold a count of data errors in the $results array
$incorrectDataValues = 0;

//check that each value is an integer
foreach ($results as $value) {
	if (gettype($value) !== 'integer') {
		$incorrectDataValues++;
	}
}

header('Content-Type: application/json');
//if there are any incorrect values, respond with a success false obj
if ($incorrectDataValues > 0) {
	echo '{"failed":true}';
} else {
	echo json_encode($results);
}
?>