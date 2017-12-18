<?php if($this->auth->check_access('Deliver manager')) : ?>
<div style="margin-top:40px;border:1px solid red;text-align:center;overflow-y:scroll;">
<?php $messages = $this->Restaurant_model->GetdelpatnerMessages();  ?>
<MARQUEE WIDTH=100% HEIGHT=100 >
<?php if(isset($messages) && $messages != 0){
	foreach($messages as $message){ ?> 
	<div style="margin-right:20px;color:red;"><strong></strong> <?=$message['message']; ?></div>
<?php } } ?>
</MARQUEE>
</div>
<script>
       setTimeout(function(){
           location.reload();
           
       },5000); 
    </script>
<?php endif; ?>