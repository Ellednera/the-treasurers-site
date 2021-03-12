<?php
// session_start();

// this file will be included in home.php so no need for session start

// session start is only for testing
?>

<div class="main-1">
	
	<h2>Payments<h2>
		
	<h3>1. Unsettled Payments</h3>
	
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
				sp_record.`stu_id`='" . $_SESSION["stu_id"] . "' 
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
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $payments_msg = "<div class='empty'>Nice! No unsettled payments yet.</div>";
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
				<td class="debt-msg" colspan="3">You still owe your treasurer RM <?php echo $debt;?></td>
			</tr>
			
		</table>
		<?php
		}
	?>
	
	<h3>2. Settled Payments</h3>
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
				sp_record.`stu_id`='" . $_SESSION["stu_id"] . "' 
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
		
		
			// don't do anything to $expences_msg, it's a unique variable name to avoid strange things from happening
		// retrieve data
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $expences_msg = "<div class='empty'>Looks like you don't have any settled payments yet.</div>";
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
		
		// close connection
		mysqli_close($con);
		
			
		// if message exists then print it out
		if(isset($expences_msg)){
			echo $expences_msg;
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

	<h3>3. Payment List</h3>
	
	<?php
			// connect to database to retrieve id and actual name
		$con = mysqli_connect("localhost", "root", "", "tts") or die("Oops! Something went wrong. Please try again later.");
		
		// sql statement
		$sql = "
				SELECT payments.`pmt_name`, payments.`pmt_price` 
				FROM `payments` 
				LEFT JOIN `sp_record` 
				ON payments.`pmt_id`=sp_record.`pmt_id` 
				WHERE 
				sp_record.`stu_id`='" . $_SESSION["stu_id"] . "' 
				AND 
				sp_record.`spr_deleted`='0' 
				ORDER BY payments.`pmt_name` ASC;
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
		$data = mysqli_fetch_all($result, MYSQLI_BOTH) or $pmt_list_msg = "<div class='empty'>Relax, there's no payment to make at this moment.</div>";
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
			
		// close connection
		mysqli_close($con);
		
		// print out the data in a table
		if(isset($pmt_list_msg)){
			echo $pmt_list_msg;
		}else{
		?>
		<table>
			<tr>
				<th>No.</th>
				<th>Payment</th>
				<th>Price (RM)</th>
			</tr>
		<?php
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