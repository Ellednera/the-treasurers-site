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
	<link href="css/name_list.css" type="text/css" rel="stylesheet"><!-- css - name list -->
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
		// connect to database to retrieve id and actual name
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// sql statement
		$sql = "
				SELECT `stu_id`, `actual_name` 
				FROM `students`
				WHERE `c_name`='" . $_SESSION["c_name"] . "'
				AND
				`stu_deleted`='0'
				ORDER BY `actual_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			// echo "success";
			// exit;
		
		// retrieve data
		// there's no need to check whether if there is any students or not 
		// because the name list will have at least 1 student--the treasurer
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or die("Oops! Something went wrong. Please try again later.");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
		mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>
		<?php
			echo "Name List (". $_SESSION["c_name"] . ")";
		?>
		</h2>
		
		<!-- print all the result in the form of table -->
		<table>
			<tr>
				<th>Class No.</th>
				<th>Students' Id</th>
				<th>Actual Name</th>
			</tr>
		<?php
			for($row=0; $row<count($data); $row++){
		?>	
			<tr class="hover">
				<td class="values-center"><?php echo $row+1; ?></td><!-- class number -->
				<td class="values-center"><?php echo $data[$row]["stu_id"]; ?></td><!-- id -->
				<td class="values-pad"><?php echo $data[$row]["actual_name"]; ?></td><!-- actual name -->
			</tr>				
		<?php		
			}
		?>
		</table>
		
	</div>
	
</body>

</html>