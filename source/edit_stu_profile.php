<?php
session_start();

// redirections
if(!isset($_SESSION["stu_id"]) && !isset($_SESSION["actual_name"])){
	// echo "true";
	// exit;
	header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// sql statement, execute sql statement - update the tables using a loop
			// count either one i.e. id will do
		for($update=0; $update<count($_POST["id"]); $update++){ 
		
				// create the status number first
				$status = "status" . $update;
			
			// sql statement
			$sql = "
					UPDATE `students`
					SET
					`actual_name`='" . $_POST["name"][$update] . "'
					WHERE `stu_id`='" . $_POST["id"][$update] . "';
					";
					
			// execute sql
			$result = mysqli_query($con,$sql)or die ("Oops! Something went wrong. Please try again later.");
			
		}
		// close connection
		mysqli_close($con);
		
		// set success message
		if($result){
			$edit_msg = "Your changes have been saved";
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
	<link href="css/edit_stu_profile.css" type="text/css" rel="stylesheet"><!-- css - edit students' profile -->
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
		$data = mysqli_fetch_all($result, MYSQLI_BOTH)or die("Oops! Something went wrong. Please try again later.");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
			
			// close connection
			mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>Edit Students' Profile</h2>
		
		<form method="post">
		
			<!-- print all the result in the form of table -->
			<table>
			
			<?php
				// after changes haave been saved successfully, display message
				if(isset($edit_msg)){
			?>
				<tr>
					<td colspan="5" class="edit-msg">
						<?php
							echo $edit_msg;
						?>
					</td>
				</tr>				
			<?php
				}
			?>
			
				<tr>
					<th>Class No.</th>
					<th class="stu-id-checkbox">Id</th><!-- ids for checkboxes - hidden -->
					<th>Students' Id</th><!-- id - display -->
					<th>Actual Name</th>
				</tr>
			<?php
				for($row=0; $row<count($data); $row++){
			?>	
				
				<tr class="hover">
					
					<!-- numbering -->
					<td class="number"><?php echo $row+1; ?></td>
					
					<!-- checkboxes for the ids -->
					<!-- this part is hidden -->
					<td class="stu-id-checkbox">
					<input type="checkbox" name="id[]" value="<?php echo $data[$row]["stu_id"]; ?>" checked>
					</td>
					
					<td class="actual-id">
					<?php echo $data[$row]["stu_id"]; ?>
					</td>
					
					<!-- payment names -->
					<td class="name">
						<input type="text" name="name[]" value="<?php echo $data[$row]["actual_name"]; ?>" required>
					</td>
					
				</tr>				
			<?php		
				}
			?>
			
				<tr class="save-btn">
					<!-- "save" button -->
					<td class="save-btn" colspan="5">
						<input type="submit" value="Save">
					</td>
				</tr>
			
			</table>
		
		</form>
		
	</div>
	
</body>

</html>