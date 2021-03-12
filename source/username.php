<?php
session_start();

// redirections
if(!isset($_SESSION["stu_id"]) && !isset($_SESSION["actual_name"])){
	// echo "true";
	// exit;
	header("Location: index.php");
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
	<title>The Treasurer's Site</title>
	<link href="css/home.css" type="text/css" rel="stylesheet"><!-- css - home -->
	<link href="css/banner.css" type="text/css" rel="stylesheet"><!-- css - banner -->
	<link href="css/menu.css" type="text/css" rel="stylesheet"><!-- css - menu -->
	<link href="css/features.css" type="text/css" rel="stylesheet"><!-- css - profile -->
	<link href="css/username.css" type="text/css" rel="stylesheet"><!-- css - profile -->
	<meta charset="UTF-8">
</head>

<body>
	
	<?php
// banner method == "get" goes in here
// the "new" menu only appears when submitting the form twice

if($_SERVER["REQUEST_METHOD"] == "GET"){
	// save the value of v into a session
	if(isset($_GET["view"])){
		$_SESSION["view"] = $_GET["view"];
	}
}


// currently, usable values for $role are 1 and 2 only

if($_SESSION["role"] == 1){
	// students
	include("template/banner.php");
	include("template/menu.php");
}else {
	// treasurer

	if(isset($_SESSION["view"])){
	// $SESSION["view"] == 2 => treasurers' view
	// $SESSION["view"] == 1 => students' view
		if($_SESSION["view"] == 2){
			// treasurers' view
			include("template/banner-a.php");
			include("template/menu-a.php");	
			// echo $_SESSION["view"]."=>treasurers' view";
		}elseif($_SESSION["view"] == 1){
			// students' view
			include("template/banner-a.php"); // title remains the same
			include("template/menu.php");
			// echo $_SESSION["view"]."=>students' view";
		}
	}else{
			// default treasurers' view
			include("template/banner-a.php");
			include("template/menu-a.php");
		}

}

// for students, include summary_s.php
// for treasueres, include summary_t.php


?>
	
	<?php
	
	//for changing username, the banner must be placed before this part of code
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts");
		// var_dump($con);

		// set the new username 
		$_SESSION["username"] = $_POST["username"];
		
		// echo $_SESSION["username"];
		// redirect to home page to 'refresh' session
		// exit;
		
		// sql statement
		$sql = "
				UPDATE `students` 
				SET `username`='" . $_POST["username"] . "'
				WHERE `stu_id`='" . $_SESSION["id"] . "'
				;";
				
		// echo $sql;
		// exit;
				
		// execute query
		$result = mysqli_query($con, $sql);
		
		// close connection
		mysqli_close($con);
		
		// if query is successfully executed
		if($result){
			// set success message
			$change_usrname_s = "You username has been changed to  '" . $_SESSION["username"] . "' successfully";
		}

	}
	?>
	<div class="main-3">
		<h2>Change Username</h2>
		
		<form method="post">
			<p>Username</p>
			<input type="text" name="username" value="<?php echo $_SESSION["username"]; ?>" required><br>
			<input type="submit" value="Change username">
			<?php
			if(isset($change_usrname_s)){
				echo "<br>";
				echo "<span class='change-username'>";
				echo $change_usrname_s;
				echo "</span>";
			}
			?>
		</form>
		
	</div>
	
	
</body>

</html>