<?php
require("db.php");
session_start();
$name = "Squash Courts";
$login = "<li class='nav-item mx-0 mx-lg-1'>
            <a class='nav-link py-3 px-0 px-lg-3 rounded' href='login.php'>Log In</a>
          </li>";
$username = "";
$phone = "";
$feed="";
$adminPage = "";
if(isset($_POST['name'], $_POST['phone'], $_POST['password'], $_POST['conpass']))
{
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$password = $_POST['password'];
	$conpass = $_POST['conpass'];
	if(!empty($name) && !empty($phone) && !empty($password) && !empty($conpass))
	{
		if($conpass == $password)
		{
			$sql = "UPDATE registeredusers SET name='$name', phone='$phone', password='$password' WHERE username='".$_SESSION['username']."'";
			if($db->query($sql))
			{
				$feed = "<div class='feed succ fixed-bottom'>
							<span>Profile updated</span>
							<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
						  </div>";
			}
		}
		else
		{
			$feed = "<div class='feed err fixed-bottom'>
						<span>Password does not match</span>
						<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
					  </div>";
		}
	}
	else
	{
		$feed = "<div class='feed err fixed-bottom'>
						<span>Empty field(s)</span>
						<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
					  </div>";
	}
}
if(isset($_SESSION['username']))
{
	$login = "<li class='nav-item mx-0 mx-lg-1'>
            <a class='nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger' href='#edit'>Edit Profile</a>
          </li>
		  <li class='nav-item mx-0 mx-lg-1'>
            <a class='nav-link py-3 px-0 px-lg-3 rounded' href='login.php'>Log Out</a>
          </li>";
	$username =$_SESSION['username'];
	$sql = "SELECT name, username, phone FROM registeredusers WHERE username='$username'";
	if($query = $db->query($sql))
	{
		$result = $query->fetch_object();
		$name = $result->name;
		$username = $result->username;
		$phone = $result->phone;
	}
	
	if($username == 'margaret')
	{
		$adminPage = "<li class='nav-item mx-0 mx-lg-1'>
						<a class='nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger' href='#admin'>Admin</a>
					  </li>";
	}
}
$year = date('Y', time());
$month = date('m', time());
$day = date('d', time());
$setDate = $year.'-'.$month.'-'.$day;

if(isset($_GET['date']))
	$setDate = $_GET['date'];

$temp = 0;
$allSlots = "";
$sql = "SELECT COUNT(bookId) FROM book WHERE date='$setDate'";
$query = $db->query($sql);
$row = $query->fetch_row();
$num = $row[0];
$sql = "SELECT * FROM book WHERE date='$setDate'";
if($query = $db->query($sql))
{
	$row = $query->fetch_all();
	for($i = 1; $i<=8; $i++)
	{
		$allSlots .= '<tr><td>Court-'.$i.'</td>';
		for($k = 1; $k <= 33; $k++)
		{
			for($z = 0; $z < $num; $z++)
			{
				if($row[$z][1] == $k && $row[$z][2] == $i)
				{
					$temp = 1;
					if(isset($_SESSION['username']))
					{
						if($_SESSION['username'] == $row[$z][4])
						{
							$allSlots .= '<td id="bookedbyyou" c='.$i.' t='.$k.'></td>';
						}
						else
						{
							$allSlots .= '<td id="booked" c='.$i.' t='.$k.'></td>';
						}
					}
					else
					{
						$allSlots .= '<td id="booked" c='.$i.' t='.$k.'></td>';
					}
					break;
				}
			}
			if($temp != 1)
			{
				$allSlots .= '<td id="unbooked" c='.$i.' t='.$k.'></td>';
			}
			$temp = 0;
		}
		$allSlots .= '</tr>';
	}
}

$penalizeList = "";
$time = "";
if(isset($_GET['time']))
{
	$year = date('Y', time());
	$month = date('m', time());
	$day = date('d', time());
	$today = $year.'-'.$month.'-'.$day;

	$time = $_GET['time'];

	$sql = "SELECT username FROM book WHERE date='$today' AND time=$time";
	if($query = $db->query($sql))
	{
		while($row = $query->fetch_assoc())
		{
			$penalizeList .= "<tr>
								<td>".$row['username']."</td>
								<td><a href='index.php?time=".$time."&pen=".$row['username']."#admin'>Penalize</a></td>
							</tr>";
		}
	}
}

if(isset($_GET['pen']))
{
	$pen = $_GET['pen'];
	$sql = "UPDATE registeredusers SET rating = rating - 1 WHERE username='$pen'";
	if($db->query($sql))
	{
		$feed = "<div class='feed succ fixed-bottom'>
					<span>You just penalized $pen</span>
					<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
				  </div>";
	}
}

if(isset($_GET['restore']))
{
	$restore = $_GET['restore'];
	$sql = "UPDATE registeredusers SET rating = 10 WHERE username='$restore'";
	if($db->query($sql))
	{
		$feed = "<div class='feed succ fixed-bottom'>
					<span>You just restored $restore's rating</span>
					<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
				  </div>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Squash Courts</title>

  <!-- Custom fonts for this theme -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Theme CSS -->
  <link href="css/freelancer.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="index.php">Squash</a>
      <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#book">Book Court</a>
          </li>
		  <?php echo $login;?>
		  <?php echo $adminPage;?>
        </ul>
      </div>
    </div>
  </nav>
	<?php echo $feed;?>
  <!-- Masthead -->
  <header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Avatar Image -->
      <img class="masthead-avatar mb-5" src="img/avataaars.svg" alt="">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0"><?php echo $name;?></h1>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">Select Slot and Court to Book for Given Date</p>

    </div>
  </header>

  <!-- Portfolio Section -->
  <section class="page-section portfolio" id="book">
    <div class="container">
		<h2 class="page-section-heading text-center text-uppercase">Slots</h2>
		<div class="input-append text-center date">
			<form method="get" action="#book" name="sentMessage" id="contactForm" novalidate="novalidate">
				<input id="calender" name="date" type="date" value="<?php echo $setDate;?>"/><br/>
				<button type="submit" style="background-color: #0799fb;border-color: #0a97c5;padding: 5px 10px;margin-bottom: 5px;" class="btn btn-primary btn-xl" id="sendMessageButton">Submit</button>
			</form>
		</div> 
      <div class="row">
        <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
				
					<!--========================================================-->
					<div id="time-table" class="table-responsive">
						<table id="time" class="table table-bordered table-responsive table-hover">
							<thead>
								<tr>
									<th>Venue</th>
									<th>06:00</th>
									<th>06:30</th>
									<th>07:00</th>
									<th>07:30</th>
									<th>08:00</th>
									<th>08:30</th>
									<th>09:00</th>
									<th>09:30</th>
									<th>10:00</th>
									<th>10:30</th>
									<th>11:00</th>
									<th>11:30</th>
									<th>12:00</th>
									<th>12:30</th>
									<th>13:00</th>
									<th>13:30</th>
									<th>14:00</th>
									<th>14:30</th>
									<th>15:00</th>
									<th>15:30</th>
									<th>16:00</th>
									<th>16:30</th>
									<th>17:00</th>
									<th>17:30</th>
									<th>18:00</th>
									<th>18:30</th>
									<th>19:00</th>
									<th>19:30</th>
									<th>20:00</th>
									<th>20:30</th>
									<th>21:00</th>
									<th>21:30</th>
									<th>22:00</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $allSlots;?>
							</tbody>
						</table>
					</div>
					<!--========================================================-->
					
				</div>
			</div>
		</div>

      </div>
    </div>
  </section>
<?php 
if(isset($_SESSION['username']))
{
?>
  <section class="page-section bg-primary text-white mb-0" id="edit">
    <div class="container">
      <h2 class="page-section-heading text-center text-uppercase text-white">Edit Profile</h2>
      <div class="row">
		
		<div class="col-lg-8 mx-auto">
          <form method="post" name="sentMessage" id="contactForm" novalidate="novalidate">
            <div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Name</label>
                <input class="form-control" id="name" name="name" type="text" value="<?php echo $name?>" placeholder="Name" required="required">
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Number</label>
                <input class="form-control" id="phone" name="phone" value="<?php echo $phone?>" type="tel" placeholder="Number"/>
                <p class="help-block text-danger"></p>
              </div>
            </div>
			<div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Username</label>
                <input class="form-control" id="name" name="username" type="text" value="<?php echo $username?>" placeholder="Username" disabled/>
                <p class="help-block text-danger"></p>
              </div>
            </div>
			<div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Password</label>
                <input class="form-control" id="name" name="password" type="password" placeholder="Password"/>
                <p class="help-block text-danger"></p>
              </div>
            </div>
			<div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Confirm Password</label>
                <input class="form-control" id="name" name="conpass" type="password" placeholder="Confirm Password"/>
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <br>
            <div id="success"></div>
            <div class="form-group">
              <button type="submit" style="background-color: #0799fb;border-color: #0a97c5;" class="btn btn-primary btn-xl" id="sendMessageButton">Edit</button>
            </div>
          </form>
        </div>
		
      </div>
    </div>
  </section>
<?php 
}
if($username == 'margaret')
{
?>

	<section class="page-section portfolio" id="admin">
		<div class="container">
			<h2 class="page-section-heading text-center text-uppercase">Admin</h2>
		  <div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
					
						<!--========================================================-->
						<div id="time-table" class="table-responsive">
							<table id="time" class="table table-bordered table-responsive table-hover">
								<tbody style="float: right;">
									<tr>
										<td>
											<form method="get" action="index.php#admin">
												<select name="time">
													<option value="default">Select Time</option>
													<option value="1">06:00</option>
													<option value="2">06:30</option>
													<option value="3">07:00</option>
													<option value="4">07:30</option>
													<option value="5">08:00</option>
													<option value="6">08:30</option>
													<option value="7">09:00</option>
													<option value="8">09:30</option>
													<option value="9">10:00</option>
													<option value="10">10:30</option>
													<option value="11">11:00</option>
													<option value="12">11:30</option>
													<option value="13">12:00</option>
													<option value="14">12:30</option>
													<option value="15">13:00</option>
													<option value="16">13:30</option>
													<option value="17">14:00</option>
													<option value="18">14:30</option>
													<option value="19">15:00</option>
													<option value="20">15:30</option>
													<option value="21">16:00</option>
													<option value="22">16:30</option>
													<option value="23">17:00</option>
													<option value="24">17:30</option>
													<option value="25">18:00</option>
													<option value="26">18:30</option>
													<option value="27">19:00</option>
													<option value="28">19:30</option>
													<option value="29">20:00</option>
													<option value="30">20:30</option>
													<option value="31">21:00</option>
													<option value="32">21:30</option>
													<option value="33">22:00</option>
												</select>
											
										</td>
										<td>
											<button type="submit" style="background-color: #0799fb;border-color: #0a97c5;padding: 5px 10px;margin-bottom: 5px;" class="btn btn-primary btn-xl" id="sendMessageButton">Submit</button>
											</form>
										</td>
									</tr>
									<?php echo $penalizeList;?>
								</tbody>
							</table>
						</div>
						<!--========================================================-->
						
					</div>
				</div>
			</div>
			
			<div class='col-md-6'>
				<div class='panel panel-default'>
					<div class='panel-body'>
					
						<!--========================================================-->
						<div id='time-table' class='table-responsive'>
							<table id='time' class='table table-bordered table-responsive table-hover'>
								<tbody>
									<tr>
										<td>
											<form method='get' action='index.php#admin'>
												<input class='form-control' id='name' name='restore' type='text' placeholder='Username'/>
										</td>
										<td>
												<button type='submit' style='background-color: #0799fb;border-color: #0a97c5;padding: 5px 10px;margin-bottom: 5px;' class='btn btn-primary btn-xl' id='sendMessageButton'>Submit</button>
											</form>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--========================================================-->
						
					</div>
				</div>
			</div>

		  </div>
		</div>
  </section>
<?php 
}
?>
  
  <!-- Copyright Section -->
  <section class="copyright py-4 text-center text-white">
    <div class="container">
      <small>Copyright &copy; Squash Team</small>
    </div>
  </section>

  <script src="js/jquery-1.10.2.js"></script>
  <script src="js/bootstrap.bundle.js"></script>
  <script src="js/freelancer.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>
