<html>
<head>

<script type="text/javascript">

function nithin()
{
document.getElementById("form1").submit();

}


</script>

</head>

<body onload="nithin()">
<p>Processing your request.Please wait......</p>

<form name="payment" method="POST" id="form1" action="https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments">

<input type="hidden" name="merchantid" value="100001000014146"/>
<input type="hidden" name="clientid" value="10000002"/>
<input type="hidden" name="channel" value="WEB"/>
<input type="hidden" name="returl" value="http://61.16.175.3:8089/misimul/purchaseResult.jsp"/>
<input type="hidden" name="checksum" value="070d0d1145dd0a0f6524f8f537b7ebd4f1c6008fe7f25694acd6bd85b5a929d5"/>
<input type="hidden" name="token" value="">
<input type="hidden" name="transaction.extref" value="9KZJLV8VDUGK"/>
<input type="hidden" name="transaction.timestamp" value="20170405125217"/>
<input type="hidden" name="transaction.txntype" value="PURCHASE"/>
<input type="hidden" name="transaction.amount" value="1.00"/>
<input type="hidden" name="transaction.currency" value="INR"/>
<input type="hidden" name="subscriber.mobilenumber" value="9820569065"/>

<!--<input type="submit" value="Jio Pay" />-->
<!--000|10000002|100001000014146|1234|extref123456|901033349332|2.00|SUCCESS|APPROVED|20170214151549|NA|JM|NA|7710075465|pd|ud|ud|ud|ud|ud|394aae3eaf59a604a3611431098ba0c145ef8e947c3716213cf5c35701e5cacb-->
 
</form>
</body>


</html>