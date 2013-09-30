<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restaurant extends CI_Controller {

	protected $id;
	protected $page = NULL;
	protected $home_search = NULL;
	protected $inside_search = NULL;
	protected $restaurants_results = NULL;
	protected $html = NULL;
	protected $checked_box = NULL;
	protected $total_average = NULL;
	protected $restaurants_sorted = NULL;
	protected $restaurants_info = NULL;
	protected $ratings = NULL;
	protected $user_info = NULL;
	protected $messages = NULL;
	protected $comment = NULL;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/Los_Angeles');
	}
	
	public function index()
	{
		$this->load->view('restaurant_home.php');
	}

	public function process_home_search()
	{	
		$this->home_search['location'] = $this->input->post('location');
		
		//this finds out which boxes were checked and assigns to array with key of category id.
		for ($i=1; $i < 5; $i++)
		{
			if($this->input->post($i) === "checked")
			{
				$this->checked_box[$i] = $this->input->post($i);
			}
		}

		//this sets the variable/array created to session to be avilable in other function (didn't need here because the variable was created outside this function, so it can be accessed through calling $this->variable)
		$this->session->set_userdata('checked_box', $this->checked_box);
		$this->html['check'] = $this->checked_box;
		$this->html['list'] = $this->get_restaurants_list($this->home_search);
		//this sets the placeholder for the input field on inside page based on which input fields were filled out on home page.
		if(!empty($this->home_search['location']))
		{
			$this->html['placeholder'] = $this->home_search['location'];
		}

		$this->html['title'] = "List of Restaurants";
		$this->load->view('restaurant_list.php', $this->html);
	}

	public function get_restaurants_list($search)
	{
		//go to modal get the restaurant info, with total rating info
		$this->load->model('Restaurant_model');
		$this->restaurants_results = $this->Restaurant_model->get_restaurants_info($search);

		//this unsets the session data back to a local variable
		$this->checked_box = $this->session->userdata('checked_box');
		//this for loops through the restaurant results multidimensinal array to determine which restaurant has the highest percentage result to display in desending order. 
		foreach($this->restaurants_results as $restaurant)
		{
			//variable to use to add the rates in to.
			$ratings = 0;
			//once inside the first array in the 'parent' array, will go in to the fields, and find the first 'number' field, (which in our case are the fields which hold the ratings).
			for($i=1; $i<5; $i++)
			{
				// Go through each one of these on till finds the one that equals the number found in the checked box variable.
				if(isset($this->checked_box[$i]))
				{
					// When it finds it, it will store the value of the rating (from the current 'key' number) in to the ratings variable.
					$ratings += $restaurant[$i];
				}
			}
			//set this new variable to the ratings total of this restaurant
			$this->total_average[strval($restaurant['id'])] = $ratings;
			//then start the loop over for the next restaurant array in 'parent' array.
		}
		//will sort the new array (which holds in it 'key' (as - restaurant id), 'value' (as - rating total for that restaurant) ) in order of the highest average in reverse order (meaning highest first)
		arsort($this->total_average);
		//this will reset our original restaurant result array in to our new order that we have in the total average array.
		//Go in to total average array
		foreach ($this->total_average as $key => $value) 
		{	
			//Go in to the restaurant result array
			foreach ($this->restaurants_results as $restaurant) 
			{
				//see if the key of the current total average array equals the restaurant id of the restaurant result array
				if($key == $restaurant['id'])
				{	
					//if it doe's, send the current restaurant array (with the restaurant id just found) in to the new array
					$this->restaurants_sorted[] = $restaurant;
				}
				//then all the other restaurant results id's don't match the current key of the total average array
			}
			//then go to next key value pair in the total average array and run through the second forloop again putting the next restaurant result array id that matches the key in to the restaurant_sorted new array. And then are new array will be ordered in the order we want it in, and we display that in the html coming up next.
		}

		$this->session->set_userdata('restaurants_sorted', $this->restaurants_sorted);

		$this->html['list'] = "";
		foreach($this->restaurants_sorted as $restaurant)
    	{
    		$this->html['list'] .= "
	    		<div class='restaurant_info'>
					<h3>
						<img class='float_left' src='{$restaurant['image_path']}' alt='restaurant image'>
						<a href='/ci/restaurant/get_restaurant_details/?={$restaurant['id']}'>{$restaurant['name']}</a>
					</h3>
					<p>{$restaurant['address']}</p>
					<p>{$restaurant['city']}, {$restaurant['state']}</p>
					<ul class='categories'>
						<li>Affordability</li>
						<li>Ambiance</li>
						<li>Food Quality</li>
						<li>Service</li>
					</ul>
					<ul class='ratings'>
						<li><img src='/ci/assets/images/{$restaurant[1]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[2]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[3]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[4]}_star.png' alt'star_ratings' /></li>
					</ul>
				</div>";
    	}
    	
    	return $this->html['list'];
	}

	public function process_inside_search()
	{	
		$this->inside_search['location'] = $this->input->post('location');

		for ($i=1; $i < 5; $i++)
		{
			if($this->input->post($i) === "checked")
			{
				$this->checked_box[$i] = $this->input->post($i);
			}
		}
		$this->session->set_userdata('checked_box', $this->checked_box);
		$this->html['placeholder'] = $this->inside_search['location'];
		$this->html['check'] = $this->checked_box;
		$this->html['list'] = $this->get_restaurants_list_inside($this->inside_search);
		$this->html['title'] = "List of Restaurants";
		echo json_encode($this->html);
	}

	public function get_restaurants_list_inside($search)
	{
		$this->load->model('Restaurant_model');
		$this->restaurants_results = $this->Restaurant_model->get_restaurants_info_inside($search);
		$this->checked_box = $this->session->userdata('checked_box');
		if(! empty($this->restaurants_results))
		{		
			foreach($this->restaurants_results as $restaurant)
			{
				$ratings = 0;
				for($i=1; $i<5; $i++)
				{
					if(isset($this->checked_box[$i]))
					{
						$ratings += $restaurant[$i];
					}
				}

				$this->total_average[strval($restaurant['id'])] = $ratings;

				arsort($this->total_average);
			}

			foreach ($this->total_average as $key => $value) 
			{
				foreach ($this->restaurants_results as $restaurant) 
				{
					if($key == $restaurant['id'])
					{
						$this->restaurants_sorted[] = $restaurant;
					}
				}
			}

			$this->html['list'] = "";
			$this->session->set_userdata('restaurants_sorted', $this->restaurants_sorted);

			foreach($this->restaurants_sorted as $restaurant)
	    	{
	    		$this->html['list'] .= "
		    		<div class='restaurant_info'>
						<h3>
							<img class='float_left' src='{$restaurant['image_path']}' alt='restaurant image'>
							<a href='/ci/restaurant/get_restaurant_details/?={$restaurant['id']}'>{$restaurant['name']}</a>
						</h3>
						<p>{$restaurant['address']}</p>
						<p>{$restaurant['city']}, {$restaurant['state']}</p>
						<ul class='categories'>
							<li>Affordability</li>
							<li>Ambiance</li>
							<li>Food Quality</li>
							<li>Service</li>
						</ul>
						<ul class='ratings'>
							<li><img src='/ci/assets/images/{$restaurant[1]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[2]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[3]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[4]}_star.png' alt'star_ratings' /></li>
						</ul>
					</div>";
	    	}
	    	return $this->html['list'];
		}
		else
		{
			$this->html['error_message'] = "Please put in a valid input!";
			return $this->html['list'];
		}
	}

	public function process_inside_search_details()
	{	
		$this->inside_search['location'] = $this->input->post('location');

		for ($i=1; $i < 5; $i++)
		{
			if($this->input->post($i) === "checked")
			{
				$this->checked_box[$i] = $this->input->post($i);
			}
		}
		$this->session->set_userdata('checked_box', $this->checked_box);
		$this->html['placeholder'] = $this->inside_search['location'];
		$this->html['check'] = $this->checked_box;
		$this->html['list'] = $this->get_restaurants_list_inside_details($this->inside_search);
		$this->html['title'] = "List of Restaurants";
		echo json_encode($this->html);
	}

	public function get_restaurants_list_inside_details($search)
	{
		$this->load->model('Restaurant_model');
		$this->restaurants_results = $this->Restaurant_model->get_restaurants_info_inside($search);
		$this->checked_box = $this->session->userdata('checked_box');
		if(! empty($this->restaurants_results))
		{		
			foreach($this->restaurants_results as $restaurant)
			{
				$ratings = 0;
				for($i=1; $i<5; $i++)
				{
					if(isset($this->checked_box[$i]))
					{
						$ratings += $restaurant[$i];
					}
				}

				$this->total_average[strval($restaurant['id'])] = $ratings;

				arsort($this->total_average);
			}

			foreach ($this->total_average as $key => $value) 
			{
				foreach ($this->restaurants_results as $restaurant) 
				{
					if($key == $restaurant['id'])
					{
						$this->restaurants_sorted[] = $restaurant;
					}
				}
			}

			$this->session->set_userdata('restaurants_sorted', $this->restaurants_sorted);
			$this->html['list'] = "";

			foreach($this->restaurants_sorted as $restaurant)
	    	{
	    		$this->html['list'] .= "
		    		<div class='restaurant_info'>
						<h3>
							<img class='float_left' src='{$restaurant['image_path']}' alt='restaurant image'>
							<a href='/ci/restaurant/get_restaurant_details/?={$restaurant['id']}'>{$restaurant['name']}</a>
						</h3>
						<p>{$restaurant['address']}</p>
						<p>{$restaurant['city']}, {$restaurant['state']}</p>
						<ul class='categories'>
							<li>Affordability</li>
							<li>Ambiance</li>
							<li>Food Quality</li>
							<li>Service</li>
						</ul>
						<ul class='ratings'>
							<li><img src='/ci/assets/images/{$restaurant[1]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[2]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[3]}_star.png' alt'star_ratings' /></li>
							<li><img src='/ci/assets/images/{$restaurant[4]}_star.png' alt'star_ratings' /></li>
						</ul>
					</div>";
	    	}
	    	return $this->html['list'];
		}
		else
		{
			$this->html['error_message'] = "Please put in a valid input!";
			return $this->html['list'];
		}
	}

	public function get_restaurant_details()
	{
		$this->id = $_SERVER['REQUEST_URI'];
		$this->id = explode("=", $this->id);
		$this->session->set_userdata('current_restaurant_id', $this->id[1]);

		$this->restaurants_info['restaurants_results'] = $this->session->userdata('restaurants_sorted');
		$this->restaurants_sorted = $this->restaurants_info['restaurants_results'];
		
		$this->html['list'] = "";
		foreach($this->restaurants_sorted as $restaurant) 
		{
			if($restaurant['id'] === $this->id[1])
			{
				$this->html['detailed'] = "
					<h3 id='restaurant_name'>
						<img class='float_left' src='{$restaurant['image_path']}' alt='restaurant_image'>
						<a href=''>{$restaurant['name']}</a>
					</h3>
					<p>{$restaurant['address']}</p>
					<p>{$restaurant['city']}, {$restaurant['state']}</p>
					<a data-toggle='modal' href='#rate' class='rate_button btn btn-primary btn-lg'>Rate this restaurant</a>
					<a class='clear float_left' href=''>{$restaurant['website']}</a>
						<div class='modal fade' id='rate'>
						  <div class='modal-dialog'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						        <h4 class='modal-title'>{$restaurant['name']}</h4>
						      </div>
						      <div class='modal-body' id='ratings'>
						      	 <div id='error'></div>
						      	 <ul>
						  			<li>Affordability: 
						  				<ul id='1' class='star_rating'>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</li>
						  			<li>Ambiance: 
						  				<ul id='2' class='star_rating'>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</li>
						  			<li>Food Quality: 
						  				<ul id='3' class='star_rating'>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</li>
						  			<li>Service: 
						  				<ul id='4' class='star_rating'>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</li>
						  		</ul>
						  		<button type='button' class='btn btn-default float_left' data-dismiss='modal'>Close</button>
						        <form id='rating_form' action='/ci/restaurant/process_ratings' method='post'>
						        	<input type='hidden' name='restaurant_id' value='{$this->id[1]}'/>
						        	<input type='hidden' name='1' value=''/>
						        	<input type='hidden' name='2' value=''/>
						        	<input type='hidden' name='3' value=''/>
						        	<input type='hidden' name='4' value=''/>
						        	<div class='input-group col-lg-4 float_left'>
									  <span class='input-group-addon required'></span>
									  <input type='text' name='name' class='form-control input-sm' placeholder='Name'>
									</div>
									<div class='input-group col-lg-4'>
									  <span class='input-group-addon required'></span>
									  <input type='email' name='email' class='form-control input-sm' placeholder='Email'>
									</div>
									<input class='btn btn-primary' type='submit' name='rating' value='RATE'>
								</form>
						      </div>
						    </div><!-- end of modal-content -->
						  </div><!-- end of modal-dialog -->
						</div><!-- end of modal -->
					</div>
					<h3 class='clear float_left'>Reviews</h3>
				  	<!-- Restaurant detailed page - left restaurant details - rating section -->
				  	<div id='reviews' class='clear float_left'>
			  			<div id='success_message'>";			  			
					  		$success_message = $this->session->flashdata('success_message');
				  				if(isset($success_message))
				  				{
				  					$this->html['detailed'] .="{$success_message}";
				  				}			  			
			  		$this->html['detailed'] .="</div>	
				  		<ul>
				  			<li>Category: <a href='#'>Italian</a></li>
				  			<li>Service: <img src='/ci/assets/images/{$restaurant[1]}_star.png' alt'star_ratings' /></li>
				  			<li>Ambiance: <img src='/ci/assets/images/{$restaurant[2]}_star.png' alt'star_ratings' /></li>
				  			<li>Food Quality: <img src='/ci/assets/images/{$restaurant[3]}_star.png' alt'star_ratings' /></li>
				  			<li>Affordability: <img src='/ci/assets/images/{$restaurant[4]}_star.png' alt'star_ratings' /></li>
				  		</ul>";
			}
			else
			{
				$this->html['list'] .= "
	    		<div class='restaurant_info'>
					<h3>
						<img class='float_left' src='{$restaurant['image_path']}' alt='restaurant image'>
						<a href='/ci/restaurant/get_restaurant_details/?={$restaurant['id']}'>{$restaurant['name']}</a>
					</h3>
					<p>{$restaurant['address']}</p>
					<p>{$restaurant['city']}, {$restaurant['state']}</p>
					<ul class='categories'>
						<li>Affordability</li>
						<li>Ambiance</li>
						<li>Food Quality</li>
						<li>Service</li>
					</ul>
					<ul class='ratings'>
						<li><img src='/ci/assets/images/{$restaurant[1]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[2]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[3]}_star.png' alt'star_ratings' /></li>
						<li><img src='/ci/assets/images/{$restaurant[4]}_star.png' alt'star_ratings' /></li>
					</ul>
				</div>";
			}
		}

		$this->html['reviews'] = $this->get_reviews();
		$this->html['title'] = "Detailed Restaurant Page";
		$this->load->view('restaurant_details.php', $this->html);
	}
	
	public function process_ratings()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$this->messages['error'] = NULL;

		if(empty($name))
		{
			$this->messages['error'] .= "<p class='error'>Name field cannot be blank!</p>";
		}
		
		if(empty($email))
		{
			$this->messages['error'] .= "<p class='error'>Email field cannot be blank!</p>";
		}

		if($this->messages['error'] == NULL)
		{
			$this->user_info['name'] = $this->input->post('name');
			$this->user_info['email'] = $this->input->post('email');

			$this->load->model('Restaurant_model');
			$this->ratings['user_id'] = $this->Restaurant_model->check_user_id($this->user_info);
			

			$this->ratings['restaurant_id'] = $this->input->post('restaurant_id');

			// loop to insert ratings
			for($i = 1; $i <= 4; $i++)
			{
				$this->ratings['category_id'] = $i;
				$this->ratings['rating'] = $this->input->post($i);
				$times_rated_full = $this->Restaurant_model->add_ratings($this->ratings);				
			}

			if(isset($times_rated_full))
			{
				$this->messages['error'] .= "<p class='error'>You have rated this restaurant already 3 times for today<br/>
											Please try again tomorrow!</p>";
				
				echo json_encode($this->messages);
			}
			else
			{			
				$this->user_info['restaurant_id'] = $this->input->post('restaurant_id');
				$this->messages['success_message'] = "<p>Ratings successfuly added!</p>";
				$this->session->set_flashdata('success_message', $this->messages['success_message']);

				// $this->user_info['restaurant_new_ratings'] = $this->Restaurant_model->get_restaurants_ratings_list($this->ratings['restaurant_id']);
				// $this->session->set_userdata('restaurant_new_ratings', $this->user_info['restaurant_new_ratings']);
				// $this->get_updated_restaurant_details($this->user_info['restaurant_id']);
				
				echo json_encode($this->user_info);
			}
		}
		else
		{
			echo json_encode($this->messages);
		}
	}

	public function process_comment()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$title = $this->input->post('title');
		$comment = $this->input->post('comment');
		$this->messages['error'] = NULL;

		if(empty($name))
		{
			$this->messages['error'] .= "<p class='error'>Name field cannot be blank!</p>";
		}
		
		if(empty($email))
		{
			$this->messages['error'] .= "<p class='error'>Email field cannot be blank!</p>";
		}

		if(empty($title))
		{
			$this->messages['error'] .= "<p class='error'>Title field cannot be blank!</p>";
		}

		if(empty($comment))
		{
			$this->messages['error'] .= "<p class='error'>Comment field cannot be blank!</p>";
		}

		if($this->messages['error'] == NULL)
		{
			$this->user_info['name'] = $this->input->post('name');
			$this->user_info['email'] = $this->input->post('email');

			$this->load->model('Restaurant_model');
			$this->comment['user_id'] = $this->Restaurant_model->check_user_id($this->user_info);
			$this->comment['restaurant_id'] = $this->session->userdata('current_restaurant_id');
			$this->comment['title'] = $this->input->post('title');
			$this->comment['comment'] = $this->input->post('comment');

			$this->Restaurant_model->add_comment($this->comment);

			$this->messages['success'] = "<p id='success'>Comment successfuly submited!</p>";
			$this->messages['restaurant_id'] = $this->comment['restaurant_id'];
			$this->session->set_flashdata('comment_success', $this->messages['success']);

			echo json_encode($this->messages);
		}
		else
		{
			echo json_encode($this->messages);
		}	
	}

	public function get_reviews()
	{
		$html['rating_comment'] = '';
		$html['comment'] = '';
		$html['rating'] = '';
		$current_restaurant_id = $this->session->userdata('current_restaurant_id');
		$this->load->model('Restaurant_model');
		$total_users_reviews = $this->Restaurant_model->get_reviews($current_restaurant_id);

		foreach($total_users_reviews as $array_1)
		{		
			foreach($array_1 as $array_2) 
			{
				if(array_key_exists('title', $array_2)) 
				{
				  $user_ids['comments'][] = $array_2['user_id'];
				  $comments_array[] = $array_2;
				}
				else
				{
					$user_ids['ratings'][] = $array_2['user_id'];
					$ratings_array[] = $array_2;
				}
			}			
		}
		 
		$new_ratings_array = array_chunk($ratings_array, 4);
		
		foreach($new_ratings_array as $one_user) 
		{
			foreach($one_user as $one_rating) 
			{
				$total_rating['user_id'] = $one_rating['user_id'];
				$total_rating['name'] = $one_rating['name'];
				$total_rating['email'] = $one_rating['email'];

				if($total_rating['user_id'] == $one_rating['user_id'])
				{		
					$total_rating[] = $one_rating['rating'];

					if(isset($total_rating[0]))
					{
						$total_rating[1] = $total_rating[0];
						unset($total_rating[0]);
					}
				}
			}
			$new_total_ratings[$total_rating['user_id']] = $total_rating;
			unset($total_rating);
		}
		
		if(isset($comments_array))
		{
			foreach ($comments_array as $one_comment) 
			{
				if(isset($new_total_ratings))
				{
					$array_total_items = count($new_total_ratings);
					$times_through_array = 1;
					foreach($new_total_ratings as $one_user) 
					{
						$result = array_search("{$one_comment['user_id']}", $one_user);
						if(!$result)
						{
							if($times_through_array === $array_total_items)
							{
								$html['comment'] .= "<div class='comment' id='".$one_comment['user_id']."'>
							  					<p>{$one_comment['name']}</p>
												<p><a href=''>{$one_comment['email']}</a></p>
												<p>{$one_comment['title']}</p>
												<p>{$one_comment['comment']}</p>
											</div>";
							}
							$times_through_array++;
						}
					}
				}
			}
		}
		
		foreach($new_total_ratings as $one_user) 
		{
			unset($equal_comment);
			if(isset($comments_array))
			{
				foreach ($comments_array as $one_comment)
				{
					if($one_user['user_id'] != $one_comment['user_id'])
					{
						if(isset($equal_comment))
						{
							unset($equal_comment);
							break;
						}
						else
						{
							$array_total_items = count($comments_array);
							$times_through_array = 1;
							foreach ($comments_array as $one_comment) 
							{
								$result = array_search("{$one_user['user_id']}", $one_comment);

								if($result != FALSE)
								{
									$html['rating_comment'] .= "<div class='comment' id='".$one_user['user_id']."'>
						  					<p>{$one_user['name']}</p>
											<p><a href=''>{$one_user['email']}</a></p>
											<ul class='categories'>
												<li>Affordability</li>
												<li>Ambiance</li>
												<li>Food Quality</li>
												<li>Service</li>
											</ul>
											<ul class='ratings'>
												<li><img src='/ci/assets/images/{$one_user[1]}_star.png' alt'star_ratings' /></li>
												<li><img src='/ci/assets/images/{$one_user[2]}_star.png' alt'star_ratings' /></li>
												<li><img src='/ci/assets/images/{$one_user[3]}_star.png' alt'star_ratings' /></li>
												<li><img src='/ci/assets/images/{$one_user[4]}_star.png' alt'star_ratings' /></li>
											</ul>";
				
									$html['rating_comment'] .= "<p>{$one_comment['title']}</p>
													<p>{$one_comment['comment']}</p>
												</div>";
									break 2;
								}
								else
								{
									if($times_through_array == $array_total_items)
									{
										$html['rating'] .= "<div class='comment' id='".$one_user['user_id']."'>
											  					<p>{$one_user['name']}</p>
																<p><a href=''>{$one_user['email']}</a></p>
																<ul class='categories'>
																	<li>Affordability</li>
																	<li>Ambiance</li>
																	<li>Food Quality</li>
																	<li>Service</li>
																</ul>
																<ul class='ratings'>
																	<li><img src='/ci/assets/images/{$one_user[1]}_star.png' alt'star_ratings' /></li>
																	<li><img src='/ci/assets/images/{$one_user[2]}_star.png' alt'star_ratings' /></li>
																	<li><img src='/ci/assets/images/{$one_user[3]}_star.png' alt'star_ratings' /></li>
																	<li><img src='/ci/assets/images/{$one_user[4]}_star.png' alt'star_ratings' /></li>
																</ul>
															</div>";
										break 2;
									}
									$times_through_array++;
								}
							}
						}
					}
					elseif($one_comment['user_id'] === $one_user['user_id'])
					{
						$html['rating_comment'] .= "<div class='comment' id='".$one_user['user_id']."'>
					  					<p>{$one_user['name']}</p>
										<p><a href=''>{$one_user['email']}</a></p>
										<ul class='categories'>
											<li>Affordability</li>
											<li>Ambiance</li>
											<li>Food Quality</li>
											<li>Service</li>
										</ul>
										<ul class='ratings'>
											<li><img src='/ci/assets/images/{$one_user[1]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[2]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[3]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[4]}_star.png' alt'star_ratings' /></li>
										</ul>";
			
						$html['rating_comment'] .= "<p>{$one_comment['title']}</p>
										<p>{$one_comment['comment']}</p>
									</div>";
						$equal_comment = TRUE;

					}			  
				}								
			}
			else
			{
				$html['rating'] .= "<div class='comment' id='".$one_user['user_id']."'>
					  					<p>{$one_user['name']}</p>
										<p><a href=''>{$one_user['email']}</a></p>
										<ul class='categories'>
											<li>Affordability</li>
											<li>Ambiance</li>
											<li>Food Quality</li>
											<li>Service</li>
										</ul>
										<ul class='ratings'>
											<li><img src='/ci/assets/images/{$one_user[1]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[2]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[3]}_star.png' alt'star_ratings' /></li>
											<li><img src='/ci/assets/images/{$one_user[4]}_star.png' alt'star_ratings' /></li>
										</ul>
									</div>";
			}
		}
		return $html;
	}
}

/* End of file restaurant.php */
/* Location: /application/controllers/restaurant.php */