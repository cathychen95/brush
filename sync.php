<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);

    // get numbers and end time from .csv file
    $a=array();
    $file = fopen("study_numbers.csv","r");
    while(! feof($file)) {
        array_push($a, fgetcsv($file));
    }
    fclose($file);

    // cycle through array from csv and send message
    foreach($a as $v){
        $number = $v[0];    
        $sms = $client->account->messages->
            sendMessage(
                "267-296-4099", 
                $number,
                "It is time to sync your beam brush with your phone. You must do this today! Thank you."
            );
        echo "Sent sync reminder to ".$number."\n";
    }