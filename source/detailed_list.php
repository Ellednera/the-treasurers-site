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
	<link href="css/detailed_list.css" type="text/css" rel="stylesheet"><!-- css - detailed list -->
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
	
	// check whether $_GET["sid"] exists or not
	// if it doesn't exist, then list out a whole list of links with parameters
	// if it exists, then retrive all data from the database
	if(!isset($_GET["sid"])){
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement
		$sql = "
				SELECT `stu_id`, `actual_name`
				FROM `students`
				WHERE
				`c_name`='" . $_SESSION["c_name"] . "'
				AND
				`stu_deleted`='0'
				ORDER BY `actual_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// fetch data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or die("Oops! Something went wrong. Please try again later");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// exit;
		
		// close connection
		mysqli_close($con);
		
		// print out the list
	?>
		<div class="main-2">
			<h2 class="title">Detailed List</h2>
			<p>Click on a student's name to view his/her payments details</p>
			
			<!-- ordered list -->
			<ol class="details">
			<?php
				for($list=0; $list<count($data); $list++){
			?>
				<li class="details-list">
					<a href="detailed_list.php?sid=<?php echo $data[$list]["stu_id"]; ?>" target="_blank">
					<?php echo $data[$list]["actual_name"]; ?>
					</a>
				</li>
			<?php	
				}
			?>
			</ol>
			
		</div>
	<?php
	}else{
		// if the parameter exists
		// echo $_GET["sid"];
		
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement
		$sql = "
				SELECT `actual_name`
				FROM `students`
				WHERE
				`stu_id`='" . $_GET["sid"] . "'
				AND
				`stu_deleted`='0'
				ORDER BY `actual_name` ASC;
				";
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// fetch data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or die("Oops! Something went wrong. Please try again later");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// exit;
		
		// close connection
		mysqli_close($con);
	?>
		<div class="main-1">
		
			<h2>Detailed List</h2>
			
			<?php
			// get the student's actual name
			
			?>
			
			<h3 class="h-name">
			<?php
			echo $data[0]["actual_name"];
			// echo $data[0]["stu_id"];
			?>
			</h3>
				
			<h3 class="pmt-type">1. Unsettled Payments</h3>
			
			<?php
				// connect to database
				$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
				
				// sql statement - join tables
				$sql = "
						SELECT payments.`pmt_name`, payments.`pmt_price` 
						FROM `payments` 
						LEFT JOIN `sp_record` 
						ON payments.`pmt_id`=sp_record.`pmt_id` 
						WHERE 
						sp_record.`stu_id`='" . $_GET["sid"] . "' 
						AND 
						sp_record.`spr_deleted`='0' 
						AND 
						sp_record.`spr_status`='Not paid' 
						AND 
						sp_record.`spr_deleted`='0' 
						ORDER BY payments.`pmt_name` ASC;
						";
					
					// echo $sql;
					// exit;
					
				// execute sql statement
				$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
				
				// retrieve data
				$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $us_payments_msg = "<div class='empty'>Nice! No unsettled payments found.</div>";
					// echo "<pre>";
					// var_dump($data);
					// echo "</pre>";
					
				// close connection
				mysqli_close($con);
				
				// print out the data in a table
				if(isset($us_payments_msg)){
					echo $us_payments_msg;
				}else{
				?>
				<table>
					<tr>
						<th>No.</th>
						<th>Payment</th>
						<th>Price (RM)</th>
					</tr>
				<?php
					// define debt
					$debt = 0.00;
					for($row=0; $row<count($data); $row++){
						// calculate the debt first
						$debt = $debt + $data[$row]["pmt_price"];
				?>
					<tr class="hover">
						<td class="number"><?php echo $row+1; ?></td>
						<td class="payment"><?php echo $data[$row]["pmt_name"]; ?></td>
						<td class="price"><?php echo $data[$row]["pmt_price"] ?></td>
					</tr>
					
				<?php
					}
				?>
					<tr class="debt-msg">
						<td class="debt-msg" colspan="3">This student owes you RM <?php echo $debt;?></td>
					</tr>
					
				</table>
				<?php
				}
			?>
			
			<h3 class="pmt-type">2. Settled Payments</h3>
			<?php
			// connect to database
				$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
				
				// sql statement - join tables
				$sql = "
						SELECT payments.`pmt_name`, payments.`pmt_price` 
						FROM `payments` 
						LEFT JOIN `sp_record` 
						ON payments.`pmt_id`=sp_record.`pmt_id` 
						WHERE 
						sp_record.`stu_id`='" . $_GET["sid"] . "' 
						AND 
						sp_record.`spr_deleted`='0' 
						AND 
						sp_record.`spr_status`='Paid' 
						AND 
						sp_record.`spr_deleted`='0' 
						ORDER BY payments.`pmt_name` ASC;
						";
					
					// echo $sql;
					// exit;
					
				// execute sql statement
				$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
				
				// retrieve data
				$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $s_payments_msg = "<div class='empty'>No settled payments found.</div>";
					// echo "<pre>";
					// var_dump($data);
					// echo "</pre>";
				
				// close connection
				mysqli_close($con);
				
					
				// if message exists then print it out
				if(isset($s_payments_msg)){
					echo $s_payments_msg;
				}else{
					?>
				<table>
					
					<tr>
						<th>No.</th>
						<th>Payments</th>
						<th>Price (RM)</th>
					</tr>
					<?php
					// print the data
					for($row=0; $row<count($data); $row++){
				?>
					<tr class="hover">
						<td class="number"><?php echo $row+1; ?></td>
						<td class="payment"><?php echo $data[$row]["pmt_name"]; ?></td>
						<td class="price"><?php echo $data[$row]["pmt_price"] ?></td>
					</tr>	
					
				<?php
					}
				?>
				</table>
				<?php
				}
		?>		
				
				
				</div>
			<?php
			}	
			?>
	
	
</body>

</html>