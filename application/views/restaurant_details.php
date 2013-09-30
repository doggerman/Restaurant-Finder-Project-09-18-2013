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
	<script src="/ci/assets/js/jquery.raty.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			$(".star_rating li").hover(function()
				{
					var star = $(this);
					var siblings = star.parent().find("li");

					// reset the stars to default
					siblings.removeClass("rated");

					siblings.each(function(index)
					{
						if(star.index() >= index)
						{
							$(this).addClass("rated");
						}
					});
				}, function()
				{
					var star = $(this);
					var siblings = star.parent().find("li");
					var clicked_star = star.parent().find("li.clicked");

					// check if clicked_star exists
					if(clicked_star.length != 0)
					{
						siblings.each(function(index)
						{
						 	if(clicked_star.index() >= index)
							{
								$(this).addClass("rated");
							}
							else
							{
								$(this).removeClass("rated");
							}
						});	
					}
					else
					{
						star.removeClass("rated").siblings("li").removeClass("rated");
					}
				});

			$(".star_rating li").click(function()
			{
				// variables
				var star = $(this);
				var siblings = star.parent().find("li");

				// get category
				var category_id = star.parent().attr("id");

				// check if this star is clicked
				if(star.hasClass("clicked"))
				{
					star.removeClass("clicked");
				}
				else
				{
					// to avoid several stars to have a class 'clicked'
					siblings.removeClass("clicked");

					// adds the class 'clicked' to the clicked star
					star.addClass("clicked");
					$("#rating_form").find("input[name='" + category_id + "']").val(star.index() + 1);
				}

			});
			
			$('#rating_form').submit(function(){

				$.post(
					
					$(this).attr('action'),
					$(this).serialize(),
					function(data){
						if(data.error)
						{
							$('#error').html(data.error);
						}
						else
						{							
							window.location = "/ci/restaurant/get_restaurant_details/?=" + data.restaurant_id;
						}
					},					
					"json"		
				);
				return false;		
			});
 
			//ajax for input field
			$('.text_field').keyup(function(){

				$.post(
					
					$('#side_bar_form').attr('action'),
					$('#side_bar_form').serialize(),
					function(data){

						if(data.list)
						{
							$('#side_bar_list').html(data.list);
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
							$('#side_bar_list').html(data.list);
						}
					},					
					"json"		
				);
				return false;		
			});

			$('#comment_form').submit(function(){

				$.post(
					
					$(this).attr('action'),
					$(this).serialize(),
					function(data){
						if(data.error)
						{
							$('#comment_messages').html(data.error);
						}
						else
						{						
							window.location = "/ci/restaurant/get_restaurant_details/?=" + data.restaurant_id;
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
<div id="wrapper_restaurant">
  	<div id="left_details" class="float_left">
  		<!-- Restaurant detailed page - left restaurant details - top section -->
	  	<div id="restaurant_details" class="float_left">	
	  		<?php

	  			echo $detailed;

	  		?>
	  		<!-- <button id="reload"></button> -->
	  		<h3>Share your thoughts/comments</h3>
	  		<div id="comment_messages">
	  			<?php
	  				$comment_success = $this->session->flashdata('comment_success');
	  				if(isset($comment_success))
	  				{
	  					echo $comment_success;
	  				}
  				?>
	  		</div>
	  		<form id="comment_form" action="/ci/restaurant/process_comment" method="post">
	  			<div class="input-group col-lg-4 float_left">
				  <span class="input-group-addon required"></span>
				  <input type="text" name="name" class="form-control input-sm" placeholder="Name">
				</div>
				<div class="input-group col-lg-4 float_left clear">
				  <span class="input-group-addon required"></span>
				  <input type="email" name="email" class="form-control input-sm" placeholder="Email">
				</div>
				<div class="input-group col-lg-4 float_left clear">
					<span class="input-group-addon required"></span>
					<input type="text" class="form-control input-sm" name="title" placeholder="Title">
				</div>
				<textarea class="float_left clear form-control textarea" id="comment" cols="80" rows="4" name="comment" placeholder="Write a comment"></textarea>
				<input class="float_left clear btn btn-success" id="button" type="submit" value="Post a comment">
	  		</form>
	  	</div>
	  	<!-- Restaurant detailed page - left restaurant details - display comment section -->
		<div id="comments" class="clear float_left">		
				<?php
					if(isset($reviews['rating_comment']))
					{
						echo $reviews['rating_comment'];
					}
					if(isset($reviews['comment']))
					{
						echo $reviews['comment'];
					}
					if(isset($reviews['rating']))
					{
						echo $reviews['rating'];
					}
					
				?>
		</div><!-- end of comments -->
	</div><!-- end of left side details -->
	<!-- Restaurant detailed page - similar restaurants info -->
  	<div id="right_list" class="float_right">
  		<div id="search_bar">
  			<form id="side_bar_form" action="/ci/restaurant/process_inside_search_details" method="post">
				<input class="clear_left text_field" type="search" results="0" name="location" value="<?php if(isset($placeholder)) echo $placeholder ?>">
				<div id="checkbox" class="checkbox-inline">
					<label class="checkbox-inline checkbox float_left" id="affordability"><input type="checkbox" class="big-checkbox" id="affordability" name="1" value="checked" <?php if(isset($check[1])) echo "checked='checked'"; ?>> Affordability</label>
					<label class="checkbox-inline checkbox float_left" id="ambiance"><input type="checkbox" class="big-checkbox" id="ambiance" name="2" value="checked" <?php if(isset($check[2])) echo "checked='checked'"; ?>> Ambiance</label>
					<label class="checkbox-inline checkbox float_left" id="quality"><input type="checkbox" class="big-checkbox" id="quality" name="3" value="checked" <?php if(isset($check[3])) echo "checked='checked'"; ?>> Food Quality</label>
					<label class="checkbox-inline checkbox float_left" id="service"><input type="checkbox" class="big-checkbox" id="service" name="4" value="checked" <?php if(isset($check[4])) echo "checked='checked'"; ?>> Service</label>
				</div>
			</form>
  		</div>
  		<div id="side_bar_list">
			<?php

				echo $list;

			?>
		</div>
	</div><!-- end of right_list div-->
	<div class="clear"></div>
  </div><!-- end of wrapper div -->
</body>
</html>