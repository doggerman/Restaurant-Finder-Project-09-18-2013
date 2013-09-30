<?php

class Restaurant_model extends CI_Model {

    protected $check_user;
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_restaurants_info($restaurant_search)
    {
    	//query for all restaurants info
    	$restaurants = $this->db->query("SELECT * FROM restaurants AS t1
							    		 WHERE t1.name LIKE '%{$restaurant_search['location']}%'
										 OR t1.address LIKE '%{$restaurant_search['location']}%' 
										 OR t1.city LIKE '%{$restaurant_search['location']}%' 
										 OR t1.state LIKE '%{$restaurant_search['location']}%' 
										 OR t1.country LIKE '%{$restaurant_search['location']}%' 
										 OR t1.zip_code = '{$restaurant_search['location']}'")->result_array();

    	foreach($restaurants as $restaurant)
    	{    		
	    	if(! preg_match("/\b{$restaurant_search['location']}\b/i", $restaurant['address']))
	    	{
	    		$restaurants_results_new[] = $restaurant;
	    	}
	    	else
	    	{
	    		$restaurants_same[] = $restaurant;
	    	}
    	}

    	if(isset($restaurants_results_new))
    	{	
	    	for($i = 0; $i<count($restaurants_results_new); $i++)
	    	{	
	    		$restaurants_results_new[$i] += $this->get_restaurants_ratings_list($restaurants_results_new[$i]['id']);  		
	    	}
	    	return $restaurants_results_new;		
    	}
    	elseif(isset($restaurants_same))
    	{
    		for($i = 0; $i<count($restaurants_same); $i++)
	    	{	
	    		$restaurants_same[$i] += $this->get_restaurants_ratings_list($restaurants_same[$i]['id']);  		
	    	}
	    	return $restaurants_same;
    	}
    }

    public function get_restaurants_info_inside($restaurant_search)
   	{
   		//query for all restaurants info
    	$restaurants = $this->db->query("SELECT * FROM restaurants AS t1
							    		 WHERE t1.name LIKE '%{$restaurant_search['location']}%'
										 OR t1.address LIKE '%{$restaurant_search['location']}%' 
										 OR t1.city LIKE '%{$restaurant_search['location']}%' 
										 OR t1.state LIKE '%{$restaurant_search['location']}%' 
										 OR t1.country LIKE '%{$restaurant_search['location']}%' 
										 OR t1.zip_code = '{$restaurant_search['location']}'")->result_array();

    	foreach($restaurants as $restaurant)
    	{    		
	    	if(! preg_match("/\b{$restaurant_search['location']}\b/i", $restaurant['address']))
	    	{
	    		$restaurants_results_new[] = $restaurant;
	    	}
	    	else
	    	{
	    		$restaurants_same[] = $restaurant;
	    	}
    	}

    	if(isset($restaurants_results_new))
    	{	
	    	for($i = 0; $i<count($restaurants_results_new); $i++)
	    	{	
	    		$restaurants_results_new[$i] += $this->get_restaurants_ratings_list($restaurants_results_new[$i]['id']);  		
	    	}
	    	return $restaurants_results_new;		
    	}
    	elseif(isset($restaurants_same))
    	{
    		for($i = 0; $i<count($restaurants_same); $i++)
	    	{	
	    		$restaurants_same[$i] += $this->get_restaurants_ratings_list($restaurants_same[$i]['id']);  		
	    	}
	    	return $restaurants_same;
    	}
   	}

    public function get_restaurants_ratings_list($restaurant_id)
    {	

    	//getting first (affordability) category ratings 
    	$total_people =  $this->db->query("SELECT COUNT(rating) AS count_people
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '1'")->row();
    	
    	$total_stars = $this->db->query("SELECT SUM(rating) AS sum_stars
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '1'")->row();
    	
    	if(floatval($total_people->count_people) != 0)
    	{	
    		$ratings['1'] = round((floatval($total_stars->sum_stars) / floatval($total_people->count_people))*2)/2;
    	}
    	else
    	{
    		$ratings['1'] = 0;
    	}
    	
    	//getting second (ambiance) category ratings 
    	$total_people =  $this->db->query("SELECT COUNT(rating) AS count_people
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '2'")->row();
    	
    	$total_stars = $this->db->query("SELECT SUM(rating) AS sum_stars
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '2'")->row();
    	
    	if(floatval($total_people->count_people) != 0)
    	{	
    		$ratings['2'] = round((floatval($total_stars->sum_stars) / floatval($total_people->count_people))*2)/2;
    	}
    	else
    	{
    		$ratings['2'] = 0;
    	}
    	

    	//getting third (food_quality) category ratings 
    	$total_people =  $this->db->query("SELECT COUNT(rating) AS count_people
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '3'")->row();
    	
    	$total_stars = $this->db->query("SELECT SUM(rating) AS sum_stars
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '3'")->row();
    	
    	if(floatval($total_people->count_people) != 0)
    	{	
    		$ratings['3'] = round((floatval($total_stars->sum_stars) / floatval($total_people->count_people))*2)/2;
    	}
    	else
    	{
    		$ratings['3'] = 0;
    	}

    	//getting fourth (service) category ratings 
    	$total_people =  $this->db->query("SELECT COUNT(rating) AS count_people
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '4'")->row();
    	
    	$total_stars = $this->db->query("SELECT SUM(rating) AS sum_stars
    							 FROM reviews
								 WHERE restaurant_id = '{$restaurant_id}' 
								 AND category_id = '4'")->row();
    	
		if(floatval($total_people->count_people) != 0)
    	{	
    		$ratings['4'] = round((floatval($total_stars->sum_stars) / floatval($total_people->count_people))*2)/2;
    	}
    	else
    	{
    		$ratings['4'] = 0;
    	}
    	return $ratings;
    }

    public function check_user_id($user_info)
    {
        $this->check_user = $this->db->query("SELECT * FROM users WHERE users.email = '{$user_info['email']}'")->row();

        if($this->check_user)
        {
            return $this->check_user->id;
        }
        else
        {
            $this->db->set('created_at', 'NOW()', FALSE);
            $this->db->insert('users', $user_info);
            return mysql_insert_id();
        }
    }

    public function add_ratings($rating_info)
    {

        $check_created_at = $this->db->query("SELECT id, restaurant_id, user_id, created_at, updated_at, times_rated FROM reviews
                                        WHERE reviews.user_id = '{$rating_info['user_id']}' 
                                        AND reviews.restaurant_id = '{$rating_info['restaurant_id']}'
                                        AND reviews.category_id = '{$rating_info['category_id']}'")->row();
        
        $times_rated = 0;
        $date = date('Y-m-d');

        if($check_created_at)
        {
            if($check_created_at->updated_at)
            {
                $find_date = strpos($check_created_at->updated_at, $date);
                if($find_date === false)
                {
                    $times_rated++;
                    $rating_info['times_rated'] = $times_rated;

                    $conditions = array('restaurant_id' => $rating_info['restaurant_id'], 'user_id' => $rating_info['user_id'], 'category_id' => $rating_info['category_id']); 

                    $this->db->where($conditions);
                    $this->db->set('updated_at', 'NOW()', FALSE);
                    $this->db->update('reviews', $rating_info);
                }
                else
                {
                    if($check_created_at->times_rated < 3)
                    {
                        $times_rated = $check_created_at->times_rated;
                        $times_rated++;
                        $rating_info['times_rated'] = $times_rated;

                        $conditions = array('restaurant_id' => $rating_info['restaurant_id'], 'user_id' => $rating_info['user_id'], 'category_id' => $rating_info['category_id']); 

                        $this->db->where($conditions);
                        $this->db->set('updated_at', 'NOW()', FALSE);
                        $this->db->update('reviews', $rating_info);
                    }
                    else
                    {
                        $times_rated_full = 3;
                        return $times_rated_full;
                    }   
                }
            }
            else
            {
                $find_date = strpos($check_created_at->created_at, $date);
                if($find_date === false)
                {
                    $times_rated++;
                    $rating_info['times_rated'] = $times_rated;

                    $conditions = array('restaurant_id' => $rating_info['restaurant_id'], 'user_id' => $rating_info['user_id'], 'category_id' => $rating_info['category_id']); 

                    $this->db->where($conditions);
                    $this->db->set('updated_at', 'NOW()', FALSE);
                    $this->db->update('reviews', $rating_info);
                }
                else
                {
                    if($check_created_at->times_rated < 3)
                    {
                        $times_rated = $check_created_at->times_rated;
                        $times_rated++;
                        $rating_info['times_rated'] = $times_rated;

                        $conditions = array('restaurant_id' => $rating_info['restaurant_id'], 'user_id' => $rating_info['user_id'], 'category_id' => $rating_info['category_id']); 

                        $this->db->where($conditions);
                        $this->db->set('updated_at', 'NOW()', FALSE);
                        $this->db->update('reviews', $rating_info);
                    }
                    else
                    {
                        $times_rated_full = 3;
                        return $times_rated_full;
                    }   
                }
            }      
        }
        else
        {
           $times_rated++;
           $rating_info['times_rated'] = $times_rated;

           $this->db->set('created_at', 'NOW()', FALSE);
           $this->db->insert('reviews', $rating_info);
        }
    }

    public function add_comment($comment_info)
    {
        $this->db->set('created_at', 'NOW()', FALSE);
        $this->db->insert('comments', $comment_info);
    }

    public function get_reviews($current_restaurant_id)
    {
        $total =  $this->db->query("SELECT COUNT(users.id) AS total
                                          FROM users")->result_array();
        $total_users = $total[0]['total'];

        for($i=1; $i<$total_users+1; $i++)
        {
            $users_ratings[$i] = $this->db->query("SELECT reviews.user_id, users.name, users.email, reviews.rating
                          FROM reviews
                          LEFT JOIN users
                          ON users.id = reviews.user_id
                          WHERE reviews.restaurant_id = '{$current_restaurant_id}'
                          AND reviews.user_id = '{$i}'")->result_array();

           $users_comments[$i] = $this->db->query("SELECT comments.user_id, users.name, users.email, comments.title, comments.comment
                           FROM comments
                           LEFT JOIN users
                           ON users.id = comments.user_id
                           WHERE comments.restaurant_id = '{$current_restaurant_id}'
                           AND comments.user_id = '{$i}'
                           ORDER BY comments.created_at DESC")->result_array();

           $total_users_reviews[] = array_merge($users_ratings[$i], $users_comments[$i]);   
        }
       return $total_users_reviews; 
    }
    
}
/* End of file restaurant_model.php */
/* Location:/application/models/restaurant_model.php */