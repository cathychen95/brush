<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');


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

    $answer = True;

    $correct_answers = True;

    // cycle through array from csv and send message
    for($x=0;$x<$arrlength;$x++){

        $number = $a[$x][0];
        $end = strtotime($a[$x][1]);

        if (time() < $end){

            $sms = $client->account->messages->

                sendMessage(

                // Twilio account's phone number
                "215-600-2133", 
 
                // number receiving text
                $number,
 
                // QUIZ QUESTION
                "True of False. Cavities (caries) affect children only, not adults. Text us back with the correct answer before midnight and earn $10."
                );


        }

        echo "Sent text to ";
        echo $number;
        echo " to ";
        echo date('d-m-y', $end);
        echo "</br>";
    }

    // $testtime = strtotime("10:40pm");

    // if ($testtime + 100 < time()) {
    //     echo $testtime + 100;
    // }

    // echo "</br>";
    // echo $time_1 + 120;
    // echo "</br>";
    // echo $time_1 - 120;
    // echo "</br>";
    // echo time();


    // if (time() < ($time_1 + 1200) && time() > ($time_1 - 1200)){
    //     echo "SUCCESS";
    // }