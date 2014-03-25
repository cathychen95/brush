<?php
 
    require "Services/Twilio.php";
 
    $AccountSid = "ACd36eed02ab4654655342f9f24b3c0dcc";
    $AuthToken = "67fc6649f3ab8e5827d245b42e938b81";
 
    // instantiate Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
/*    $header = NULL;
    $people = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }*/

    // array of phone numbers
    $people = array(
        "+12156808230" => "Thomas",
        "+19178213080" => "Cathy",
    );
 
    // loop over all numbers
    // $number is a phone number above, and 
    // $name is the name next to it
    foreach ($people as $number => $name) {
 
        $sms = $client->account->messages->sendMessage(
 
        // Step 6: Change the 'From' number below to be a valid Twilio number 
        // that you've purchased, or the (deprecated) Sandbox number
            "215-600-2133", 
 
            // the number we are sending to - Any phone number
            $number,
 
            // the sms body
            "Hello $name"
        );
 
        // Display a confirmation message on the screen
        echo "Sent message to $name";
    }