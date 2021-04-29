<?php 
	
	class User{
		private $user;
		private $bdd;
		public	function __construct($bdd, $user)
		{
			$this->bdd = $bdd;
			$user_details_query = $bdd -> prepare("SELECT * FROM users WHERE username='$user'");
			$user_details_query->execute();
			$this->user = $user_details_query -> fetch();
		}
		public function getUsername(){
			return $this->user['username'];
		}
		public function getNumPosts(){
			$username = $this->user['username'];
			$query = $this->bdd -> prepare("SELECT num_posts FROM users WHERE username='$username'");
			$query->execute();
			$row = $query->fetch();
			return $row['num_posts'];
		}
		public function getFirstAndLastName(){
			$username = $this->user['username'];
			$query =$this->bdd -> query("SELECT first_name, last_name FROM users WHERE username='$username'");
			$row = $query -> fetch();
			return $row['first_name'] . " " . $row['last_name'];
		}
		public function isClosed(){
			$username = $this->user['username'];
			$query = $this->bdd->query("SELECT user_closed FROM users WHERE username='$username'");
			$row = $query->fetch();
			if($row['user_closed'] == 'yes')
				return true;
			else return false;
		}
	}
?>