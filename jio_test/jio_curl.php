<?php



echo "1";



$url = 'https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments';

$postData = array();

$postData["merchantid"] = "100001000014146";

$postData["clientid"] = "10000002";

$postData["channel"] = "WEB";

$postData["returl"] = "https://eatsapp.in/login/jio_test/response.php";

$postData["checksum"] = "5cf6736717ebce51966949dc25ea2c088706515548fe9b8b8f6ee14d3496f23c ";

$postData["token"] = "";

$postData["transaction.extref"] = "123123123";

$postData["transaction.timestamp"] = "20170405125217";

$postData["transaction.txntype"] = "PURCHASE";

$postData["transaction.amount"] = "1.00";

$postData["transaction.currency"] = "INR";

$postData["transaction.mobilenumber"] = "820569065";



echo "2";



/*

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);



echo "3";



$result = curl_exec($ch);

curl_close($ch);



echo "4";

echo $result;



print_r ("result is".$result);

*/





$query = http_build_query ($postData);



$contextData = array ( 

                'method' => 'POST',

                'header' => "Connection: close\r\n".

                            "Content-Length: ".strlen($query)."\r\n",

                'content'=> $query );

 

// Create context resource for our request

$context = stream_context_create (array ( 'http' => $contextData ));

 

// Read page rendered as result of your POST request

$result =  file_get_contents (

                  'https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments',  // page url

                  false,

                  $context);



echo $result;

print_r ("result is".$result);				  

				  

?> 