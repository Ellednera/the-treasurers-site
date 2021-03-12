<?php
// using the old data in session
session_start();

// unset all data in session
session_unset();

// log out - go back to main page
header("Location: index.php");

?>