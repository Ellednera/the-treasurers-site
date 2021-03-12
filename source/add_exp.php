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
	<link href="css/add_exp.css" type="text/css" rel="stylesheet"><!-- css - add payments -->
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
				SELECT * FROM `expences`
				WHERE
				`exp_name`='" . $_POST["exp_name"] . "'
				AND
				`c_name`='" . $_SESSION["c_name"] . "'
				AND
				`exp_deleted`='0';
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
			$add_exp_err_msg = "This expences name exists, please use another name";
			// for preserving the form if there are 2 same payment names
			$exp_name = $_POST["exp_name"];
			$price = $_POST["price"];
		}else{
			// save the payment
			// sql statement
			$sql = "
					INSERT INTO `expences`
					SET
					`exp_id`='',
					`exp_name`='" . $_POST["exp_name"] . "',
					`exp_price`='" . $_POST["price"] . "',
					`c_name`='" . $_SESSION["c_name"] . "',
					`exp_deleted`='0';
					";
				// echo $sql;
				// exit;
				
			// execute sql statement
			$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
			
			// close connection
			mysqli_close($con);
			
			// set success message
			$add_exp_msg = "Expences &#34" . $_POST["exp_name"] . "&#34 with price of RM " . $_POST["price"] . " succesfully added";
		}
	}
	?>
	<div class="main-3">
		<form class="add-1" method="post">
			<h2>Add Expences</h2>
			
			<!-- input fields and submit button starts -->
			<!-- actual name -->
			Expences name<br>
			<input type="text" name="exp_name" value="<?php if(isset($exp_name)){echo $exp_name;} ?>" required>
			<br>
			
			<!-- username -->
			Price (RM)<br>
			<input class="price" type="text" name="price" value="<?php if(isset($price)){echo $price;} ?>" placeholder="e.g. 12.90" required>
			
			<br>
			
			<!-- submit button -->
			<input type="submit" value="Add Payment">
			<!-- input fields and submit button ends -->
			
			
			<!-- success message with all the detils -->
			<?php
			// Student named $name created with user id of $id and username of $username.
			// *students' new account's password is the same as thier id
			if(isset($add_exp_msg)){
				echo "<br>";
				echo "<br>";
				echo "<span class='add-msg'>";
				echo $add_exp_msg;
				echo "</span>";
			}elseif(isset($add_exp_err_msg)){
				echo "<br>";
				echo "<br>";
				echo "<span class='add-err-msg'>";
				echo $add_exp_err_msg;
				echo "</span>";
			}
			?>
			
		</form>
	</div>
	
</body>

</html>