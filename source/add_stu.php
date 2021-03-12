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
	<link href="css/features.css" type="text/css" rel="stylesheet"><!-- css - features -->
	<link href="css/add_stu.css" type="text/css" rel="stylesheet"><!-- css - add students -->
	<!--<link href="" type="" rel="icon"> icon -->
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
}else{
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
		$result = mysqli_query($con, $sql) or die("<br>12345678Oops! Something went wrong. Please try again later.");
		
		// close connection
		mysqli_close($con);
		
		// set success message - if the query is not correct, nothing below it will be executed
		$add_stu_msg = "Account for &#34;" . $_POST["name"] . "&#34; with id &#34;" . $_SESSION["new_stu_id"] . "&#34; successfully created!";
		
		
		// after adding a new student, the sp_record table must have new records for the new student
		// 1. Select all the payments (if any)
		// 2. Using the infomation obtain, create new records in the sp_record table
		
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// sql statement
		$sql = "
				SELECT `pmt_id` FROM
				`payments`
				WHERE
				`c_name`='" . $_SESSION["c_name"] . "'
				AND
				`pmt_deleted`='0';
				";
		
		// execute sql statement
		$result = mysqli_query($con, $sql);
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH);
		
		// only proceed if the data can be retrieved(not null)
		if($data){
			// update the sp_record table
			for($rows=0; $rows<count($data); $rows++){
				// sql statement
				$sql = "
						INSERT INTO `sp_record`
						SET
						`spr_id`='',
						`stu_id`='" . $_SESSION["new_stu_id"] . "',
						`pmt_id`='" . $data[$rows]["pmt_id"] . "',
						`spr_status`='Not paid',
						`spr_deleted`='0';
						";
						
				// execute sql statement
				$result = mysqli_query($con, $sql);
			}
		}
		
		// close connection
		mysqli_close($con);
		
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
	<div class="main-3">
		<form class="add-1" method="post">
			<h2>Add Students</h2>
			<!-- connect to database to retrive data -->
			<span class="fixed-details">
			Student's id: <?php if(isset($_SESSION["new_stu_id"])){echo $_SESSION["new_stu_id"];} ?>
			<br>
			Default password: <?php if(isset($_SESSION["new_stu_id"])){echo $_SESSION["new_stu_id"];} ?>
			</span>
			
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