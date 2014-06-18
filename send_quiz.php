<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');

    $filequiz = "quiz_question_".date('y-m-d', time()).".csv";
    // create quiz from csv file
    $quiz = fopen($filequiz,"r");

    if ($quiz == null) {
        echo "No quiz file for today";    
    }
    else {
        $a = fgetcsv($quiz, 0, ";");
        fclose($quiz);
        $quiz_question = $a[0];
    }

    // get numbers and end time from .csv file
    $a=array();
    $file = fopen("quiz_numbers.csv","r");
    while(! feof($file)) {
        array_push($a, fgetcsv($file));
    }
    fclose($file);

    $arrlength=count($a);

    // cycle through array from csv and send message
    foreach($a as $v){
        $number = $v[0];
        $end = strtotime($v[1]);
    
        if ((time() < $end)){
            $sms = $client->account->messages->
                sendMessage(
                    "267-296-4099", 
                    $number,
                    $quiz_question
                );
            echo "Sent quiz to ";
            echo $number;
            echo " at ";
            echo time();
            echo "\n";
        }
        else {
            echo "Not sent to ".$number."\n";
        }
    }