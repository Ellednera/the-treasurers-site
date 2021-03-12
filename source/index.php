<?php
// echo md5("john2000");
// exit;


// start session
session_start();

				// echo "<pre>";
				// var_dump($_SESSION);
				// echo "</pre>";
// redirections
if(isset($_SESSION["stu_id"]) && isset($_SESSION["actual_name"])){
	// echo "true";
	// exit;
	header("Location: home.php");
}
// for login
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// use simpler variables
	$id = $_POST["id"];
	$pswd = $_POST["password"];
	
	// connect to database
	$con = mysqli_connect("localhost", "root", "", "tts");
	// var_dump($con);
	
	// connection
	if($con){
	// successfully connected to the database
		// sql statement to check for the right id and password
		$sql = "
				SELECT * FROM `students` 
				WHERE 
				`stu_id`='" . $id . "' AND `pswd`='" . md5($pswd) . "' AND `stu_deleted`='0';
				";
				
		// var_dump($sql);
		// exit;
		
		// execute sql
		$result = mysqli_query($con, $sql);
		
		// var_dump($result);
		// exit;
		
		// if the sql statement returns a result/executes successfully
		if($result){
		
			// fetch data
			$data = mysqli_fetch_array($result, MYSQLI_ASSOC); // boolean
			
			// close connection 
			mysqli_close($con);
			
			// to check validity of login
			if($data){
			// if valid
			
				// echo "<pre>";
				// var_dump($data);
				// echo "</pre>";
				// exit;
				
				// set variables into a session
				$_SESSION["id"] = $data["stu_id"];
				$_SESSION["stu_id"] = $data["stu_id"];
				$_SESSION["actual_name"] = $data["actual_name"];
				$_SESSION["username"] = $data["username"];
				$_SESSION["role"] = $data["role"];
				$_SESSION["c_name"] = $data["c_name"];
				
				// echo "<pre>";
				// var_dump($_SESSION);
				// echo "</pre>";
				
				// set view then redirect to the home page
				$_SESSION["view"] = $data["role"];
				// header("Location: home.php?view=" . $_SESSION["view"] . "&p=1");
				header("Location: home.php");

			}else{
			// if invalid - set error message
			$login_err_msg = "Invalid id and/or password";
			}

		}
		
	}else{
	// failed to connect to the database
		exit("Oops! Something went wrong with the connection. Please try again later.");;
	}
	
}

?>
<!DOCTYPE html>

<html lang="en">

<head>
	<title>The Treasurer's Site</title>
	<link href="css/index.css" type="text/css" rel="stylesheet"><!-- css -->
	<!--<link href="" type="" rel="icon"> icon -->
	<meta charset="UTF-8">
</head>

<body>
	
	<div class="container">
		
		<!-- title -->
		<h1>The Treasurer's Site</h1>
		
		<!-- about us -->
		<div class="about-us">
			<h2>About Us</h2>
			<p>
				The Treasurer's Site is a web application which lets treasurers (or to be specific--school treasurers) manage all 
				the payments students need to make.
			</p>
			<p>
				Have you, as a treasuere, faced the following problems before?
			</p>
				<ol>
					<li>Classmates who do not know that certain payment exists</li>
					<li>Classmates who keep forgetting to make payments</li>
					<li>Classmates who do not read the notice board</li>
					<li>Tired of nagging you classmates to make their payments</li>
					<li>Tired of calculating your class fund</li>
				</ol>
			<p>
				If so, this is the right place for you! This application might not be able to solve all your problems as a treasurer, 
				but it sure will help solve most of your problems.
			</p>
			
		</div>
		
		<!-- login form's container -->
		<div class="login">
			
			<!-- login form -->
			<form method="post">
			
				<h2>Log In</h2>
				
				User id:<br>
				<input type="text" name="id" required><br>
				Password:<br>
				<input type="password" name="password" required><br>
				
				<!-- log in button -->
				<input type="submit" value="Log In">

			<?php
			// if login id is invalid, display error message
			if(isset($login_err_msg)){
				echo "<span class='login-err'>";
				echo $login_err_msg;
				echo "</span>";
			}
			?>
			</form>
		</div>
		
			<!-- notes-->
			<h4>NOTES</h4>
			<ol>
				<li>
					Please note that this application is currently available for SMJK Kuching High students only.
				</li>
				<li>
					To avoid conflicting accounts, school treasurers are not able to register their account themselves. If you would
					like to use this application, please contact the developer through:<br>
					Raphael Jong (+6 019-816 8068) - messages only
				</li>
				<li>
					This application is a beta version and is still developing. Do feel free to give some feedbacks or report any bugs.
				</li>
				<li>
					This application is currently not mobile-compatible. Sorry for the inconvenience caused.
				</li>
			</ol>
		
	</div>
	
	
	
	
</body>

</html>