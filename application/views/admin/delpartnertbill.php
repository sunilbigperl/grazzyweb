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
                               <?=$name;?><br>
                              <?=$email;?>
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
								Wolotech LLP<br>
								152, Mittal Court (B),<br>
								Nariman Point,<br>
								Mumbai 400021<br>
								billing@wolotech.com	
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
            
					<tr class="item">
						<td>&nbsp;</td>
						<td>Deliveries</td>
						<td>Rate</td>
						<td>Delivery charge</td>
					</tr>
					<tr class="item">
						<td> </td>
						<td>
							<?=$deliveries;?>
						</td>
						<td> <?=$rate;?></td>
						<td>
							<?=$delivery_charge;?>
						</td>
					</tr>
					<tr class="item last">
						<td>GST</td>
						<td></td>
						<td><?=$servicetax1;?></td>
						<td><?=$servicetax;?></td>
					</tr>
					<tr class="total">
						<td>Total</td>
						 <td></td>
						  <td></td>
						<td>
						  <?=$total;?>
						</td>
					</tr>
              
            

            
            
        </table>
    </div>
