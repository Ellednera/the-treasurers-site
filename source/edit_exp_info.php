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
			
			// sql statement
			$sql = "
					UPDATE `expences`
					SET
					`exp_name`='" . $_POST["name"][$update] . "',
					`exp_price`='" . $_POST["price"][$update] . "'
					WHERE `exp_id`='" . $_POST["id"][$update] . "';
					";
					
				// echo $sql;
				
					
			// execute sql
			$result = mysqli_query($con,$sql)or die ("Oops! something went wrong. Please try again later.");
			
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
	<link href="css/edit_exp_info.css" type="text/css" rel="stylesheet"><!-- css - edit expences -->
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
				SELECT `exp_id`, `exp_name`, `exp_price`
				FROM `expences`
				WHERE `c_name`='" . $_SESSION["c_name"] . "'
				AND
				`exp_deleted`='0'
				ORDER BY `exp_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			// echo "success";
			// exit;
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH)or die("It seems like you have any expences yet.<br><a class='empty' href='home.php'>Go to home page</a>");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
			
			// close connection
			mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>Edit Expences Information</h2>
		
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
			
				<!-- notes -->
				<tr>
					<td class="edit-notes" colspan="5">
						*Please do not use any payment name more than once
						<br>
						*Please fill in the actual amount of money in the price column e.g. 12.90
					</td>
				</tr>
			
				<tr>
					<th>No.</th>
					<th class="pmt-id">Id</th><!-- ids -->
					<th>Expences Names</th>
					<th>Price (RM)</th>
				</tr>
			<?php
				for($row=0; $row<count($data); $row++){
			?>	
				
				<tr class="hover">
					
					<!-- numbering -->
					<td class="number"><?php echo $row+1; ?></td>
					
					<!-- checkboxes for the ids -->
					<!-- this part is hidden -->
					<td class="pmt-id">
					<input type="checkbox" name="id[]" value="<?php echo $data[$row]["exp_id"]; ?>" checked>
					</td>
					
					<!-- payment names -->
					<td class="name">
						<input type="text" name="name[]" value="<?php echo $data[$row]["exp_name"]; ?>" required>
					</td>
					
					<!-- price -->
					<td class="price">
						<input type="text" name="price[]" value="<?php echo $data[$row]["exp_price"]; ?>" required>
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