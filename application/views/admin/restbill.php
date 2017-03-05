
    <style>
    .invoice-box{
        max-width:800px;
        margin:auto;
        padding:30px;
        border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);
        font-size:16px;
        line-height:24px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;
    }
    
    .invoice-box table{
        width:100%;
        line-height:inherit;
        text-align:left;
    }
    
    .invoice-box table td{
        padding:5px;
        vertical-align:top;
    }
    
    .invoice-box table tr td:nth-child(2){
        text-align:right;
    }
    
    .invoice-box table tr.top table td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.top table td.title{
        font-size:45px;
        line-height:45px;
        color:#333;
    }
    
    .invoice-box table tr.information table td{
        padding-bottom:40px;
    }
    
    .invoice-box table tr.heading td{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
    }
    
    .invoice-box table tr.details td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom:1px solid #eee;
    }
    
    .invoice-box table tr.item.last td{
        border-bottom:none;
    }
    
    .invoice-box table tr.total td:nth-child(2){
        border-top:2px solid #eee;
        font-weight:bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }
        
        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }
    </style>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                           
                            <td style="text-align:center;">
                               Wolotech LLP<br>
                               152,Mittal Court(B),Nariman Point, Mumbai 400021<br>
                               Billing@wolotech.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                To<br>
                                <?php echo $name;?><br>
								<?php echo $address;?><br/>
								<?php echo $branch;?><br/>
                               <?php echo $email;?>
                            </td>
                            
                            <td>
                                Date: <?php echo $date; ?><br>
                                Bill No:<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
       
            <tr class="heading" style="text-align:center;">
                <td colspan=3>
                    Service Invoice
                </td>
               
            </tr>
            
            <tr>
				<table>
					<tr class="item">
						<td>Value of orders forwarded</td>
						<td></td>
						<td></td>
					</tr>
					<tr class="item">
						<td>
							Commission
						</td>
						<td> </td>
						<td>
							$75.00
						</td>
					</tr>
					<tr class="item last">
						<td> Service tax</td>
						<td></td>
						<td> $10.00</td>
					</tr>
					<tr class="total">
						<td>Total</td>
						 <td></td>
						<td>
						   $385.00
						</td>
					</tr>
                </table>
            </tr>
            
             <tr>
				<table>
					<tr class="item">
						<td>Value of orders forwarded</td>
						<td></td>
						<td></td>
					</tr>
					<tr class="item">
						<td>
							Commission
						</td>
						<td> </td>
						<td>
							$75.00
						</td>
					</tr>
					<tr class="item last">
						<td> Service tax</td>
						<td></td>
						<td> $10.00</td>
					</tr>
					<tr class="total">
						<td>Total</td>
						 <td></td>
						<td>
						   $385.00
						</td>
					</tr>
                </table>
            </tr>

            
            
        </table>
    </div>
