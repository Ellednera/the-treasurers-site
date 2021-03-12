<?php
session_start();

// redirections
if(!isset($_SESSION["stu_id"]) && !isset($_SESSION["actual_name"])){
	// echo "true";
	// exit;
	header("Location: index.php");
}

// soft delete
if($_SERVER["REQUEST_METHOD"] == "POST"){
		// echo "<pre>";
		// var_dump($_POST["delete"]);
		// echo "</pre>";
		// exit;
	if(!isset($_POST["delete"])){
	// if no box is checked, the delete variable will not exist, redirect to the same page
		header("Location:delete_stu.php");
	}else{
	// carry out the soft delete - use loop
		$total = 0; // for the total number of students deleted
		// connect to the database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// set spr_deleted to 1
		// the calculation in the summary part is based on spr_deleted=0
		// if the deleted student's spr_deleted isn't set to 1, his/her part will be calculated too
		for($affect=0; $affect<count($_POST["delete"]); $affect++){
			// sql statement
			$sql = "
					UPDATE `sp_record`
					SET
					`spr_deleted`='1'
					WHERE
					`stu_id`='" . $_POST["delete"][$affect] . "';
					";
					// echo $sql;
					// echo "<br>";
					
			// execute sql statement
			$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
		}
		
		// sql statement and execution of the statement
		// $_POST["deleted"] is for the number of checkboxes
		for($affect=0; $affect<count($_POST["delete"]); $affect++){
			// sql statement
			$sql = "
					UPDATE `students`
					SET `stu_deleted`='1'
					WHERE `stu_id`='" . $_POST["delete"][$affect] . "';
					";
					// echo $sql;
					// echo "<br>";
					
			// execute sql statement
			$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			
			// set the total number of affected rows
			$total = $total + mysqli_affected_rows($con);
		}


		// set message
		$del_suc_msg = $total . " student(s) succecfully deleted";
					// exit;
		// close connection
		mysqli_close($con);
	}
	

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
	<link href="css/delete_stu.css" type="text/css" rel="stylesheet"><!-- css - delete students -->
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
		$result = mysqli_query($con, $sql) 
				or die("Oops! Something went wrong. Please try again later.");
			// echo "success";
			// exit;
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH)or die("Oops! Something went wrong. Please try again later");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;

		// close connection
		mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>Delete Students</h2>
		
		<!-- print all the result in a form containing a table -->
		<form method="post">
			<table>
				
				<tr>
					<th>Delete</th>
					<th>Class No.</th>
					<th>Student's Id</th>
					<th>Actual Name</th>
				</tr>
				
			<?php
				// print out the data
				for($row=0; $row<count($data); $row++){
					// the treasurer cannot delete himself/herself
					$preserve = substr($data[$row]["stu_id"], 0, 1);
			?>		
				<tr class="hover">
					<td class="del-col">
						<!-- if the id starts with a "T", don't show the checkbox -->
						<?php 
						if($preserve != "T"){
							echo "<input type='checkbox' name='delete[]' value='" . $data[$row]["stu_id"] . "'>";
						} else{
							echo "";
						}
						?>
					</td>
					<td class="class-no"><?php echo $row+1; ?></td><!-- class number -->
					<td class="id-col"><?php echo $data[$row]["stu_id"]; ?></td><!-- student's id -->
					<td class="actual-name"><?php echo $data[$row]["actual_name"]; ?></td><!-- actual name -->
				</tr>
			<?php
				}
			?>
				
				<!-- delete button -->
				<tr>
					<td class="delete-btn" colspan="4">
						<input type="submit" value="Delete">
					</td>
				</tr>
				
				<!-- notes -->
				<tr class="del-notes">
					<td colspan="4">
						Please note that this action is permanent
					</td>
				</tr>
				
			<?php
				if(isset($del_suc_msg)){
			?>
				<tr class="del-suc">
					<td colspan="4">
					<?php
						echo $del_suc_msg;
					?>
					</td>
				</tr>	
			<?php	
				}
			?>
				
			</table>
		</form>
		
		
	</div>
	
</body>

</html>