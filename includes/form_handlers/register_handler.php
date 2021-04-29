<?php

//Declaring Variables
		$fname = ""; //First Name
		$lname = ""; //Last Name
		$email = ""; //Email
		$email2 = ""; //Confirm Email
		$pwd = ""; //Password
		$pwd2 = ""; //Confirm Password
		$date = ""; //Sign up date
		$error_array = array(); //Holds error messages!
		if(isset($_POST['reg_button'])){
			//Registration form values
			//first name:
			$fname = strip_tags($_POST['reg_fname']); //remove html tags
			$fname = str_replace(" ", "", $fname); //remove spaces
			$fname = ucfirst(strtolower($fname)); //Uppercase first letter
			$_SESSION['reg_fname'] = $fname; //stores first name into session variables
			//last name:
			$lname = strip_tags($_POST['reg_lname']); //remove html tags
			$lname = str_replace(" ", "", $lname); //remove spaces
			$lname = ucfirst(strtolower($lname)); //Uppercase first letter
			$_SESSION['reg_lname'] = $lname; //stores last name into session variables
			//E-mail:
			$email = strip_tags($_POST['reg_email']); //remove html tags
			$email = str_replace(" ", "", $email); //remove spaces
			$email = ucfirst(strtolower($email)); //Uppercase first letter
			$_SESSION['reg_email'] = $email; //stores E-mail into session variables
			//Confirm E-mail:
			$email2 = strip_tags($_POST['reg_email2']); //remove html tags
			$email2 = str_replace(" ", "", $email2); //remove spaces
			$email2 = ucfirst(strtolower($email2)); //Uppercase first letter
			$_SESSION['reg_email2'] = $email2; //stores E-mail2 into session variables
			//Password :
			$pwd = strip_tags($_POST['reg_password']); //remove html tags
			//Confirm Password :
			$pwd2 = strip_tags($_POST['reg_password2']); //remove html tags
			//Date
			$date = date('Y-m-d'); //Current Date
			if($email == $email2){
				//checking if email is in valid format
				if(filter_var($email,FILTER_VALIDATE_EMAIL)){
					$email = filter_var($email,FILTER_VALIDATE_EMAIL);
					//check if email already exists
					$e_check = $bdd->query("SELECT email FROM users WHERE email='$email'");
					//Count the number of rows returned
					$num_rows = $e_check->rowCount();
					if($num_rows > 0){
						array_push($error_array, "Email already in use !!<br/>");
					}
					$e_check->closeCursor();
				}else{
					array_push($error_array, "Invalid E-mail Format!!<br/>");
				}
			}else{
				array_push($error_array, "E-mails don't match !!<br/>");
			}
			if(strlen($fname) > 25 || strlen($fname) < 2){
				array_push($error_array, "Your First Name must be between 2 and 25 Characters<br/>");
			}
			if(strlen($lname) > 25 || strlen($lname) < 2){
				array_push($error_array, "Your Last Name must be between 2 and 25 Characters<br/>");
			}
			if($pwd != $pwd2){
				array_push($error_array, "Your Passwords do not match !!<br/>");
			}else{
				if(preg_match('/[^A-Za-z0-9]/', $pwd)){
					array_push($error_array, "Your Password can only contain English Characters or numbers!!<br/>");
				}
			}
			if(strlen($pwd) > 30 || strlen($pwd) < 5){
				array_push($error_array, "Your Password must be between 5 and 30 Characters<br/>");
			}
			if(empty($error_array)){
				$pwd = md5($pwd); //Encrypt password before sending to database
				//Generate username by concatenating first name and last name 
				$username = strtolower($fname . "_" . $lname);
				$check_user_query = $bdd->query("SELECT username FROM users WHERE username='$username'");
				$i = 0;
				//if username already exist, so we change it
				while($check_user_query -> rowCount() != 0){
					$i++; //Increment i
					$username = $username . "_" . $i;
					$check_user_query = $bdd->query("SELECT username FROM users WHERE username='$username'");
				}
				$check_user_query->closeCursor();
				//profile picture assignment 
				$rand = rand(1,2);//random number between 1 & 2
				if($rand == 1) $profile_pic = "assets/img/profile_pics/defaults/head_deep_blue.png";
				elseif ($rand == 2) $profile_pic = "assets/img/profile_pics/defaults/head_emerald.png";
				//
				$query = $bdd->prepare("INSERT INTO users VALUES ('','$fname','$lname','$username','$email','$pwd','$date','$profile_pic','0','0','no',',')");
					$query -> execute();
					array_push($error_array, "<span style='color:#14C800;'>You're all set! Go ahead and login!</span><br/>");
					//Clear session variables
					$_SESSION['reg_fname']="";
					$_SESSION['reg_lname']="";
					$_SESSION['reg_email']="";
					$_SESSION['reg_email2']="";
					$query->closeCursor();
			}
		}
?>