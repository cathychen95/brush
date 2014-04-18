<?php
    
    date_default_timezone_set('America/New_York');

    $this_num = substr($_REQUEST['From'], 1);

/*
    $handle = fopen("quiz_answers.csv", "a");
        $line = array ("another line", "hello");
        fputcsv($handle, $line);
        fclose($handle);
*/

    // check if the person has answered this quiz before
    $answered = false; // flag is true if person answered before
    $a = array(); // create empty array
    $file = fopen("quiz_answers.csv","r");
    while(! feof($file)) { // traverse through each line of file
        // add every person's data to a
        array_push($a, fgetcsv($file));
    } fclose($file);
    // cycle through array from csv and send message
    for($x=0; $x < count($a); $x++){
        if ($a[$x][0] == $this_num) {
            $answered = true;
        }
    }
    

    //--------------------------------------------------------
    // create quiz from csv file
    $quiz = new SplFileObject("current_quiz.csv");
    $quiz->setFlags(SplFileObject::READ_CSV);
    $quiz->setCsvControl(';');

    // retrieve quiz question and date
    //$quiz_question = null;
    $quiz_ans = null;
    $quiz_date = null;
    foreach ($quiz as $v) {
        list ($q, $a, $d) = $v;
        //$quiz_question = $q;
        $quiz_ans = $a;
        $quiz_date = date('ymd', strtotime($d));
    }
 
    // get their answer
    $ans = strtoupper($_REQUEST['Body']);

    // all the possible valid responses, depending on the quiz file
    $correct = null;
    if ($quiz_ans == "T") {
        $correct = array("T", "TRUE", "TRU", "TURE");
    }
    else  {
        $correct = array("F", "FALSE", "FLSE", "FALS");
    }

    $reply = "Sorry you have passed the quiz reply period.";
    $end = strtotime("11:59PM"); // midnight
    if ((time() < $end) && (date('ymd', time()) == $quiz_date)){
        if (in_array($ans, $correct)) {
            $reply = "Correct! You have earned $10.";
        }
        else {
            $reply = "Sorry that is incorrect. Try again next time.";
        }
    }
    // override reply if answered already
    if ($answered) {
        $reply = "Thank you. We already recorded your prior answer.";
    }
    else {
        $handle = fopen("quiz_answers.csv", "a");
        $line = array ($this_num, $ans);
        fputcsv($handle, $line);
        fclose($handle);
    // $reply = "got to else branch";
    }

    // now greet the sender
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message><?php echo $reply?><?php echo $this_num?></Message>
</Response>