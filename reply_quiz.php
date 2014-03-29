<?php

    // create quiz from csv file
    $quiz = new SplFileObject("current_quiz.csv");
    $quiz->setFlags(SplFileObject::READ_CSV);
    $quiz->setCsvControl(';');

    // retrieve quiz question and date
    $quiz_question = null;
    $quiz_ans = null;
    $quiz_date = null;
    foreach ($quiz as $v) {
        list ($q, $a, $d) = $v;
        $quiz_question = $q;
        $quiz_ans = $a;
        $quiz_date = date('mdy', $d);
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
   
    $reply = null;
    if (in_array($ans, $correct)) {
      $reply = "Correct! You have earned $10.";
    }
    else {
      $reply = "Sorry that is incorrect. Try again next time.";
    }
 
    // now greet the sender
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message><?php echo $reply?></Message>
</Response>