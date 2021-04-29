<?php 
	
	class Post{
		private $user_obj;
		private $bdd;

		public	function __construct($bdd, $user)
		{
			$this->bdd = $bdd;
			$this->user_obj = new User($bdd, $user);
		}
		public function submitPost($body, $user_to){
			$body = strip_tags($body); //removes html tags 
			// $body = mysqli_real_escape_string($this->bdd, $body);
			$check_empty = preg_replace('/\s+/', '', $body); //delete all spaces 
			if($check_empty != ""){
				//current date and time
				$date_added = date("Y-m-d H:i:s");
				//get username
				$added_by = $this->user_obj->getUsername();
				//if user is on his own profile, user_to is 'none'
				if($user_to == $added_by){
					$user_to = 'none';
				}
				//Insert post
				$query = $this->bdd -> prepare("INSERT INTO posts VALUES('','$body','$added_by','$user_to','$date_added','no','no','0')");
				$query->execute();
				$returned_id = $this->bdd -> lastInsertId();
				//Insert notification
				//Update post count for user
				$num_posts = $this->user_obj->getNumPosts();
				$num_posts++;
				$update_query = $this->bdd ->prepare("UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
				$update_query -> execute();
			}

		}
		public function loadPostsFriends($data, $limit){
			$page = $data['page']; 
			$userLoggedIn = $this->user_obj->getUsername();

			if($page == 1) 
				$start = 0;
			else 
				$start = ($page - 1) * $limit;

			$str = "";
			$data_query = $this->bdd -> query("SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
			//Count the number of rows returned
			$num_rows = $data_query->rowCount();
			if($num_rows > 0){
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = $data_query-> fetch()){
					$id = $row['id'];
					$body = $row['body'];
					$added_by = $row['added_by'];
					$date_time = $row['date_added'];
					//Prepare user_to string so it can be included even if not posted to a user
					if($row['user_to'] == "none"){
						$user_to = "";

					}else{
						$user_to_obj = new User($bdd, $row['user_to']);
						$user_to_name = $user_to_obj -> getFirstAndLastName();
						$user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
					}
					//check if user who posted, has their account closed
					$added_by_obj = new User($this->bdd, $added_by);
					if($added_by_obj->isClosed()){
						continue;
					}
					if($num_iterations++ < $start)
						continue; 

					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					$user_details_query = $this->bdd->query("SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = $user_details_query->fetch();
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time);
					$end_date = new DateTime($date_time_now);
					$interval = $start_date->diff($end_date);
					if($interval->y >= 1){
						if($interval ==1){
							$time_message = $interval->y." year ago"; //1 year ago
						}else{
							$time_message = $interval->y." years ago";//1+ year ago
						}
					}else if($interval->m >=1){
						if($interval->d == 0){
							$days = " ago";
						}else if($interval->d ==1){
							$days = $interval->d . " day ago";
						}else{
							$days = $interval->d . " days ago";
						}
						if($interval->m ==1){
							$time_message = $interval->m." month".$days;
						}else{
							$time_message = $interval->m." months".$days;
						}
					}else if($interval->d >=1){
						if($interval->d==1){
							$time_message="Yesterday";
						}else{
							$time_message = $interval->d." days ago";
						}
					}else if($interval->h >= 1){
						if($interval->h ==1){
							$time_message = $interval->h." hour ago";
						}else{
							$time_message = $interval->h." hours ago";
						}
					}else if($interval->i >= 1){
						if($interval->i ==1){
							$time_message = $interval->i." minute ago";
						}else{
							$time_message = $interval->i." minutes ago";
						}
					}else{
						if($interval->s <30){
							$time_message = "Just now";
						}else{
							$time_message = $interval->s." seconds ago";
						}
					}
					$str .= "<div class='status_post'>
									<div class='post_profile_pic'>
										<img src='$profile_pic' width='50'>
									</div>

									<div class='posted_by' style='color:#ACACAC;'>
										<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
									</div>
									<div id='post_body'>
										$body
										<br>
									</div>

								</div>
								<hr>";
				} //End while loop
				if($count > $limit) {
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
				}
				else {
					$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
				}
			}
			echo $str;
		}
	}
?>