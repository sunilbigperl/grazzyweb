<?php 

/*
$query = "SELECT `did` FROM `delivery_boy` WHERE `id` = ";								
				$query_run=mysql_query($query) ;			
				$result=mysql_fetch_assoc($query_run);						
				$did=$result['did'];	
				$registatoin_ids = array($did);
*/

$registatoin_ids = array("d3mZIttEdVY:APA91bFbXgFmO3MC0XJP10Lu-9I3T8kC9UR97p7QfwMkmpIAD4i_JRyUszqpcduZZ3cv67TAcCEvdNefFADztdriabnlyj95ios71puv0ENdemi5K5Km0oo1jKbLwYGSJ0n41MOVx6Eu");
	
    $message = array("type" => "order_assigned");      
	 
	 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=AIzaSyCB4r56wVzKQdte4Rw8QUwoK9k7AMP0fr4',
            'Content-Type: application/json'
        );
		//print_r($headers);
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        echo $result;
    


?>