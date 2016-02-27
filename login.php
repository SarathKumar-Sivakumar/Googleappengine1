<?php

require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
   use google\appengine\api\cloud_storage\CloudStorageTools;

   ob_start();
   session_start();
?>


<!doctype html>
<html>
<head>
<title>Login</title>
</head>
<body>

<p><a href="register.php">Registration</a> | <a href="login.php">Login</a></p>
<h3>Login </h3>
<form action="" method="POST">
Username: <input type="text" name="user"><br />
Password: <input type="password" name="pass"><br />	
<input type="submit" value="Login" name="submit" />
</form>
<?php
if(isset($_POST["submit"])){

if(!empty($_POST['user']) && !empty($_POST['pass'])) {
	$user=$_POST['user'];
	$pass=$_POST['pass'];

	$conn = mysql_connect(':/cloudsql/cloudwebstorage:sarathweb',
  'root', // username
  'root'      // password
  )or die("cannot connect");
  mysql_select_db("guestbook")or die("cannot select DB");

	$query=mysql_query("SELECT * FROM login WHERE username='".$user."' AND password='".$pass."'");
	$numrow=mysql_num_rows($query);
	if($numrow!=0)
	{
	while($rows=mysql_fetch_assoc($query))
	{
	$dbusername=$rows['username'];
	$dbpassword=$rows['password'];
	}

	if($user == $dbusername && $pass == $dbpassword)
	{
	
		$_SESSION['sess_user']=$user;
	/* Redirect browser */
	header('Location: upload.php');
	}
	} else {
	echo "Invalid user or password!";
	}

} else {
	echo "All fields are required!";
}
}
?>

</body>
</html>