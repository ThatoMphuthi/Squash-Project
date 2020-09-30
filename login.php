<?php
require("db.php");
session_start();
$feed = "";
if(isset($_SESSION['username']))
{
	unset($_SESSION['username']);
}
if(isset($_POST['username'], $_POST['password']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$sql = "SELECT username FROM registeredusers WHERE username='$username' AND password='$password'";
	if($query = $db->query($sql))
	{
		if($query_user_row = $query->num_rows)
		{
			if($query_user_row == 1)
			{
				$result = $query->fetch_object();
				$_SESSION['username'] = $result->username;
				echo $result->username;
				header("Location: index.php");
			}
			else
			{
				$feed = "<div class='feed err fixed-bottom'>
							<span>Username or Password Incorrect</span>
							<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
						  </div>";
			}
		}
		else
		{
			$feed = "<div class='feed err fixed-bottom'>
						<span>Account does not exist</span>
						<span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span>
					  </div>";
		}
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
  <nav class="navbar navbar-expand-lg bg-secondary text-uppercase" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top">Squash</a>
      <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="index.php">Book Court</a>
          </li>
		  <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <?php echo $feed;?>

  <section class="page-section bg-primary text-white mb-0" style="height: 1000px;" id="edit">
    <div class="container">
      <h2 class="page-section-heading text-center text-uppercase text-white">Log In To Account</h2>
      <div class="row">
		
		<div class="col-lg-8 mx-auto">
          <form method="post" name="sentMessage" id="contactForm" novalidate="novalidate">
			<div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label>Username</label>
                <input class="form-control" id="username" name="username" type="text" placeholder="Username"/>
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
            <br>
            <div id="success"></div>
            <div class="form-group">
              <button type="submit" style="background-color: #0799fb;border-color: #0a97c5;" class="btn btn-primary btn-xl" id="sendMessageButton">Login</button>
            </div>
          </form>
        </div>
		
      </div>
    </div>
  </section>
  
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
