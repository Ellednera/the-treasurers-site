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
	<link href="css/add_pmt.css" type="text/css" rel="stylesheet"><!-- css - add payments -->
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
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			// echo "<pre>";
			// var_dump($_POST);
			// echo "</pre>";
			// exit;
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// check for the same payment name - don't allow same payment names
		$sql = "
				SELECT * FROM `payments`
				WHERE
				`pmt_name`='" . $_POST["pmt_name"] . "'
				AND
				`c_name`='" . $_SESSION["c_name"] . "'
				AND
				`pmt_deleted`='0';
				"; 
				 
				// echo $sql;
				// exit;
				
		// execute sql statement
		$result = mysqli_query($con, $sql);
		// fetch data
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// exit;

		
		// if there is any record, don't proceed
		if(count($data)>0){
			$add_pmt_err_msg = "This payment name exists, please use another name";
			// for preserving the form if there are 2 same payment names
			$pmt_name = $_POST["pmt_name"];
			$price = $_POST["price"];
			$status = $_POST["status"];
		}else{
			// save the payment
			// sql statement
			$sql = "
					INSERT INTO `payments`
					SET
					`pmt_id`='',
					`pmt_name`='" . $_POST["pmt_name"] . "',
					`pmt_price`='" . $_POST["price"] . "',
					`pmt_status`='" . $_POST["status"] . "',
					`c_name`='" . $_SESSION["c_name"] . "',
					`pmt_deleted`='0';
					";
				// echo $sql;
				// exit;
				
			// execute sql statement
			$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			
			// this piece of code will be place after the payment is added
			// to get the payment id for creating the payment record
			$pmt_id = mysqli_insert_id($con);
			
			// after creating every payment, the payment record table must be updated
			// 1. Get all the students information
			// 2. Using the payment information, update the payment record table
			
			// variables needed: student's id and the payment id
			$sql = "
					SELECT `stu_id` FROM `students`
					WHERE
					`c_name`='" . $_SESSION["c_name"] . "'
					AND
					`stu_deleted`='0';
					";
			
			// execute sql statement
			$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			
			// fetch data
			$data = mysqli_fetch_all($result, MYSQLI_ASSOC) or die("Oops! Something went wrong. Please try again later.");
			
			// create the payment record
			for($record=0; $record<count($data); $record++){
				// sql statement
				$sql = "
						INSERT INTO `sp_record`
						SET
						`spr_id`='',
						`stu_id`='" . $data[$record]["stu_id"] . "',
						`pmt_id`='". $pmt_id . "',
						`spr_status`='Not paid',
						`spr_deleted`='0';
						";

				// execute sql statement
				$result = mysqli_query($con, $sql) or die("Oops! something went wrong. Please try again later.");
			}
			
			
			// close connection
			mysqli_close($con);
			
			// set success message
			$add_pmt_msg = "Payment &#34" . $_POST["pmt_name"] . "&#34 with price of RM " . $_POST["price"] . " per unit succesfully added";
		}
	}
	?>
	<div class="main-3">
		<form class="add-1" method="post">
			<h2>Add Payments</h2>
			
			<!-- input fields and submit button starts -->
			<!-- actual name -->
			Payment name<br>
			<input type="text" name="pmt_name" value="<?php if(isset($pmt_name)){echo $pmt_name;} ?>" required>
			<br>
			
			<!-- username -->
			Price per unit (RM)<br>
			<input class="price" type="text" name="price" value="<?php if(isset($price)){echo $price;} ?>" placeholder="e.g. 12.90" required>
			<br>
			<br>
			
			<span class="status">
			Is the (collected) money (still) with you?
			<br>
			<!-- any field with the 'required' attribute will do -->
			<input type="radio" name="status" value="1" <?php if(isset($status)){if($status == 1){echo "checked";}} ?> required>Yes
			<input type="radio" name="status" value="0" <?php if(isset($status)){if($status == 0){echo "checked";}} ?> required>No
			</span>
			
			<br>
			
			<!-- submit button -->
			<input type="submit" value="Add Payment">
			<!-- input fields and submit button ends -->
			
			
			<!-- success message with all the detils -->
			<?php
			// Student named $name created with user id of $id and username of $username.
			// *students' new account's password is the same as thier id
			if(isset($add_pmt_msg)){
				echo "<br>";
				echo "<br>";
				echo "<span class='add-msg'>";
				echo $add_pmt_msg;
				echo "</span>";
			}elseif(isset($add_pmt_err_msg)){
				echo "<br>";
				echo "<br>";
				echo "<span class='add-err-msg'>";
				echo $add_pmt_err_msg;
				echo "</span>";
			}
			?>
			
		</form>
	</div>
	
</body>

</html>