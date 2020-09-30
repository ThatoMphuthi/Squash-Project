<?php
$db = new mysqli('localhost', 'root', '', 'lutendo');
if($db->connect_errno)
{
	die('Sorry, we are having some problems.');
}
?>