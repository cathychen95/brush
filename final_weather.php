<?php
$result = file_get_contents('http://weather.yahooapis.com/forecastrss?p=19104&u=c');
$xml = simplexml_load_string($result);
 

 
$xml->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');
$location = $xml->channel->xpath('yweather:location');
 

$currenttemp = null;
$currentcondition = null;

$forecast = null;
$forecasttemp = null;
$forecastcondition = null;

foreach($xml->channel->item as $item){
        $current = $item->xpath('yweather:condition');
        $currenttemp = $current[0]['temp'];
        $currentcondition = $current[0]['text'];
 
        $forecast = $item->xpath('yweather:forecast');
        $forecasttemp = strval((floatval($forecast[0]['high']) + floatval($forecast[0]['low']))/2.0);
        $forecastcondition = $forecast[0]['text'];

}

echo $currenttemp;
echo $currentcondition;
echo $forecasttemp;
echo $forecastcondition;