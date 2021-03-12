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
	<link href="css/profile.css" type="text/css" rel="stylesheet"><!-- css - profile -->
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
	<div class="main-2">
		<h2>Profile</h2>
		<table>
		
			<!-- table heading -->
			<tr>
				<th>Contents</th>
				<th>Details</th>
			</tr>
		
			<!-- id -->
			<tr>
				<td>Id</td>
				<td><?php echo $_SESSION["id"]; ?></td>
			</tr>
			
			<!-- actual name -->
			<tr>
				<td>Actual name</td>
				<td><?php echo $_SESSION["actual_name"]; ?></td>
			</tr>
			
			<!-- username -->
			<tr>
				<td>Username</td>
				<td><?php echo $_SESSION["username"]; ?></td>
			</tr>
			
			<!-- class -->
			<tr>
				<td>Class</td>
				<td><?php echo $_SESSION["c_name"]; ?></td>
			</tr>
			
		</table>
		
	</div>
	
	
</body>

</html>