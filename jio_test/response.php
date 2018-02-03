<?php

//Status|ClientId|MerchantId|CustomerId|MerchantTxnRefNum|JioTxnRefNum|TxnAmount|ErrorCode|ResponseMsg|TxnTimeStamp|CardNumber|TxnType|CardType|CheckSum
//500|10000040|100001000013689|NA|123456799991|001000000521|1.00|ERROR-CANCEL|Transaction has been cancelled|20151218151008|NA|NA||9518908f9557e6ef6d937eb7323d58ca0d1138f6ba1f709fd59d8f9bdd1b9fe9
//print_r($_POST['response']);
//exit;
$result = explode('|', $_POST['response']);
//echo '<pre>';
//print_r($result);
$year = substr($result[9],0,4);
$month = substr($result[9],4,2);
$day = substr($result[9],6,2);
if($result[0]=='000') {
	 
	$con = mysqli_connect("localhost","root","root","eatsappdb");
	if(!$con){ die('could not connect'.mysqli_errno());}
	mysqli_query($con,"update orders set status='Order Placed' where id='".$result[4]."'");
	?>
	<p>Ref No. : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[5];?></p>
	<p>Amount  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[6];?></p>
	<p>Message : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[8];?></p>
	<p>Time    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $day.'-'.$month.'-'.$year;?></p>
	<p>Timestamp    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[9];?></p>
	<p>Card Number    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo 'xxxx xxxx xxxx '.$result[10];?></p>
	<p>Transcation Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[11];?></p>
	<p>Card Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[12];?></p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Page yet to be designed</p>
	<?php
} else {
	?>
	<p>Ref No. : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[5];?></p>
	<p>Amount  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[6];?></p>
	<p>Message : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[8];?></p>
	<p>Time    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $day.'-'.$month.'-'.$year;?></p>
	<p>Timestamp    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[9];?></p>
	<p>Card Number    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[10];?></p>
	<p>Transcation Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[11];?></p>
	<p>Card Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[12];?></p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Page yet to be designed</p>
	<?php
 
}
 
exit;
 
//$data = "10000040|1.00|12345679999|WEB|100001000013689||https://www.yourUrl.com/jio/response.php|20151218000000|PURCHASE";
//$checksumSeed = "NGwJrYRB/2KHaLz2M4QMuTo1jlvTknOoFHnikqX9DzUhFJ3Tm8gvsFXHnObEOmAZMB/d2H0upFtI9YosB3KHmCKEHfJ2HRjOS+6GY92JdFYgdowvBl2gaoyGXtkJbKtuPu/Kdh7kp6azXO7Pk4H9OI/4qdevGq53xQKVLMFVAeCE1w1I5wzhk0aAEAqieze/50VJm8TD1TeH0Aa+XCqbDwN6tQUP3SNFZiipLCZ6bwV1td0TXYwSYK3yH2GW1qWV";
//$output = hash_hmac('SHA256',$data, $checksumSeed);
//echo $output;


//2e30dd2f7be11298aec9ae1981c45c3bdb012bf7626511e02484073723e78d17
//2e30dd2f7be11298aec9ae1981c45c3bdb012bf7626511e02484073723e78d17

?>


