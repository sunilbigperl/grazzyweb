<?php
	//header('Content-Type: application/xml');
	$timestamp = date('YmdHis');
	$data1 = "10000002|100001000014146|STATUSQUERY|901033389607";
	$checksumSeed = "tSLfi8BMxohvWJTCfwd1cZuARH78myF21JAdgdNhvixbj7o6+uIA38WFm7VHQ0aGu8LyQYv8tRPyN+Ba0+nRLuBLZXK4PH2gxkSvJ7Jnhof0NJr3IktRPSUZIQi6hcFZxoElSuOD8KzxCkagJfxT/u4vLeGqdskKl3p46RxJAJvOp7VutGHK2MG1HE7X68E/cKJrEUk7v0vI+kUp2Mfyh7GE8wZ9enYBrkY6olGYS9pLHLv/zGAZzAZVadblK1+3";
	$checksum = hash_hmac('SHA256',$data1, $checksumSeed);

	$data ='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<REQUEST>
		<REQUEST_HEADER>
			<VERSION>1.0</VERSION>
			<API_NAME>STATUSQUERY</API_NAME>
		</REQUEST_HEADER>
		<PAYLOAD_DATA>
			<CLIENT_ID>10000002</CLIENT_ID>
			<MERCHANT_ID>100001000014146</MERCHANT_ID>
			<TRAN_REF_NO>901033389607</TRAN_REF_NO>
		</PAYLOAD_DATA>
		<CHECKSUM>'.$checksum.'</CHECKSUM>
	</REQUEST>';

	$url = 'https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments';
	$ch = curl_init($url);
    $options = array(
        CURLOPT_RETURNTRANSFER	=> true,         // return web page
        CURLOPT_HTTPHEADER     	=> array('Content-Type:application/xml'),
        CURLOPT_HEADER         	=> array('Content-Type:application/xml','Accept:application/xml'),
        CURLOPT_POSTFIELDS 		=> $data,
		CURLOPT_SSL_VERIFYHOST => 2,           
        CURLOPT_SSL_VERIFYPEER => true
    );

    curl_setopt_array($ch,$options);
    $res = curl_exec($ch);
	
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    echo '<strong>ERROR No. : </strong>'.$curl_errno;
    echo '<br/>';
    echo '<br/>';
    echo '<strong>ERROR: </strong>'. $curl_error;
    echo '<br/>';
    echo '<pre>';
    print_r($res);
    curl_close($ch);
	
?>