
	<!-- banner for students only -->
	<div class="title-s">
		The Treasurer's Site
	</div>
	<div class="banner-s">
		Welcome back, <?php if(isset($_SESSION["username"])){echo $_SESSION["username"];}else{echo "undefined";} ?>.
		
		<form class="view" method="get" action="home.php">
			Viewing as: 
			<select name="view">
			
				<!-- viewing as treasurer -->
				<option value="2" <?php if(isset($_SESSION["view"])){if($_SESSION["view"] == 2){echo "selected";}} ?>>treasurer</option>
				
				<!-- viewing as student -->
				<option value="1" <?php if(isset($_SESSION["view"])){if($_SESSION["view"] == 1){echo "selected";}} ?>>student</option>
				
			</select>
			
			<input type="Submit" value="Change view">
			
		</form>
		
	</div>