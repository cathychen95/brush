<?php
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    date_default_timezone_set('America/New_York');

    $filequiz = "quiz_question_".date('y-m-d', time()).".csv";
    // create quiz from csv file
    $quiz = new SplFileObject($filequiz);
    $quiz->setFlags(SplFileObject::READ_CSV);
    $quiz->setCsvControl(';');

    // retrieve quiz question and date
    $quiz_question = null;
    $quiz_date = null;
    foreach ($quiz as $v) {
        list ($q, $a, $d) = $v;
        $quiz_question = $q;
        $quiz_date = date('Y-m-d', strtotime($d));
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

        if ((time() < $end) && (date('Y-m-d', time()) == $quiz_date)){
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
            echo "Not sent to a number";
        }

        /*
        echo "Sent quiz to ";
        echo $number;
        echo " to ";
        echo date('d-m-y', $end);
        echo " with question ";
        echo $quiz_question;
        echo "</br>";
        echo date('Y-m-d', time());
        echo "</br>";
        echo $quiz_date;
        echo "</br>";
        */
    }