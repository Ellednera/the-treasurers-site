	<!-- banner for students only -->
	<div class="title-s">
		The Treasurer's Site
	</div>
	<div class="banner-s">
		Welcome back, <?php if(isset($_SESSION["username"])){echo $_SESSION["username"];}else{echo "undefined";} ?>.
	</div>