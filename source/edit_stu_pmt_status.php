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
		// only update the `sp_record` table
			// count either one i.e. id will do
			
			// define $status 
			$status = 0;
			
		for($update=0; $update<count($_POST["id"]); $update++){ 
		// $_POST["id"] is the students' id array
				// create the status number first
				$status = "status" . $update;
				
				// if $status value is 0, enter not paid, else enter paid
				if($_POST[$status] == "0"){
					$status = "Not paid";
				}else{
					$status = "Paid";
				}

			// sql statement - get pmt_id from the parameter
			$sql = "
					UPDATE `sp_record`
					SET
					`spr_status`='" . $status . "'
					WHERE `stu_id`='" . $_POST["id"][$update] . "'
					AND
					`pmt_id`='" . $_GET["pid"] . "';
					";
					
					// echo $sql;
					// exit;
					
			// $sql = "select * from `payments`";
			// execute sql and set success message
			// the folowing code will work
			$result = (mysqli_query($con,$sql) and $edit_msg = "Your changes have been saved") or die ("Oops! something went wrong. Please try again later.");
			
		}
		// close connection
		mysqli_close($con);
		
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
	<link href="css/edit_stu_pmt_status.css" type="text/css" rel="stylesheet"><!-- css - edit students' payment status -->
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
	
	// check whether $_GET["pid"] exists or not
	// if it doesn't exist, then list out a whole list of links with parameters
	// if it exists, then retrive all data from the database
	if(!isset($_GET["pid"])){
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement
		$sql = "
				SELECT `pmt_id`, `pmt_name`, `pmt_price`
				FROM `payments`
				WHERE
				`c_name`='" . $_SESSION["c_name"] . "'
				AND
				`pmt_deleted`='0'
				ORDER BY `pmt_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// fetch data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or die("It seems like you don't have any payments yet<br><a class='empty' href='home.php'>Go to home page</a>");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// exit;
		
		// close connection
		mysqli_close($con);
		
		// print out the list
	?>
		<div class="main-2">
			<h2>Edit Students' Payment Status</h2>
			<p>Choose a payment you would like to edit</p>
			
			<!-- ordered list -->
			<ol class="edit">
			<?php
				for($list=0; $list<count($data); $list++){
			?>
				<li class="edit-stu-pmt-status">
					<a href="edit_stu_pmt_status.php?pid=<?php echo $data[$list]["pmt_id"]; ?>" target="_blank">
					<?php echo $data[$list]["pmt_name"] . "&nbsp;-&nbsp; RM&nbsp;" . $data[$list]["pmt_price"] . "&nbsp;per unit"; ?>
					</a>
				</li>
			<?php	
				}
			?>
			</ol>
			
		</div>
	<?php
	}else{
		// echo $_GET["pid"];
		
		// retrieve the data from the database
		
		// connect to the database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement - join tables
		$sql = "
				SELECT 
				students.`stu_id`, 
				students.`actual_name`, 
				sp_record.`spr_status`, 
				payments.`pmt_id`, 
				payments.`pmt_name`, 
				payments.`pmt_price`
				FROM
				`students`
				LEFT JOIN `sp_record`
				ON
				students.`stu_id`=sp_record.`stu_id`
				LEFT JOIN `payments`
				ON
				sp_record.`pmt_id`=payments.`pmt_id`
				WHERE
				payments.`pmt_id` = '" . $_GET["pid"] . "'
				AND
				sp_record.`spr_deleted` = 0
				ORDER BY students.`actual_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or die("Oops! Something went wrong. Please try again later");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// exit;
			
			// if ($data[0]["spr_status"] == "Not paid"){
				// echo "not paid";
				// exit;
			// }else{
				// echo "paid";
				// exit;
			// }
			
			// echo $data[0]["spr_status"];
			
		
		// close connection
		mysqli_close($con);
			
		// print out the table for editing
	?>
		<div class="main-1">
		
			<h2>Edit Students' Payment Status</h2>
			
			<h3>
			<?php
			// for the 1st dimensionof the array, use the number 0 since it will always exists no matter what
			// other numbers will do as well as the "pmt_name" is all the same - based on $_GET["pid"]
			echo $data[0]["pmt_name"] . "&nbsp-&nbsp;RM&nbsp;" . $data[0]["pmt_price"] . "&nbsp;per unit";
			?>
			</h3>
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
						<th class="pmt-id">Id</th><!-- ids -->
						<th>Students' Id</th>
						<th>Actual Names</th>
						<th>Paid?</th>
					</tr>
				<?php
					for($row=0; $row<count($data); $row++){
				?>	
					
					<tr class="hover">
						
						<!-- class number -->
						<td class="number"><?php echo $row+1; ?></td>
						
						<!-- checkboxes for the ids -->
						<!-- this part is hidden -->
						<td class="pmt-id">
						<!-- this part uses students' id because payment id is already a parameter -->
						<input type="checkbox" name="id[]" value="<?php echo $data[$row]["stu_id"]; ?>" checked>
						</td>
						
						<!-- students'id -->
						<td class="stu-id">
						<?php echo $data[$row]["stu_id"]; ?>
						</td>
						
						<!-- students' actual names -->
						<td class="name">
							<?php echo $data[$row]["actual_name"]; ?>
						</td>
						
						<!-- status -->
						<td class="status">
						<!-- this part uses spr_status because we are editing the `sp_record` data -->
							<input type="radio" name="status<?php echo $row; ?>" value="1" <?php if($data[$row]["spr_status"] == "Paid"){echo "checked";} ?>>Yes
							<input type="radio" name="status<?php echo $row; ?>" value="0" <?php if($data[$row]["spr_status"] == "Not paid"){echo "checked";} ?>>No
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
	<?php
	}	
	?>
	
	
</body>

</html>