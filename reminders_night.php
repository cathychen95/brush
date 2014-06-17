<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');


    // get current weather data
    $result = file_get_contents('http://weather.yahooapis.com/forecastrss?p=19104&u=f');
    $xml = simplexml_load_string($result); 
    $xml->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');
    
    // weather variables
    $currenttemp = null;
    $currentcondition = null;
    $forecast = null;
    $forecasttemp = null;
    $forecastcondition = null;

    // populate weather variables using data from yahoo
    foreach($xml->channel->item as $item){

            $current = $item->xpath('yweather:condition');
            $currenttemp = $current[0]['temp'];
            $currentcondition = $current[0]['text'];
     
            $forecast = $item->xpath('yweather:forecast');
            // $forecastday = $forecast[1]['date'];
            $forecasttemp = strval((floatval($forecast[1]['high']) + floatval($forecast[1]['low']))/2.0);
            $forecastcondition = $forecast[1]['text'];
    }

    // create empty array
    $a=array();
    // open the csv file, located in the same folder
    $file = fopen("study_numbers.csv","r");

    // traverse through each line of file
    while(! feof($file))
    {
        // add every person's data to a
        array_push($a, fgetcsv($file));
    }
    fclose($file);

    $arrlength=count($a);

    //
    $curr_time=time();
    echo "Script started at " + $curr_time + "/n";

    // cycle through array from csv and send message
    for($x=0;$x<$arrlength;$x++){
        if (count($a[$x]) > 1) { // check for empty line
            $number = $a[$x][0];
            $begin = strtotime($a[$x][1]);
            $end = strtotime($a[$x][2]);
            $time_2 = strtotime($a[$x][4]);

            // forward timegap of 4:59 minutes
            $timegap = 299;

            if (((($curr_time <= ($time_2 + $timegap)) && ($curr_time >= $time_2))) &&
                 ($curr_time < $end && $curr_time > $begin)) {

                $sms = $client->account->messages->sendMessage(
     
                    // Twilio account's phone number
                    "215-600-2133", 
     
                    // number receiving text
                    $number,
     
                    // the sms body
                    "It is time to brush your teeth for 2 minutes. Weather now is $currenttemp F $currentcondition, tomorrow $forecasttemp F $forecastcondition ."
                );

                echo "\nSent morning reminder to ".$number;
            }

            else {
                echo "\nDid not send text to ".$number;
            }
        }
    }