<?php
require("db.php");
session_start();

$username = $_SESSION['username'];

$setDate = $_POST['setDate'];
$court = $_POST['court'];
$time = $_POST['time'];
$sql = "DELETE FROM book WHERE court=$court AND time=$time AND date='$setDate' AND username='$username'";
if($db->query($sql))
	echo 'success';

?>