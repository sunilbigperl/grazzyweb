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
    
    .invoice-box table tr.total td:nth-child(4){
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
                               Restaurant Company Name<br>
                               Registered Address<br>
                               Outlet Address<br>
                               res@taura.nt
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
                                Customer Name<br>
                                Address Line1<br>
                                Address Line2<br>
                                Branch<br>
                                Email

                               <!-- <?php echo $name;?><br>
								<?php echo $address;?><br/>
								<?php echo $branch;?><br/>
                               <?php echo $email;?> -->
                            </td>
                            
                            <td>
                                Date: <?php echo $date; ?><br>
                                Bill Number:<br>
                                Order Number:<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
       
            <tr class="heading" style="text-align:center;">
                <td colspan=3>
                    Retail Invoice
                </td>
               
            </tr>

            <tr>
                
                    <tr class="item">
                        <td>Food Item1</td>
                         <td></td>
                        <td>RS.</td>
                        <td>250</td>
                        <!-- <td><?=$deliverycharge1;?></td> -->
                        
                       
                    </tr>
            
                
                        <tr class="item">
						<td>Food Item2</td>
                        <td></td>
                        <td>Rs.</td>
                        <!-- <td> <?=$servicetax1;?></td> -->
                        <td>300</td>
                        </tr>

                        <tr class="item">
                        <td>Food Item3</td>
                        <td></td>
                        <td>Rs.</td>
                        <td>214</td>
                        <!-- <td> <?=$servicetax1;?></td> -->
                        </tr>
                    

					    <tr class="total">
                        <td>Total</td>
                         <td></td>
                         <td>Rs.</td>
                       
                     <!-- <td><?=$totalbill;?> </td>  -->
                    </tr>

                    <tr class="total">
                        <td>SGST</td>
                         <td>9%</td>
                         <td>Rs.</td>
                         <td></td>
                       
                     <!-- <td><?=$totalbill;?> </td>  -->
                    </tr>

                    <tr class="total">
                        <td>CGST</td>
                         <td>9%</td>
                         <td>Rs.</td>
                         <td></td>
                       
                     <!-- <td><?=$totalbill;?> </td>  -->
                    </tr>

                     <tr class="total">
                        <td>Gross Total</td>
                         <td></td>
                         <td>Rs.</td>
                         <td></td>
                       
                     <!-- <td><?=$totalbill;?> </td>  -->
                    </tr>
                  <tr>  
                 <td style="text-align:center;">GSTIN:</td> 

                 <td></td>
                </tr>
                <tr >
                 <td style="text-align:center;">Since this is a Computer Generated Invoice,No Signeture is required</td> 
                <tr>

                </tr>

            
            
        </table>
    </div>