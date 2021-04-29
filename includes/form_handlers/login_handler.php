<?php 
	if(isset($_POST['log_button'])){
		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //sanitize email

		$_SESSION['log_email'] = $email; //Store email into session variable
		$password = md5($_POST['log_pwd']); //Get password
		$check_database_query = $bdd->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
		
		//Count the number of rows returned
		$check_login_query = $check_database_query -> rowCount();
		if($check_login_query == 1){
			$row =  $check_database_query -> fetch();
			$username = $row['username'];
			$_SESSION['username'] = $username;

			$check_database_query->closeCursor();
			$user_closed_query = $bdd -> query("SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
			if($user_closed_query -> rowCount()){
				$reopen_account = $bdd -> prepare("UPDATE users SET user_closed='no' WHERE email='$email'");
				$reopen_account -> execute();
			}
			$user_closed_query->closeCursor();
			header("Location: index.php");
			exit();
		}else{
			array_push($error_array, "E-mail or Password was Incorrect!!<br/>");
		}
	}

?>