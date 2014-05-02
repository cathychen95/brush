<?php
    // set time zone
    date_default_timezone_set('America/New_York');

    // get this person's number
    $this_num = substr($_REQUEST['From'], 1);
    // get their first letter of answer, caps
    $ans = substr(strtoupper($_REQUEST['Body']), 0, 1);

    // set output filename
    $filedest = "quiz_answers_".date('yMd', time()).".csv";

    // check if the person has answered this quiz before
    $answered = false;
    $a = array();
    $file = fopen($filedest,"r");
    // if file exists, traverse each line in csv
    if ($file != null) {
        while(! feof($file)) {
            array_push($a, fgetcsv($file));
        } fclose($file);
        for($x=0; $x < count($a); $x++){
            // phone number is first item in line
            if ($a[$x][0] == $this_num) {
                // person answered this quiz already
                $answered = true;
            }
        }
    }

    $reply = null; // content of reply message
    if ($answered) {
        // person answered already
        $reply = "Thank you. We already recorded your prior answer.";
    }
    else {
        // person has not answered, so now validate response
        // create quiz from csv file
        $quiz = new SplFileObject("current_quiz.csv");
        $quiz->setFlags(SplFileObject::READ_CSV);
        $quiz->setCsvControl(';');

        // retrieve quiz question and date
        $quiz_ans = null;
        $quiz_date = null;
        $ans_exp = null; // this is the explanation of the answer
        foreach ($quiz as $v) {
            list ($q, $a, $d, $exp) = $v;
            $quiz_ans = $a;
            $quiz_date = date('ymd', strtotime($d));
            $ans_exp = $exp;
        }

        $correct = 0; // flag of whether response matches quiz file
        if ($quiz_ans == "T" && $ans == "T" ||
                $quiz_ans == "F" && $ans == "F") {
            $correct = 1;
        }

        // check the answer was sent within the quiz date
        $end = strtotime("11:59PM"); // midnight
        if ((time() < $end) && (date('ymd', time()) == $quiz_date)){
            if ($correct == 1) {
                $reply = "Correct! You have earned $10.".$ans_exp;
            }
            else {
                $reply = "Sorry that is incorrect. Try again next time.".$ans_exp;
            }
        }
        else {
            $reply = "Sorry you have passed the quiz reply period.";
        }

        // write response onto output csv file
        $handle = fopen($filedest, "a");
        // each line is phone number with 0/1 if in/correct
        $line = array ($this_num, $correct);
        fputcsv($handle, $line);
        fclose($handle);
    }
    //--------------------------------------------------------
    // now greet the sender
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message><?php echo $reply?></Message>
</Response>