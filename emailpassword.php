<?php include('server.php') ?>
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
  width: 200px;
  color: white;
  background: #5F9EA0;
  border: none;
  border-radius: 5px;
 pa
}

.display-content{
  width: 50%;
  margin: 50px auto 0px;
  color: white;
  background: #5F9EA0;
 
  border: 1px solid #B0C4DE;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;	
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
	<div class="sub-title">
	<h3 id="emailpassword">Email and Password!</h3>
	</div>
	<br>
	 <form method="post" action="emailpassword.php">
  	<?php include('errors.php'); ?>
	
	
	<div class="header">
  	<h4>Add New / Edit or Delete Existing</h4>
    </div>
	
	
    <div class="input-group">
	<label> Domain Name</label> 
	<input type="text" name="domain_name" ></p>
	<label> Email</label> 
	<input type="text" name="save_email" ></p>
	<label> Password</label> 
	<input type="text" name="save_password" ></p>

	</div>
	
	<div>
	<button type="submit" class="add-btn" name="addemailpassword" >Add New</button> 
	<button type="submit" class="add-btn" name="editemailpassword" >Edit</button>
	<button type="submit" class="add-btn" name="deleteemailpassword" >Delete</button>
	</div>
 </form>
 <div class= "display-content">
<?php
$i=1;
$connection=mysqli_connect('localhost', 'root', '', 'dbphbook');
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$email = $_SESSION['email'];
$query_emailpasswords="SELECT * FROM tblemailpasswords WHERE email='$email'";
$result = mysqli_query($connection,$query_emailpasswords);



while($row = mysqli_fetch_array($result))
{

echo $i .". Domain Name: " .$row['domain_name'] . "<br>" ;
echo "  "."Email: " .$row['save_email']. "<br>" ; 
echo " Password: " .$row['save_password']. "<br>" ;
$i+=1;
echo "<br>";
}

mysqli_close($connection);
?>
	</div>
</div>
   
</body>
</html> 