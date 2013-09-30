<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="restaurants, finder, good food" />
	<meta name="description" content="Find the restaurant best catered to your needs, in a fast way!" />
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css">	
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/css/bootstrap-glyphicons.css">
	<link rel="stylesheet" type="text/css" href="CSS/styles.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			$('#user_form').submit(function(){
                var form = $(this);
                $.post(
                    form.attr('action'),
                    form.serialize(),
                    function(data){
                        $('#users_list').html(data);
                    },
                     'json');
               return false;
            });

		});
	</script>
</head>
<body class="shattered_background">