<?php
session_start();

$username = "";
$email = "";

$errors = array();

$db = mysqli_connect('localhost','root','','practice') or die("could not connect");

$username = mysqli_real_escape_string($db, $_POST['username']);
$email = mysqli_real_escape_string($db,$_POST['email']);
$password_1 = mysqli_real_escape_string($db,$_POST['password_1']);;
$password_2 = mysqli_real_escape_string($db,$_POST['password_2']);

//form validation
if(empty($username)){array_push($errors,"Username is required")};
if(empty($email)) {array_push($errors,"Email is required")};
if(empty($password_1)) {array_push($errors,"Email is required")};
if(empty($password_1 != $password_2)) {array_push($errors,"password do not match")};

//check db for existing user with same username
$user_check_query = "SELECT * FROM user WHERE username='$username' or email = '$email' LIMIT 1";

$results = mysqli_query($db,$user_check);
$user = mysqli_fetch_assoc($results);

if($user)
{
	
	if($user['username'] === $username){array_push($errors, ""Username already exists");}
	if($user['email'] ===$email){array_push($errors,"Username already exists");}
}

//Registration process of the user

if(count($errors) == 0)
{
	$password = md5($password_1); //this will encrypt the password
	print $password;
	$query = "INSERT INTO user (username, email, password)
	       VALUES('$username','$email','$password')";
	 mysqli_query($db,$query);
	 $_SESSION['username'] = $username;
	 $_SESSION['success'] = "You are logged in";
	 header('location: index.php')
}

}
  if(isset($_POST['login_user']))
  {
  	$username = mysqli_real_escape_string($db,$_POST['username']);
  	$password = mysqli_real_escape_string($db,$_POST['password_1']);

  	if(empty($username)) {
  		array_push($errors,"Username is required");
  	}
  	if(empty($password)) {
  		array_push($errors,"Password is required");
  	}
  	if (count($errors) ==0)
  	{
  		$password = md5($password);
  		$query = "SELECT * FROM user WHERE username='$username' AND password='$password';
  		$results= mysqli_query($db, $query);
       if(mysqli_num_results($results))
       {
       	$_SESSION['username']= $username;
       	$_SESSION['success'] = "logged in successful";
       	header("location: index.php")
       }
       else
       {
       	array_push($errors, "Please Try again");
       }
  	}
  }

?>


