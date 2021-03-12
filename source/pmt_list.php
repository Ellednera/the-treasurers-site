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
	<link href="css/pmt_list.css" type="text/css" rel="stylesheet"><!-- css - payment list -->
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
				SELECT `pmt_name`, `pmt_price`, `pmt_status` 
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
		$data = mysqli_fetch_all($result, MYSQLI_BOTH)or die("It looks like you don't have any payments yet<br><a class='empty' href='home.php'>Go to home page</a>");
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			// echo count($data);
			// exit;
		mysqli_close($con);
			
	?>
	<div class="main-1">
		<h2>Payment List</h2>
		
		<!-- print all the result in the form of table -->
		<table>
			<tr>
				<th>No.</th>
				<th>Payment Names</th>
				<th>Price (RM)</th>
				<th>Money still with you?</th>
			</tr>
		<?php
			for($row=0; $row<count($data); $row++){
				// set word for the "pmt_status" value
				if($data[$row]["pmt_status"] == 0){
					$status = "No";
				}else{
					$status = "Yes";
				}
		?>	
			<tr class="hover">
				<td class="number"><?php echo $row+1; ?></td><!-- numbering -->
				<td class="name"><?php echo $data[$row]["pmt_name"]; ?></td><!-- payment names -->
				<td class="price"><?php echo $data[$row]["pmt_price"]; ?></td><!-- price -->
				<td class="status"><?php echo $status; ?></td><!-- money status -->
			</tr>				
		<?php		
			}
		?>
		</table>
		
	</div>
	
</body>

</html>