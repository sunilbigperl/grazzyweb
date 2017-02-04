<script src="<?php echo base_url('vendors/js/typeahead.jquery.js');?>"></script>
<style>
.tt-menu,
.gist {
  text-align: left;
}

a {
  color: #03739c;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.table-of-contents li {
  display: inline-block;
  *display: inline;
  zoom: 1;
}

.table-of-contents li a {
  font-size: 16px;
  color: #999;
}



/* site theme */
/* ---------- */

.title {
  margin: 20px 0 0 0;
  font-size: 64px;
}

.example {
  padding: 30px 0;
}

.example-name {
  margin: 20px 0;
  font-size: 32px;
}

.demo {
  position: relative;
  *z-index: 1;
  margin: 50px 0;
}

.typeahead,
.tt-query,
.tt-hint {
  /*width: 750px !important;*/
  height: 34px;
  /*padding: 8px 12px;*/
  font-size: 18px;
  /*line-height: 30px;*/
  /*border: 2px solid #ccc;
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  outline: none;*/
}

.typeahead {
  background-color: #fff;
}

/*.typeahead:focus {
  border: 2px solid #0097cf;
}*/

.tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-menu {
  width: 422px;
  margin: 12px 0;
  left:inherit !important;
  top:30% !important;
  padding: 8px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 3px 20px;
  font-size: 18px;
  line-height: 24px;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #0097cf;
}

.tt-suggestion.tt-cursor {
  color: #fff;
  background-color: #0097cf;

}

.tt-suggestion p {
  margin: 0;
}

.gist {
  font-size: 14px;
}

/* example specific styles */
/* ----------------------- */

#custom-templates .empty-message {
  padding: 5px 10px;
 text-align: center;
}

#multiple-datasets .league-name {
  margin: 0 20px 5px 20px;
  padding: 3px 0;
  border-bottom: 1px solid #ccc;
}

#scrollable-dropdown-menu .tt-menu {
  max-height: 150px;
  overflow-y: auto;
}

#rtl-support .tt-menu {
  text-align: right;
}
.twitter-typeahead input{top:inherit !important;}
</style>
<?php $url = $this->uri->segment(4);  if(!isset($url)){ ?>
<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-horizontal" action="<?php echo site_url($this->config->item('admin_folder').'/message/messagerest'); ?>" method="post">
		<div class="form-group">
			<label>Send Messsage to all restaurants</label>
			<input type="checkbox" name="rest_nameall" onclick="CheckRestAll();">
		</div>
		<div class="form-group" id="the-basics">
				<input type="text" class="form-control typeahead" id="rest_name" name="rest_name" placeholder="Restaurant name and Branch">
		</div>
		<div class="form-group">
		  <textarea class="form-control" id="message" name="message" placeholder="Type new message here"></textarea>
		</div>
		
		<div class="form-group pull-right"><input type="submit" class="btn btn-primary" value="Send" name="action"></div>

	</form>
</div>
<?php } ?>

<h4>Message History</h4>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">message id</th>
			<th data-field="Date">Date</th>
			<th data-field="Time">Time</th>
			<th data-field="Restaurant">Restaurant</th>
			<th data-field="Message">Message</th>
			
		</tr>
	</thead>
	<tbody>
		
		<?php
			$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			if($messages != 0){
			foreach($messages as $message)
			{ 
		?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				
				<td>
					<?=date('Y-m-d',strtotime($message['date'])); ?>
				</td>
				<td>
					<?=date('H:i:s',strtotime($message['date'])); ?>
				</td>
				<td>
					<?=isset($message['restaurant_name']) ? $message['restaurant_name'] : 'ALL';?>
				</td>
				<td>
					<?=$message['message'];?>
				</td>
				
				
			</tr>
			<?php
			$i++;
			}
		}
		?>
	</tbody>
</table>
<script>
var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};
var states = <?=json_encode($restaurants);?>;
$('#the-basics .typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1,
},
{
  name: 'states',
  source: substringMatcher(states)
 
});

function CheckRestAll(){
	var val = $('input[name="rest_nameall"]:checked').val();
	if(val =="on"){ 
		$("#the-basics").css("display","none");
	}else{
		$("#the-basics").css("display","block");
	}
}
</script>

