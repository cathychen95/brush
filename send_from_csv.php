<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // get current weather data
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

    // create empty array
    $a=array();
    // open the csv file, located in the same folder
    $file = fopen("numbers.csv","r");

    // traverse through each line of file
    while(! feof($file))
    {
        // add every person's data to a
        array_push($a, fgetcsv($file));
    }
    fclose($file);

    $arrlength=count($a);
    for($x=0;$x<$arrlength;$x++)
    {
        $number = $a[$x][0];
        $name = $a[$x][1];

        $sms = $client->account->messages->sendMessage(
 
            // Twilio account's phone number
            "215-600-2133", 
 
            // number receiving text
            $number,
 
            // the sms body
            "It is time to brush your teeth for 2 minutes. Weather today $currenttemp F $currentcondition, tomorrow $forecasttemp F $forecastcondition ."
        );

        echo "Sent text to ";
        echo $name;
        echo " at ";
        echo $number;
        echo "<br>";
    }