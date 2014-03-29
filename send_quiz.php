<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');

    // create quiz from csv file
    $quiz = new SplFileObject("current_quiz.csv");
    $quiz->setFlags(SplFileObject::READ_CSV);
    $quiz->setCsvControl(';');

    // retrieve quiz question
    $quiz_question = null;
    foreach ($quiz as $v) {
        list ($q, $a, $d) = $v;
        $quiz_question = $q;
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
        if (time() < $end){
            $sms = $client->account->messages->
                sendMessage(
                    "215-600-2133", 
                    $number,
                    $quiz_question
                );
        }

        echo "Sent quiz to ";
        echo $number;
        echo " to ";
        echo date('d-m-y', $end);
        echo " with question ";
        echo $quiz_question;
        echo "</br>";
    }