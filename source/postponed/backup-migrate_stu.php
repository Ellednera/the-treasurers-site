<?php
session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
	<title>The Treasurer's Site</title>
	<link href="css/home.css" type="text/css" rel="stylesheet"><!-- css - home -->
	<link href="css/banner.css" type="text/css" rel="stylesheet"><!-- css - banner -->
	<link href="css/menu.css" type="text/css" rel="stylesheet"><!-- css - menu -->
	<link href="css/features.css" type="text/css" rel="stylesheet"><!-- css - features -->
	<link href="css/migrate_stu.css" type="text/css" rel="stylesheet"><!-- css - migrate students -->
	<!--<link href="" type="" rel="icon"> icon -->
	<meta charset="UTF-8">
</head>

<body>
	<?php
	
	// if the form is submitted, it must be processed here, so that the nth will have 
	// a new value
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts");
		
		// sql statement - add the new student into the database
		// By defuault, nth student's id = nth student's password
		// $c_name will be using the treasurer's class name as a reference
		$sql = "
				INSERT INTO `students`
				SET
				`stu_id`='" . $_SESSION["new_stu_id"] . "',
				`actual_name`='" . $_POST["name"] . "',
				`username`='" . $_POST["username"] . "',
				`pswd`='" . md5($_SESSION["new_stu_id"]) . "',
				`pswd_bu`='" . $_SESSION["new_stu_id"] . "',
				`role`='1',
				`c_name`='" . $_SESSION["c_name"] . "',
				`stu_deleted`='0';
				";
			// echo $sql;
			// exit;
		
		//execute sql statement
		$result = mysqli_query($con, $sql) or die("<br>Oops! Something went wrong. Please try again later.");
		
		// set success message - if the query is not correct, nothing below it will be executed
		$add_stu_msg = "Account for &#34;" . $_POST["name"] . "&#34; with id &#34;" . $_SESSION["new_stu_id"] . "&#34; successfully created!";
	}
	
	
	// generate new student's id
	//connect to database to retrieve the number of students in the specific class
	$con = mysqli_connect("localhost", "root", "", "tts");
	
	// sql statement
	$sql = "
			SELECT COUNT(*) AS `total` 
			FROM `students`
			WHERE `c_name`='" . $_SESSION["c_name"] . "';
			";
		// echo $sql;
		// exit;
	
	// execute sql
	$result = mysqli_query($con, $sql);
	
	// retrieve data
	$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
		// echo ++$data["total"];
		// exit;
		
	// close connection
	mysqli_close($con);
	
	// process the data 
	// set the nth number of the new student
	$nth = ++$data["total"];
	
	// number of characters must be at least 2 for nth student
	$length_nth = strlen($nth);
		// echo $length_nth;
		// exit;
	if($length_nth = 1){
		$nth = 0 . $nth;
	}
		// echo $nth;
		// exit;
	
		
	// extract the year and the class code from the treasurer's id
	// year      : 2th - 5th position
	// class code: 6th - 8th position
	$year = substr($_SESSION["id"], 2, 4);
	// save class name into a session for adding the values into the database
	$_SESSION["c_code"] = substr($_SESSION["id"], 6, 3);
		// echo $year . "<br>" . $c_code . "<br>" . $nth;
		// exit;
	
	// generate new id and password
	// the role of the students is always "S"
	// save the new id into a session for adding new values into the database
	$_SESSION["new_stu_id"] = "S-" . $year . $_SESSION["c_code"] . $nth;
			// echo $new_stu_id;
			// exit;
	// by default, the password is the same as the new student's id
	
	?>
	<div class="main-2">
		<form class="migrate" method="post">
			<h2>Migrate Students</h2>
			
			<br>
			<br>
			
			<!-- input fields and submit button starts -->
			<!-- actual name -->
			Actual name<br>
			<input type="text" name="name" required>
			<br>
			
			<!-- username -->
			Username<br>
			<input type="text" name="username" required>
			<br>
			
			<!-- submit button -->
			<input type="submit" value="Add student">
			<!-- input fields and submit button ends -->
			
			
			<!-- success message with all the detils -->
			<?php
			// Student named $name created with user id of $id and username of $username.
			// *students' new account's password is the same as thier id
			if(isset($add_stu_msg)){
				echo "<br>";
				echo "<br>";
				echo "<span class='add-msg'>";
				echo $add_stu_msg;
				echo "</span>";
			}
			?>
			
		</form>
	</div>
	
</body>

</html>