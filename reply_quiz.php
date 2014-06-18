<?php
    // set time zone
    date_default_timezone_set('America/New_York');

    // get this person's number
    $this_num = substr($_REQUEST['From'], 1);
    // get their first letter of answer, caps
    $ans = substr(strtoupper($_REQUEST['Body']), 0, 1);

    // set input and output filenames
    $filequiz = "quiz_question_".date('y-m-d', time()).".csv"; //today's date
    $filedest = "quiz_responses_".date('y-m-d', time()).".csv";

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
        $quiz = fopen($filequiz,"r");

        // check the answer was sent within the quiz date
        if ($quiz == null) {
            //file not found, so passed quiz day
            $reply = "Sorry, you have passed the quiz reply period (ending at midnight).";
        }
        else {
            $a = fgetcsv($quiz, 0, ";");
            fclose($quiz);
            $quiz_ans = substr($a[1], -1, 1); //allows for space after ;
            $ans_exp = $a[2];

            $correct = 0; // flag of correct or incorrect
            if ($quiz_ans == $ans) {
                    $reply = "Correct! You have earned $7.".$ans_exp;
                    $correct = 1;
            }
            else {
                $reply = "Sorry that is incorrect. Try again next time.".$ans_exp;
            }

            // write response onto output csv file
            $handle = fopen($filedest, "a");
            // each line is phone number with 0/1 if in/correct
            $line = array ($this_num, $correct);
            fputcsv($handle, $line);
            fclose($handle);
        }
    }
    //--------------------------------------------------------
    // now greet the sender
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message><?php echo $reply?></Message>
</Response>