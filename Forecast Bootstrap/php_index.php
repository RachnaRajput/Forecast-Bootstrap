 <?php
   $addr= $_POST["address"];
   $cit= $_POST["city"];
   $state_name= $_POST["state"];
   $unit = $_POST["degree"];
      
$array = Array($addr, $cit, $state_name);
$complete = join (',',$array);
//echo $complete. "<br>";
$complete = urlencode($complete);

//$request_url = "https://maps.google.com/maps/api/geocode/xml?address={$complete}";
$request_url = "https://maps.google.com/maps/api/geocode/xml?address={$complete}&key=AIzaSyAKo-narLstwWww0Nh7NreRp2jAKDZUOPA";

$parsed_url = parse_url($request_url);
//print_r($parsed_url);
//echo "<br>";
//echo "https://maps.google.com/maps/api/geocode/xml?address={$complete}&key=AIzaSyAKo-narLstwWww0Nh7NreRp2jAKDZUOPA";
$xml = new DOMDocument();
$xml = simplexml_load_file($request_url) or die("feed not loading");

//print $xml->saveXML();
//echo "<br>";

$lat = $xml['results'][0]['geometry']['location']['lat'];
$long = $xml['results'][0]['geometry']['location']['lng'];
//$lati= $xml->result->geometry->location->lat. "<br>";
//$longi= $xml->result->geometry->location->lng. "<br>";
$lati= $xml->result->geometry->location->lat or die("latitude not found");
//"<br>";
$longi= $xml->result->geometry->location->lng or die("longitude not found");
//"<br>";
//echo $lat;
//print "lng: " . $xml->result->geometry->location->lng. "<br>";
//print "lat: " . $xml->result->geometry->location->lat. "<br>";


//https://api.forecast.io/forecast/YOUR_APIKEY/LATITUDE,LONGITUDE?units=units_value&exclude=flags
$request_url1 = "https://api.forecast.io/forecast/f544b15e9df2da153c92691a80bc49cc/$lati,$longi?units=$unit&exclude=flag";
//echo "https://api.forecast.io/forecast/f544b15e9df2da153c92691a80bc49cc/{$lati},{$longi}?,units={$unit}&exclude=flag <br>";
//echo "<br>https://api.forecast.io/forecast/f544b15e9df2da153c92691a80bc49cc/$lati,$longi?units=$unit&exclude=flag";
//echo "<br>$request_url1<br>";
$parsed_url1 = parse_url($request_url1);
//print_r($parsed_url1);


 
$resp_json = file_get_contents($request_url1);
$resp1 = json_decode($resp_json, true);
 
echo "<br><pre>";
print_r($resp1);
echo "</pre>";

// $lati = $resp['results'][0]['geometry']['location']['lat'];
$sum = $resp1['currently']['summary'];
//echo "summary: $sum<br>";
$temp = $resp1['currently']['temperature'];
    $temp_int= intval($temp);
//if($unit == 'us')
  // echo "Temperature: $temp F<br>";
//else
  //  echo"Temperature: $temp C<br>";
$ico = $resp1['currently']['icon'];
        switch($ico)
        {
    case "clear-day" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/clear.png" title ="Clear-day"></html>';
        break;
    case "clear-night" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/clear_night.png" title= "Clear-night"> <alt = "Clear Night"> </html>';
        break;
    case "rain" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/rain.png" title= "Rain"> <alt = "Rain"> </html>';
        break;
    case "snow" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/snow.png" title= "Snow"> <alt = "Snow"> </html>';
        break;
    case "sleet" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/sleet.png" title= "Sleet"> <alt = "Sleet"> </html>';
        break;
    case "wind" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/wind.png" title= "Wind"> <alt = "Wind"> </html>';
        break;
    case "fog" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/fog.png" title= "Fog"> <alt = "Fog"> </html>';
        break;
        case "cloudy" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/cloudy.png" title= "clear"> <alt = "Cloudy"> </html>';
        break;  
    case "partly-cloudy-day" :echo' <html> <img src= "http://cs-server.usc.edu:45678/hw/hw6/images/cloud_day.png"> <alt = "Cloud Day"></html>';
        break;
    case "partly-cloudy-night" : echo '<html><img src= "http://cs-server.usc.edu:45678/hw/hw6/images/cloud_night.png" title= "partly-cloudy-night"> <alt = "Cloud Night"></html>';
        break;
        
    default:
        echo "wrong data";
}
            



$preci = $resp1['currently']['precipIntensity'];
$preci1 = $preci/25.4;
   if($unit == 'us')
   {
if($preci >="0" && $preci<"0.002")
    echo "none<br>";
elseif($preci >="0.002" && $preci<"0.017")
    echo "Very Light<br>";
elseif($preci >="0.017" && $preci<"0.1")
    echo "Light<br>";
elseif($preci >="0.1" && $preci<"0.4")
    echo "Moderate<br>";
elseif($preci >="0.4")
    echo "Heavy<br>";
else
    echo "Wrong data";
}
   else
   // echo "divide by 25.4";
   {
    
 if($preci1 >="0" && $preci1<"0.002")
    echo "none<br>";
elseif($preci1 >="0.002" && $preci1<"0.017")
    echo "Very Light<br>";
elseif($preci1 >="0.017" && $preci1<"0.1")
    echo "Light<br>";
elseif($preci1 >="0.1" && $preci1<"0.4")
    echo "Moderate<br>";
elseif($preci1 >="0.4")
    echo "Heavy<br>";
else
    echo "Wrong data";
    }


    
$chance_of_rain = $resp1['currently']['precipProbability'];
//echo "precipProbability: ";
$rain_percent = $chance_of_rain *100;
//echo "$rain_percent%<br>";
$wind_speed = $resp1['currently']['windSpeed'];
//echo "Wind:";
$wind_speed_int= intval($wind_speed);
//echo "$wind_speed_int mph <br>";
$dew = $resp1['currently']['dewPoint'];
//echo "dewPoint: ";
$dew_int= intval($dew);
//echo "$dew_int<br>";
$hum = $resp1['currently']['humidity'];
$hum_percent= $hum*100;

    if(isset ($resp1['currently']['visibility']))
 {
$visi = $resp1['currently']['visibility'];
$visi_int = intval($visi);
 }
       else
       $visi_int = "NA";


$timezone = $resp1['timezone']; 
$sunrise = $resp1['daily']['data'][0]['sunriseTime'];
date_default_timezone_set($timezone);
$sunrise_convert = date('h:i A', $sunrise);


$sunset = $resp1['daily']['data'][0]['sunsetTime'];
date_default_timezone_set($timezone);
$sunset_convert = date('h:i A',$sunset);

?>

