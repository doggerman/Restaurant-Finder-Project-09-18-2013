<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="restaurants, finder, good food" />
	<meta name="description" content="Find the restaurant best catered to your needs, in a fast way!" />
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css">	
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/css/bootstrap-glyphicons.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/styles.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="/ci/assets/js/cycle.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			//ajax for input field
			$('.text_field').keyup(function(){

				$.post(
					
					$('#side_bar_form').attr('action'),
					$('#side_bar_form').serialize(),
					function(data){

						if(data.list)
						{
							$('#right_list').html(data.list);
						}
					},					
					"json"		
				);
				return false;		
			});

			//ajax for checkbox
			$('.big-checkbox').change(function(){

				$.post(
					
					$('#side_bar_form').attr('action'),
					$('#side_bar_form').serialize(),
					function(data){

						if(data.list)
						{
							$('#right_list').html(data.list);
						}
					},					
					"json"		
				);
				return false;		
			});

		});
	</script>
</head>
<body class="shattered_background">
	<div id="wrapper_list">
		<div id="left_search" class="float_left">
			<?php

				if(isset($list['error_message']))
				{
					echo "<p>{$error_message}</p>";
				}
			?>
			<form id="side_bar_form" action="/ci/restaurant/process_inside_search" method="post">
				<input class="clear_left text_field" type="search" results="0" name="location" value="<?php if(isset($placeholder)) echo $placeholder ?>">
				<ul id="checkbox" class="checkbox">
					<li><label class="checkbox" id="affordability"><input type="checkbox" class="big-checkbox" id="affordability" name="1" value="checked" <?php if(isset($check[1])) echo "checked='checked'"; ?>> Affordability</label></li>
					<li><label class="checkbox" id="ambiance"><input type="checkbox" class="big-checkbox" id="ambiance" name="2" value="checked" <?php if(isset($check[2])) echo "checked='checked'"; ?>> Ambiance</label></li>
					<li><label class="checkbox" id="quality"><input type="checkbox" class="big-checkbox" id="quality" name="3" value="checked" <?php if(isset($check[3])) echo "checked='checked'"; ?>> Food Quality</label></li>
					<li><label class="checkbox" id="service"><input type="checkbox" class="big-checkbox" id="service" name="4" value="checked" <?php if(isset($check[4])) echo "checked='checked'"; ?>> Service</label></li>
				</ul>
			</form>
		</div>
		<div id="right_list">
			<?php
					echo $list;		
			?>
		</div><!-- end of right_list div-->
		<form>
			<input id="bottom_btn" class="btn btn-primary" type="submit" name="" value="Show more results">
		</form>	
	</div><!-- end of wrapper div -->
</body>
</html>