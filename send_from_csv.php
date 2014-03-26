<?php
 
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
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
            "Hi $name , your number is $number"
        );

        echo "Sent text to ";
        echo $name;
        echo " at ";
        echo $number;
        echo "<br>";
    }