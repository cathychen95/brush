<?php

    /*
    initialize.php
    The purpose of this script is to receive texts of "BEAM-" or "BRUSH"
    Then the number will be inputted into the csv number file(s).

    Input: pre_numbers.csv
    [10-digit number with '1' prefix], [morning reminder time], [night reminder time]
    
    Output: study_numbers.csv
    (all on same line)
    [10-digit number with '1' prefix], [start date], [end date],
                [morning reminder time], [night reminder time], [BEAM number / BRUSH]
    */

    // set time zone
    date_default_timezone_set('America/New_York');

    // get this person's number
    $this_num = substr($_REQUEST['From'], 1);
    // get their first letter of answer, caps
    $ans = substr(strtoupper($_REQUEST['Body']), 0, 1);

    $time1 = "TIME_ERROR";
    $time2 = "TIME_ERROR";
    // check if this is an initialize message
    if ($ans == "B") {
        // check this person's morning/night times from the pre-study info csv
        $a = array();
        $file = fopen("pre_numbers.csv","r");
        // if file exists, traverse each line in csv to find this person
        if ($file != null) {
            while(! feof($file)) {
                array_push($a, fgetcsv($file));
            } fclose($file);
            for($x=0; $x < count($a); $x++){
                // phone number is first item in line
                if ($a[$x][0] == $this_num) {
                    // get morning and night times
                    $time1 = date("h:ia", strtotime($a[$x][1]));
                    $time2 = date("h:ia", strtotime($a[$x][2]));
                }
            }
        }
        // write response onto output csv file
        $handle = fopen("study_numbers.csv", "a");
        // calculate start and end dates
        $today = date('ymd', time());
        $start = date('m/d/Y',strtotime($date1 . "+1 days"));
        $end = date('m/d/Y',strtotime($date1 . "+29 days"));
        $line = array ($this_num, $start, $end, $time1, $time2);
        fputcsv($handle, $line);
        fclose($handle);

        // put into beam_numbers if BEAM SUBJECT
        if (substr(strtoupper($_REQUEST['Body']), 0, 4) == "BEAM") {
            // write response onto output csv file
            $handle = fopen("beam_numbers.csv", "a");
            // retrieve BEAM ID
            // "BEAM" = [0:3], "-"" = [4], "xxxx" = [5:8]
            // ID starts at index 5
            $id = substr($_REQUEST['Body'], 5, 4);
            $line = array ($this_num, $id);
            fputcsv($handle, $line);
            fclose($handle);
        }
    }
    
    //--------------------------------------------------------
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message>Welcome to the study. Thank you for confirming receipt of the brush. We will now process payment for this study step and mail you a check. Your Upennbrush team.</Message>
</Response>