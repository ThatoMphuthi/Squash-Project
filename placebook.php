<?php
require("db.php");
session_start();

if(isset($_SESSION['username']))
{
	$setDate = $_POST['setDate'];
	$court = $_POST['court'];
	$time = $_POST['time'];
	
	$stop = true;
	$username = $_SESSION['username'];
	$sql = "SELECT rating FROM registeredusers WHERE username='$username'";
	$query = $db->query($sql);
	$result = $query->fetch_object();
	if($username != 'margaret')
	{
		if($setDate  >= Date('Y-m-d') && $setDate <= Date('Y-m-d', strtotime("+2 weeks")))
		{
			if($result->rating >= 3)
			{
				if($time>=23 && $time<=29)
				{
					$sql = "SELECT COUNT(bookId) FROM book WHERE username='$username' AND date='$setDate' AND time BETWEEN 23 AND 29";
					$query = $db->query($sql);
					$row = $query->fetch_row();
					$num = $row[0];
					if($num > 2)
					{
						$stop = false;
					}
				}
				if($stop == true)
				{
					$sql = "INSERT INTO book (time, court, date, username) VALUES ($time, $court, '$setDate', '$username')";
					if($db->query($sql))
						echo 'success';
					else
						echo 'error';
				}
				else
				{
					echo 'limit';
				}
			}
			else
			{
				echo 'rating';
			}
		}
		else
		{
			echo 'outofdate';
		}
	}
	else
	{
		$sql = "INSERT INTO book (time, court, date, username) VALUES ($time, $court, '$setDate', '$username')";
		if($db->query($sql))
			echo 'success';
		else
			echo 'error';
	}
}
else
{
	echo 'session';
}
?>