<?php
session_start();

// redirections
if(!isset($_SESSION["stu_id"]) && !isset($_SESSION["actual_name"])){
	// echo "true";
	// exit;
	header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		/*
		-When processing the data, the name of the status is in the format of: $_POST["status" . $row]
		-The $row will be used when updating the tables
		*/
		
		/* logic test starts */
			// for($out=0; $out<count($_POST["id"]); $out++){
				// create the status number first
				// $status = "status" . $out;
					// echo $status;
					// echo $_POST["$status"];
					// * $status here by itself is constantly changing, so htere is no need for the array number anymore
				// echo $out+1 . "->" . $_POST["id"][$out] . "->" . $_POST["name"][$out] . "->" . $_POST["price"][$out] . "->Status=" . $_POST[$status];
				// echo "<br>";
				// echo "<br>";
			// }
				// exit;
		/* logic test ends */
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// sql statement, execute sql statement - update the tables using a loop
			// count either one i.e. id will do
		for($update=0; $update<count($_POST["id"]); $update++){ 
		
				// create the status number first
				$status = "status" . $update;
			
			// sql statement
			$sql = "
					UPDATE `payments`
					SET
					`pmt_name`='" . $_POST["name"][$update] . "',
					`pmt_price`='" . $_POST["price"][$update] . "',
					`pmt_status`='" . $_POST[$status] . "'
					WHERE `pmt_id`='" . $_POST["id"][$update] . "';
					";
					
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
	<link href="css/edit_pmt_info.css" type="text/css" rel="stylesheet"><!-- css - edit payments -->
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
				SELECT `pmt_id`, `pmt_name`, `pmt_price`, `pmt_status` 
				FROM `payments`
				WHERE `c_name`='" . $_SESSION["c_name"] . "'
				AND
				`pmt_deleted`='0'
				ORDER BY `pmt_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			// echo "success";
			// exit;
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH)or die("It seems like you have any payments yet<br><a class='empty' href='home.php'>Go to home page</a>");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
			
			// close connection
			mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>Edit Payment Information</h2>
		
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
					<th>Payment Names</th>
					<th>Price (RM)</th>
					<th>Money still with you?</th>
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
					<input type="checkbox" name="id[]" value="<?php echo $data[$row]["pmt_id"]; ?>" checked>
					</td>
					
					<!-- payment names -->
					<td class="name">
						<input type="text" name="name[]" value="<?php echo $data[$row]["pmt_name"]; ?>" required>
					</td>
					
					<!-- price -->
					<td class="price">
						<input type="text" name="price[]" value="<?php echo $data[$row]["pmt_price"]; ?>" required>
					</td>
					
					<!-- money status -->
					<td class="status">
						<input type="radio" name="status<?php echo $row; ?>" value="1" <?php if($data[$row]["pmt_status"] == 1){echo "checked";} ?>>Yes
						<input type="radio" name="status<?php echo $row; ?>" value="0" <?php if($data[$row]["pmt_status"] == 0){echo "checked";} ?>>No
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