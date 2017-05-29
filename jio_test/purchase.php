<?php
 date_default_timezone_set('Asia/Calcutta');

	
$order_id =$_GET["order_id"];//rand(1,10000000);//"9KZJLV8VDUGK";
$amount =$_GET["amount"];//rand(1,10000000);//"9KZJLV8VDUGK";
	

$timestamp = date('YmdHis');// "20170405125217";

	
$checksumSeed = "tSLfi8BMxohvWJTCfwd1cZuARH78myF21JAdgdNhvixbj7o6+uIA38WFm7VHQ0aGu8LyQYv8tRPyN+Ba0+nRLuBLZXK4PH2gxkSvJ7Jnhof0NJr3IktRPSUZIQi6hcFZxoElSuOD8KzxCkagJfxT/u4vLeGqdskKl3p46RxJAJvOp7VutGHK2MG1HE7X68E/cKJrEUk7v0vI+kUp2Mfyh7GE8wZ9enYBrkY6olGYS9pLHLv/zGAZzAZVadblK1+3";

//Clientid|Amount|Extref|Channel|MerchantId|Token|Return Url|Txn TimeStamp|TxnType
	
$data = "10000002|".$amount."|".$order_id."|WEB|100001000014146||https://app.eatsapp.in/jio_test/response.php|".$timestamp."|PURCHASE";

	
$checksum = hash_hmac('SHA256',$data, $checksumSeed);
//$checksum = "070d0d1145dd0a0f6524f8f537b7ebd4f1c6008fe7f25694acd6bd85b5a929d5";

	//001000003096
	
	//echo $checksum;
	
	$url = "https://app.eatsapp.in/jio_test/response.php";
?>


<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript">
function nithin()
{
console.log("a");
 $( "#form1" ).submit();
console.log("b");

}

   
</script>

</head>

<body onload="nithin()" style="background:#ededee"> 

<img src="eatsapp_loading.gif" style="display: block;margin: auto;">
<p style="display: none;text-align: center;font-weight: bold;font-family: arial;">Eatsapp</p>
<p style="display: none;text-align: center;/*! font-weight: bold; */font-family: arial;">All the transactions made through Eatsapp are secure.Please click on proceed to pay to continue.</p>

<form name="payment" id="form1" method="POST" action="https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments">
<input type='hidden' name="merchantid" value="100001000014146"/>	
<input type='hidden' name="clientid" value="10000002"/>	
<input type='hidden' name="channel" value="WEB"/>	
<input type='hidden' name="returl" value="<?php echo $url; ?>"/>	
<input type="hidden" name="checksum" value="<?php echo $checksum; ?>"/>	 
<input type='hidden' name="token" value=""/>	
<!-- Transaction Info -->	 
<input type="hidden" name="transaction.extref" value="<?php echo $order_id; ?>"/>	
<input type="hidden" name="transaction.timestamp" value="<?php echo $timestamp; ?>"/>	
<input type='hidden' name="transaction.txntype" value="PURCHASE"/>	
<input type='hidden' name="transaction.amount" value="<?php echo $amount; ?>"/>
<input type="hidden" name="transaction.currency" value="INR"/>
<input type="hidden" name="subscriber.mobilenumber" value="820569065"/>


</form>

<input onclick="nithin()" name="submit" style="display: none;margin: auto;background: #52c600;color: white;font-weight: bold;padding: 10px;border: none;" value="Proceed to Pay" type="button">

</body>




</html>