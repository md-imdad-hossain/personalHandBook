<?php
session_start();

// initializing variables
$name    = "";
$phone    = "";
$email    = "";
$errors = array();
$domain_name= "";
$save_email= "";
$save_password= ""; 
$times = 0;

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'dbphbook');

// REGISTER USER
if (isset($_POST['reg_user'])) {
	// receive all input values from the form
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$phone = mysqli_real_escape_string($db, $_POST['phone']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($name)) { array_push($errors, "Name is required"); }
	if (empty($phone)) { array_push($errors, "Phone Number is required"); }
	if (empty($email)) { array_push($errors, "Email is required"); }
	if (empty($password_1)) { array_push($errors, "Password is required"); }
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}
	if(1 === preg_match('~[0-9]~', $name)){ array_push($errors, "Improper: Name should not contain number"); }
	if(!(1 === preg_match('~[0-9]~', $phone))){ array_push($errors, "Improper: Phone should contain number only"); }
	if(! preg_match('/[A-Z]/', $password_1)){ array_push($errors, "Password must contain a upper case"); }
	if(!preg_match('~[0-9]~', $password_1)){ array_push($errors, "Password must contain a number"); }
	if(strlen($password_1)< 8){ array_push($errors, "Password must be 8 digits long");} 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$user_check_query = "SELECT * FROM tblusers WHERE email='$email' LIMIT 1";
	$result = mysqli_query($db, $user_check_query);
	$user = mysqli_fetch_assoc($result);
  
  

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  

	// Finally, register user if there are no errors in the form
	if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO tblusers (name, phone, email, password) 
  			  VALUES('$name', '$phone', '$email', '$password')";
  	mysqli_query($db, $query);
 
  	array_push($errors,"Registered successfully");

	}
}



// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
	
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	if (count($errors) == 0) {
		$password = md5($password);
		$query = "SELECT * FROM tblusers WHERE email='$email' AND password='$password'";
		//$query1 = "SELECT LAST_INSERT_ID();"
		$results = mysqli_query($db, $query);
		if (mysqli_num_rows($results) == 1) {
			$user_check_query1 = "SELECT * FROM tblusers WHERE email='$email' LIMIT 1";
		$result1 = mysqli_query($db, $user_check_query1);
		$user1 = mysqli_fetch_assoc($result1);
		$_SESSION['email'] = $email;
		$_SESSION['success'] = "Welcome " . $user1['name']  ;
		if($email==='admin@gmail.com'){header('location: admin.php');}
		else{header('location: home.php');}
  	}else {
		//$times++;
  		array_push($errors, "Wrong email/password combination");
		
		if($times > 3){array_push($errors, "You tried too many times . Please try angain later");
		//sleep(18000);
		}
	 }
	}
}

//Adding Email & Password

if (isset($_POST['addemailpassword'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$domain_name = mysqli_real_escape_string($db, $_POST['domain_name']);
	$save_email= mysqli_real_escape_string($db, $_POST['save_email']);
	$save_password = mysqli_real_escape_string($db, $_POST['save_password']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($domain_name)) { array_push($errors, "Domain Name is required"); }
	if (empty($save_email)) { array_push($errors, "Email is required"); }
	if (empty($save_password)) { array_push($errors, "Password is required"); }

	if(1 === preg_match('~[0-9]~', $domain_name)){ array_push($errors, "Improper: Domain Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $save_email)){ array_push($errors, "Improper: Email should not contain number"); }
	 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$domain_check_query = "SELECT * FROM tblemailpasswords WHERE domain_name='$domain_name' LIMIT 1";
	$result_domain = mysqli_query($db, $domain_check_query);
	$item= mysqli_fetch_assoc($result_domain);
	  

	if ($item['domain_name'] === $domain_name) {
		  array_push($errors, "Domain name already exists");
	}
	  

	// Finally, register domain if there are no errors in the form
	if (count($errors) == 0) {
	//  $user_check_query3 = "SELECT * FROM tblusers WHERE email='$email' LIMIT 1";
	//$result3 = mysqli_query($db, $user_check_query3);
	// $user3 = mysqli_fetch_assoc($result3);
		$query = "INSERT INTO tblemailpasswords (email, domain_name, save_email, save_password) 
				  VALUES('$email', '$domain_name', '$save_email', '$save_password')";
		if(mysqli_query($db, $query) && !empty($domain_name)){
		array_push($errors, "Added Successfully");
		}
	  }
}
  
//Editing Email & Password

if (isset($_POST['editemailpassword'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$domain_name = mysqli_real_escape_string($db, $_POST['domain_name']);
	$save_email= mysqli_real_escape_string($db, $_POST['save_email']);
	$save_password = mysqli_real_escape_string($db, $_POST['save_password']);
	
	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($domain_name)) { array_push($errors, "Domain Name is required"); }
	if (empty($save_email)) { array_push($errors, "Email is required"); }
	if (empty($save_password)) { array_push($errors, "Password is required"); }

	if(1 === preg_match('~[0-9]~', $domain_name)){ array_push($errors, "Improper: Domain Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $save_email)){ array_push($errors, "Improper: Email should not contain number"); }
	 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$domain_check_query = "SELECT * FROM tblemailpasswords WHERE domain_name='$domain_name' LIMIT 1";
	$result_domain = mysqli_query($db, $domain_check_query);
	$item= mysqli_fetch_assoc($result_domain);
	  

	if ($item['domain_name'] == $domain_name) {
		// Finally, edit domain if there are no errors in the form
		$query = "UPDATE tblemailpasswords SET save_email ='$save_email ', save_password='$save_password ' WHERE email='$email' AND domain_name='$domain_name'";
		if(mysqli_query($db, $query)&& !empty($domain_name)){
			array_push($errors, "Updated Successfully");
		}
	}else{
		array_push($errors, "Domain name does not  exists");
	   }

}


//Deleting Email & Password

if (isset($_POST['deleteemailpassword'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$domain_name = mysqli_real_escape_string($db, $_POST['domain_name']);
	$save_email= mysqli_real_escape_string($db, $_POST['save_email']);
	$save_password = mysqli_real_escape_string($db, $_POST['save_password']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($domain_name)) { array_push($errors, "Domain Name is required"); }

	if(1 === preg_match('~[0-9]~', $domain_name)){ array_push($errors, "Improper: Domain Name should not contain number"); }
	 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$domain_check_query = "SELECT * FROM tblemailpasswords WHERE domain_name='$domain_name' LIMIT 1";
	$result_domain = mysqli_query($db, $domain_check_query);
	$item= mysqli_fetch_assoc($result_domain);
	  

	if ($item['domain_name'] == $domain_name) {
		// Finally, edit domain if there are no errors in the form
	   $query = "Delete FROM tblemailpasswords WHERE email='$email' AND domain_name='$domain_name'";
		echo mysqli_query($db, $query);
		if(mysqli_query($db, $query)&& !empty($domain_name)){
		
		array_push($errors, "Deleted Successfully");
		}
	  }else{
		array_push($errors, "Domain name does not  exists");
		}

}
  
//Adding Vsiting Cards

if (isset($_POST['addvcard'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$vcard_name = mysqli_real_escape_string($db, $_POST['vcard_name']);
	$organization= mysqli_real_escape_string($db, $_POST['organization']);
	$address = mysqli_real_escape_string($db, $_POST['address']);
	
	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($vcard_name)) { array_push($errors, "Card Name is required"); }
	if (empty($organization)) { array_push($errors, "Organization is required"); }
	if (empty($address)) { array_push($errors, "Address is required"); }

	if(1 === preg_match('~[0-9]~', $vcard_name)){ array_push($errors, "Improper: Card Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $organization)){ array_push($errors, "Improper: Organization should not contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$vcard_check_query = "SELECT * FROM tblvisitingcards WHERE vcard_name='$vcard_name' AND email='$email' LIMIT 1";
	$result_vcard = mysqli_query($db, $vcard_check_query);
	$item= mysqli_fetch_assoc($result_vcard);
	
	
	if ($item['vcard_name'] === $vcard_name) {
	array_push($errors, "Card name already exists");
	}
	

	// Finally, register domain if there are no errors in the form
	if (count($errors) == 0 && (getimagesize($_FILES['image']['tmp_name']) == true)) {
	
	
	$banner=$_FILES['image']['name']; 
	$expbanner=explode('.',$banner);
	$bannerexptype=$expbanner[1];
	date_default_timezone_set('Australia/Melbourne');
	$date = date('m/d/Yh:i:sa', time());
	$rand=rand(10000,99999);
	$encname=$date.$rand;
	$bannername=md5($encname).'.'.$bannerexptype;
	move_uploaded_file($_FILES["image"]["tmp_name"],$bannername);

	
  	$query = "INSERT INTO tblvisitingcards (email, vcard_name, address, vcard_picture, organization) 
  			  VALUES('$email', '$vcard_name', '$address', '$bannername', '$organization')";
			  
  	if(mysqli_query($db, $query) && !empty($vcard_name)){
  	array_push($errors, "Added Successfully");
	}
  }
  if(count($errors) == 0 ){
	  array_push($errors, "You must select a picture");
  }
}
  
//Editing Visiting Crads

if (isset($_POST['editvcard'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$vcard_name = mysqli_real_escape_string($db, $_POST['vcard_name']);
	$organization= mysqli_real_escape_string($db, $_POST['organization']);
	$address = mysqli_real_escape_string($db, $_POST['address']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($vcard_name)) { array_push($errors, "Card Name is required"); }
	if (empty($organization)) { array_push($errors, "Organization is required"); }
	if (empty($address)) { array_push($errors, "Address is required"); }

	if(1 === preg_match('~[0-9]~', $vcard_name)){ array_push($errors, "Improper: Card Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $organization)){ array_push($errors, "Improper: Organization should not contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$vcard_check_query = "SELECT * FROM tblvisitingcards WHERE vcard_name='$vcard_name' AND email='$email' LIMIT 1";
	$result_vcard = mysqli_query($db, $vcard_check_query);
	$item= mysqli_fetch_assoc($result_vcard);
  
	// Finally, edit domain if there are no errors in the form
	if (($item['vcard_name'] === $vcard_name) && (getimagesize($_FILES['image']['tmp_name']) == true)) {
	
	
	
	$banner=$_FILES['image']['name']; 
	$expbanner=explode('.',$banner);
	$bannerexptype=$expbanner[1];
	date_default_timezone_set('Australia/Melbourne');
	$date = date('m/d/Yh:i:sa', time());
	$rand=rand(10000,99999);
	$encname=$date.$rand;
	$bannername=md5($encname).'.'.$bannerexptype;
	move_uploaded_file($_FILES["image"]["tmp_name"],$bannername);

   
	$query = "UPDATE tblvisitingcards SET address='$address ', vcard_picture='$bannername', organization='$organization ' WHERE email='$email' AND vcard_name='$vcard_name'";
	if(mysqli_query($db, $query)&& !empty($vcard_name)){
	
		array_push($errors, "Updated Successfully");
		}
	}
	
	else if(!($item['vcard_name'] === $vcard_name)){
		array_push($errors, "Card name does not  exists");
	}
	else{
		array_push($errors, "You must select a picture");	
	}
}


//Deleting Visiting Cards

if (isset($_POST['deletevcard'])) {
	// receive all input values from the form
	// receive all input values from the form
	$email = $_SESSION['email'];
	$vcard_name = mysqli_real_escape_string($db, $_POST['vcard_name']);
	$organization= mysqli_real_escape_string($db, $_POST['organization']);
	$address = mysqli_real_escape_string($db, $_POST['address']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($vcard_name)) { array_push($errors, "Card Name is required"); }
	if(1 === preg_match('~[0-9]~', $vcard_name)){ array_push($errors, "Improper: Card Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $organization)){ array_push($errors, "Improper: Organization should not contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$vcard_check_query = "SELECT * FROM tblvisitingcards WHERE vcard_name='$vcard_name' AND email='$email' LIMIT 1";
	$result_vcard = mysqli_query($db, $vcard_check_query);
	$item= mysqli_fetch_assoc($result_vcard);
  

    if ($item['vcard_name'] === $vcard_name) {
		// Finally, edit domain if there are no errors in the form
		$query = "Delete FROM tblvisitingcards WHERE email='$email' AND vcard_name='$vcard_name'";
		if(mysqli_query($db, $query)&& !empty($vcard_name)){
	
			array_push($errors, "Deleted Successfully");
		}
	}else{
		array_push($errors, "Card name does not  exists");
	}

}
  
  
//Adding Credit/Debit Cards

if (isset($_POST['addcreditdebit'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$bank = mysqli_real_escape_string($db, $_POST['bank']);
	$card_name= mysqli_real_escape_string($db, $_POST['card_name']);
	$card_number = mysqli_real_escape_string($db, $_POST['card_number']);
	$pin = mysqli_real_escape_string($db, $_POST['pin']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($bank)) { array_push($errors, "Bank Name is required"); }
	if (empty($card_name)) { array_push($errors, "Card Name is required"); }
	if (empty($card_number)) { array_push($errors, "Card Number is required"); }

	if(1 === preg_match('~[0-9]~', $bank)){ array_push($errors, "Improper: Bank Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $card_name)){ array_push($errors, "Improper: Card Name should not contain number"); }
	if(!(1 === preg_match('~[0-9]~', $card_number))){ array_push($errors, "Improper: Card Number should contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$card_check_query = "SELECT * FROM tblcreditdebit WHERE card_number='$card_number' AND email='$email' LIMIT 1";
	$result_card = mysqli_query($db, $card_check_query);
	$item= mysqli_fetch_assoc($result_card);
  

    if ($item['card_number'] === $card_number) {
      array_push($errors, "Same Card number already exists");
    }
  

	// Finally, register domain if there are no errors in the form
	if (count($errors) == 0 && (getimagesize($_FILES['image']['tmp_name']) == true)) {
	
	
		$banner=$_FILES['image']['name']; 
		$expbanner=explode('.',$banner);
		$bannerexptype=$expbanner[1];
		date_default_timezone_set('Australia/Melbourne');
		$date = date('m/d/Yh:i:sa', time());
		$rand=rand(10000,99999);
		$encname=$date.$rand;
		$bannername=md5($encname).'.'.$bannerexptype;
		move_uploaded_file($_FILES["image"]["tmp_name"],$bannername);

		$query = "INSERT INTO tblcreditdebit (email, bank, card_name, card_number, pin, card_picture) 
  			  VALUES('$email', '$bank', '$card_name', '$card_number','$pin', '$bannername')";
		if(mysqli_query($db, $query) && !empty($card_number)){
		array_push($errors, "Added Successfully");
		}
	}
	if(count($errors) == 0){
	  array_push($errors, "You must select a picture");
  }
}
  
//Editing Debit/Credit Cards

if (isset($_POST['editcreditdebit'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$bank = mysqli_real_escape_string($db, $_POST['bank']);
	$card_name= mysqli_real_escape_string($db, $_POST['card_name']);
	$card_number = mysqli_real_escape_string($db, $_POST['card_number']);
	$pin = mysqli_real_escape_string($db, $_POST['pin']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($bank)) { array_push($errors, "Bank Name is required"); }
	if (empty($card_name)) { array_push($errors, "Card Name is required"); }
	if (empty($card_number)) { array_push($errors, "Card Number is required"); }

	if(1 === preg_match('~[0-9]~', $bank)){ array_push($errors, "Improper: Bank Name should not contain number"); }
	if(1 === preg_match('~[0-9]~', $card_name)){ array_push($errors, "Improper: Card Name should not contain number"); }
	if(!(1 === preg_match('~[0-9]~', $card_number))){ array_push($errors, "Improper: Card Number should contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$card_check_query = "SELECT * FROM tblcreditdebit WHERE card_number='$card_number' AND email='$email' LIMIT 1";
	$result_card = mysqli_query($db, $card_check_query);
	$item= mysqli_fetch_assoc($result_card);
  
	// Finally, edit domain if there are no errors in the form
    if (($item['card_number'] === $card_number) && (getimagesize($_FILES['image']['tmp_name']) == true)) {
	
	
		$banner=$_FILES['image']['name']; 
		$expbanner=explode('.',$banner);
		$bannerexptype=$expbanner[1];
		date_default_timezone_set('Australia/Melbourne');
		$date = date('m/d/Yh:i:sa', time());
		$rand=rand(10000,99999);
		$encname=$date.$rand;
		$bannername=md5($encname).'.'.$bannerexptype;
		move_uploaded_file($_FILES["image"]["tmp_name"],$bannername);

   
   
		$query = "UPDATE tblcreditdebit SET bank='$bank', card_name='$card_name', pin='$pin', card_picture='$bannername' WHERE email='$email' AND card_number='$card_number'";
		if(mysqli_query($db, $query)&& !empty($card_number)){
	
		array_push($errors, "Updated Successfully");
		}
	}
	else if(!($item['card_number'] === $card_number)){
		array_push($errors, "Card number does not  exists");
	}
	else{
		array_push($errors, "You must select a picture");	
	}

}


//Deleting Debit/Credit Cards

if (isset($_POST['deletecreditdebit'])) {
	// receive all input values from the form
	$email = $_SESSION['email'];
	$bank = mysqli_real_escape_string($db, $_POST['bank']);
	$card_name= mysqli_real_escape_string($db, $_POST['card_name']);
	$card_number = mysqli_real_escape_string($db, $_POST['card_number']);
	$pin = mysqli_real_escape_string($db, $_POST['pin']);

	// form validation: ensure that the form is correctly filled ...
	// by adding (array_push()) corresponding error unto $errors array
	if (empty($card_number)) { array_push($errors, "Card Number is required"); }

	if(!(1 === preg_match('~[0-9]~', $card_number))){ array_push($errors, "Improper: Card Number must  contain number"); }
 
	// first check the database to make sure 
	// a user does not already exist with the same username and/or email
	$card_check_query = "SELECT * FROM tblcreditdebit WHERE card_number='$card_number' AND email='$email' LIMIT 1";
	$result_card = mysqli_query($db, $card_check_query);
	$item= mysqli_fetch_assoc($result_card);
  

    if ($item['card_number'] === $card_number) {
		// Finally, edit domain if there are no errors in the form
   
		$query = "DELETE FROM tblcreditdebit WHERE email='$email' AND card_number='$card_number'";
		if(mysqli_query($db, $query)&& !empty($card_number)){
	
		array_push($errors, "Deleted Successfully");
	}
	}else{
		array_push($errors, "Card name does not  exists");
	}
	

}
  
//Deleting USERS

if (isset($_POST['user_del'])) {
	// receive all input values from the form
	$del_email =  mysqli_real_escape_string($db, $_POST['del_email']);
	$check_query = "SELECT * FROM tblusers WHERE email ='$del_email' LIMIT 1";
	$result= mysqli_query($db, $check_query);
	$item= mysqli_fetch_assoc($result);
	if ($item['email'] === $del_email) {
		// Finally, edit domain if there are no errors in the form
   
		$query = "DELETE FROM tblusers WHERE email='$del_email'";
	if(mysqli_query($db, $query)){
		array_push($errors, "Deleted Successfully");
	}
	}else{
		array_push($errors, "Email does not  exists");
	}
}
?>