<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="30">
    </head>

<?php if($this->auth->check_access('Restaurant manager')) : ?>
<div  style="margin-top:40px;border:1px solid red;text-align:center;overflow-y:scroll;">
<?php $messages = $this->Restaurant_model->GetMessages(); ?>
<MARQUEE WIDTH=100% HEIGHT=100 >
<?php if(isset($messages['data'])){
	foreach($messages['data'] as $message){ ?> 
	<div style="margin-right:20px;color:red;"><strong></strong> <?=$message['message']; ?></div>
<?php } } ?>
</MARQUEE>
</div>
<?php endif; ?>
</html>