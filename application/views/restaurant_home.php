<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="restaurants, finder, good food" />
	<meta name="description" content="Find the restaurant best catered to your needs, in a fast way!" />
	<title>Restaurant Finder Home Page</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/css/bootstrap-glyphicons.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/styles.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="/ci/assets/js/cycle.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.full-screen-background-image').cycle({
				fx: 'fade', 
				speed: 3000
			});
		});
	</script>
</head>
<body>
<div class="full-screen-background-image">
	<img alt="full screen background image" src="/ci/assets/images/restaurant_background_1.jpg" class="full-screen-background-image" /> 
	<img alt="full screen background image" src="/ci/assets/images/restaurant_table_bckground.jpg" class="full-screen-background-image" />
	<img alt="full screen background image" src="/ci/assets/images/restaurant_background_2.jpg" class="full-screen-background-image" />
</div>
	<div id="wrapper">
		<div class="transbox">
			<h1>The Restaurant Finder</h1>
			<h3>Find the place that's catered just for you!</h3>
		</div>
		<form id="form" class="form-inline" action="/ci/restaurant/process_home_search" method="post">
			<!-- <p class="input-group text_field float_left">
	            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
	            <input class="form-control input-large" type="text" name="location" placeholder="Restaurant Name">
        	</p> -->
        	<p class="input-group text_field float_left">
	            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
				<input class="form-control input-large" type="text" name="location" placeholder="Restaurant Name, Address, City, State or Zipcode">
        	</p>
			<div id="checkbox" class="checkbox-inline">
				<label class="checkbox-inline checkbox float_left" id="affordability"><input type="checkbox" class="big-checkbox" id="affordability" name="1" value="checked"> Affordability</label>
				<label class="checkbox-inline checkbox float_left" id="ambiance"><input type="checkbox" class="big-checkbox" id="ambiance" name="2" value="checked"> Ambiance</label>
				<label class="checkbox-inline checkbox float_left" id="quality"><input type="checkbox" class="big-checkbox" id="quality" name="3" value="checked"> Food Quality</label>
				<label class="checkbox-inline checkbox float_left" id="service"><input type="checkbox" class="big-checkbox" id="service" name="4" value="checked"> Service</label>
			</div>
			<input class="btn btn-primary btn-lg float_left clear_left" type="submit" name="" value="Search">
		</form>
	</div>
</body>
</html>