<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');



    $answer = strtoupper(True);

    $correct_answers = array("TRUE", "FALSE", "T", "F");

    $reply = null;

    foreach($correct_answers as $v) {
        if ($answer == $v) {
            $reply = "Correct! You have earned $10."
        }
        else {
            $reply = "Sorry try again next time."
        }
    }

    $number = 19178213080;
    $end = strtotime("00:00AM");

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