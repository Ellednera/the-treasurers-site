<?php
// session_start();

// this file will be included in home.php so no need for session start

// session start is only for testing
?>

<div class="main-1">
	
	<h2>Summary</h2>
	<?php
	
	// count total expences first
	// connect to database
	$con = mysqli_connect("localhost", "root", "", "tts")or die("Oops! Something went wrong. Please try again later");
	
	// sql statement
	$sql = "
			SELECT `exp_price`
			FROM `expences`
			WHERE
			`c_name`='" . $_SESSION["c_name"] . "'
			AND
			`exp_deleted`='0';
			";
			
		// echo $sql;
		// exit;
		
	// execute sql statement
	$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
	
	// fetch data - if no data obtained define expences error message
	$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $exp_err = true;
		// echo "<pre>";
		// var_dump($data);
		// echo "</pre>";
		// exit;
		
	// close connection
	mysqli_close($con);
	
	// calculate the sum if and only if $exp_err is false
	if(!isset($exp_err)){
		// define expences
		$exp_total = 0.00;
		for($item=0; $item<count($data); $item++){
			$exp_total = $exp_total + $data[$item]["exp_price"];
		}
		// echo $exp_total;
		// exit;
	}
	// else $exp_total = 0.00

		
	
	
	
	// total payments part
	// 1st part: select all existing payments and their corresponding prices first 
	// if and only if the payment money is still with the treasurer
	
	// connect to database
	$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
	
	// sql statement
	$sql = "
			SELECT `pmt_id`, `pmt_price` 
			FROM `payments`
			WHERE
			`c_name`='" . $_SESSION["c_name"] . "'
			AND
			`pmt_deleted`='0'
			AND
			`pmt_status`='1'
			ORDER BY `pmt_id` ASC;
			";
		// echo $sql;
		// exit;
		
	// execute sql statement
	$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
	
	// retrieve data
	$pmt = mysqli_fetch_all($result, MYSQLI_BOTH) or $pmt_err = true;
		// echo "<pre>";
		// var_dump($pmt);
		// echo "</pre>";
		// exit;
		
	// close connection
	mysqli_close($con);
	
	
	
	
	
	
	// 2nd part: count the number of students who paid for the specific payment if and only if 
	// the payment money is still with the treasurer
	
	// connect to database
	$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
	
	// define variables for calculation
	$pmt_total = 0.00;
	// set the sql statement and execute it using a for loop
	for($rows=0; $rows<count($pmt); $rows++){
		// sql statement
		$sql = "
				SELECT COUNT(`stu_id`) AS `frequency`
				FROM `sp_record`
				WHERE
				`pmt_id`='" . $pmt[$rows]["pmt_id"] . "'
				AND
				`spr_deleted`='0'
				AND
				`spr_status`='Paid'
				ORDER BY `pmt_id`;
				";
			// echo $sql;
		
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later.");
		
		// retrieve data - there's only 1 record
		$stu = mysqli_fetch_array($result, MYSQLI_ASSOC) or $pmt_err = true;
		
			// echo "<pre>";
			// var_dump($stu);
			// echo "</pre>";
		
		// calculate for every condition
		if(!isset($pmt_err)){
			// if both error messages doesn't exist, then only can the calculation be done completely
			$current_pmt_total = $pmt[$rows]["pmt_price"] * $stu["frequency"];
			$pmt_total = $pmt_total + $current_pmt_total;
		}
		// else $pmt_total = 0.00
	}
	// close connection
	mysqli_close($con);
	
	// echo $pmt_total;
	// exit;
	
	// do the final calculation
	if(!isset($pmt_err) && !isset($exp_err)){
		// final calculation
		$class_fund = $pmt_total - $exp_total;
		
		// check condition
		if($class_fund < 0){
			// set message
			$cf_msg = "<span style='color: red;'>Oh, dear! According to calculation, you are RM " . abs($class_fund) . " in debt.</span>";
			// change the price to a positive number - "debt" indicates a negative number
			
		}elseif($class_fund > 0){
			// set message
			$cf_msg = "<span style='color: blue;'>Nice! According to calculation, you should have RM " . $class_fund . " in your class fund.</span>";
		}else{ 
			// $class_fund == 0
			// set message
			$cf_msg = "<span style='color: black;'>According to calculation, you don't have any money in your class fund.</span>";
		}
	}elseif(isset($pmt_err) && !isset($exp_err)){
		// set message
		$cf_msg = "<span style='color: red;'>Oh, dear! According to calculation, you are RM " . $exp_total . " in debt.</span>";
		// change the price to a positive number - "debt" indicates a negative number
			
	}elseif(!isset($pmt_err) && isset($exp_err)){
		// set message
		$cf_msg = "<span style='color: blue;'>Nice! According to calculation, you should have RM " . $pmt_total . " in your class fund.</span>";
	}else{
		// $pmt_total and $exp_total do not exist, class fund = RM 0.00
		// set message
			$cf_msg = "<span style='color: black;'>According to calculation, you don't have any money in your class fund.</span>";
	}
	?>
	
	
	
	
	
	
	
	<h3 style="text-decoration: none;"><?php echo $cf_msg; ?></h3>
		
	<h3>1. Payments</h3>
	
	<?php
		// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement - join tables
		$sql = "
				SELECT * FROM `payments`
				WHERE `c_name`='" . $_SESSION["c_name"] . "'
				AND
				`pmt_deleted`='0'
				ORDER BY `pmt_name` ASC;
				";
			
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $payments_msg = "<div class='empty'>Looks like you don't have any payments yet.</div>";
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			
		// close connection
		mysqli_close($con);
		
		// print out the data in a table
		if(isset($payments_msg)){
			echo $payments_msg;
		}else{
		?>
		<table>
			<tr>
				<th>No.</th>
				<th>Payment</th>
				<th>Price (RM)</th>
				<th>Money still with you?</th>
			</tr>
		<?php
			for($row=0; $row<count($data); $row++){
		?>
			<tr class="hover">
				<td class="number"><?php echo $row+1; ?></td>
				<td class="pmt-name"><?php echo $data[$row]["pmt_name"]; ?></td>
				<td class="pmt-price"><?php echo $data[$row]["pmt_price"] ?></td>
				<td class="pmt-status"><?php if($data[$row]["pmt_status"] == 1){echo "Yes";}else{echo "No";} ?></td>
			</tr>	
			
		<?php
			}
		?>
		</table>
		<?php
		}
	?>
	
	<h3>2. Expences</h3>
	<?php
	// connect to database
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later");
		
		// sql statement - join tables
		$sql = "
				SELECT `exp_name`, `exp_price`
				FROM `expences`
				WHERE
				`c_name`='" .  $_SESSION["c_name"] . "'
				AND
				`exp_deleted`='0'
				ORDER BY `exp_name` ASC;
				";
			
			// echo $sql;
			// exit;
			
		// execute sql statement
		$result = mysqli_query($con, $sql) or die("Oops! Something went wrong. Please try again later");
		
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $expences_msg = "<div class='empty'>Looks like you don't have any expences yet.</div>";
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			
		// if message exists then print it out
		if(isset($expences_msg)){
			echo $expences_msg;
		}else{
			?>
		<table>
			
			<tr>
				<th>No.</th>
				<th>Expences</th>
				<th>Price (RM)</th>
			<tr>
			<?php
			// define $price_total first
			$exp_total = 0.00;
			for($row=0; $row<count($data); $row++){
			// calculate total price first
			$exp_total = $exp_total + $data[$row]["exp_price"];
		?>
			<tr class="hover">
				<td class="number"><?php echo $row+1; ?></td>
				<td class="exp-name"><?php echo $data[$row]["exp_name"]; ?></td>
				<td class="exp-price"><?php echo $data[$row]["exp_price"] ?></td>
			</tr>	
			
		<?php
			}
		?>
			<tr>
				<td class="exp-total-text" colspan="2">Total</td>
				<td class="exp-total"><?php echo $exp_total; ?></td>
			</tr>
		</table>
		<?php
		}
?>		

	<h3>3. Name List</h3>
	
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
	<!-- print all the result into a table -->
	<!-- this part can have its th printed first because there'll always be at least 1 student -- the treasurer -->
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
				<td class="values-center-class-num"><?php echo $row+1; ?></td><!-- class number -->
				<td class="values-center-id"><?php echo $data[$row]["stu_id"]; ?></td><!-- id -->
				<td class="values-pad"><?php echo $data[$row]["actual_name"]; ?></td><!-- actual name -->
			</tr>				
		<?php		
			}
		?>
		</table>
		<!-- the overall list -->
		<?php
		if(!isset($pmt_err)){
			?>
			<div class="overall">
				<a href='detailed_list.php' target='_blank'>
				Click here for the more detailed list
				</a>
			</div>
			<?php
		}
		?>
		

</div>