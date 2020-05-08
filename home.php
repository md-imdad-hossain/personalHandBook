<?php 
  session_start(); 

  if (!isset($_SESSION['email'])) {
  	$_SESSION['msg'] = "You must log in first";
  		if($_SESSION['email']==='admin@gmail.com'){
	header('location: admin.php');
	}else{header('location: home.php');}
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: index.php");
  }
?>
<!DOCTYPE html>
<html>
<head >
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css">
<style>
header
{
    background-color:#1B2B3B;
}
body {
  
  background: #F8F8FF;
}
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 25%;
}
.sidenav {
  height: 100%;
  width: 200px;
  position: fixed;
  z-index: 1;
  top: 0px;
  left: 0px;
  background-color: #111;
  overflow-x: hidden;
  padding-top: 100px;
}

.sidenav a {
  padding: 12px 8px 6px 16px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.main {
  margin-left: 200px; /* Same as the width of the sidenav */
  font-size: 23px; /* Increased text to enable scrolling */
  padding: 0px 10px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
.sub-title{
	color: white;
	background: #5F9EA0;
	text-align: left;
	border: 1px solid #B0C4DE;

  border-radius: 10px 10px 10px 10px;
  padding: 20px;
}
.add-btn {
  padding: 15px;
  font-size: 15px;
  width: 300px;
  color: white;
  background: #5F9EA0;
  border: none;
  border-radius: 5px;
 pa
}

</style>
</head>
<body>

<header>

<img src="logoapp.png" alt="Trulli" width="300" height="200" class="center" >
</header>
<div class="sidenav">

  <a href="home.php">Profile</a>
  <a href="visitingcard.php">Visiting Cards</a>
  <a href="emailpassword.php">Email and Password</a>
  <a href="creditdebit.php">Credit/ Debit cards</a>
  <a href="home.php?logout='1'" style="color: red;">logout</a> 
</div>

<div class="main">

  	
  <div >
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
<h3 class="center">Profile</h3>
    <!-- logged in user information -->
	<div class="sub-title"> 
	<?php  if (isset($_SESSION['email'])) : ?>
	
	<p>Full Name: <strong><?php 
	$db = mysqli_connect('localhost', 'root', '', 'dbphbook');
	  $user_check_query1 = "SELECT * FROM tblusers WHERE email='". $_SESSION['email']. "'LIMIT 1";
  $result1 = mysqli_query($db, $user_check_query1);
  $user1 = mysqli_fetch_assoc($result1);
	echo $user1['name'] ?></strong></p>
    	<p>Email: <strong><?php echo $_SESSION['email']; ?></strong></p>
    	
    <?php endif ?>
	</div>
   
	
</div>
	

	


	
	
</div>




   
</body>
</html> 
