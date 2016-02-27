<?php
require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
   use google\appengine\api\cloud_storage\CloudStorageTools;
?>


<!doctype html>
<html>
<head>
<title>Registration</title>
</head>
<body>

<p><a href="register.php">Registration</a> | <a href="login.php">Login</a></p>
<h3>Registration Form</h3>
<form action="" method="POST">
Username: 	<input type="text" name="user"><br /><br />
Password: 	<input type="password" name="pass"><br />	<br />
First Name: <input type="text" name="fname"><br />	<br />
Last Name: 	<input type="text" name="lname"><br />	<br />
Email: 		<input type="email" name="email"><br />	<br />
<input type="submit" value="Registration" name="submit" />
</form>
<?php
if(isset($_POST["submit"])){

if(!empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email'])) {
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$fname=$_POST['fname'];
	$lname=$_POST['lname'];
	$email=$_POST['email'];

	$conn = mysql_connect(':/cloudsql/cloudwebstorage:sarathweb',
  'root', // username
  'root'      // password
  )or die("cannot connect");
  mysql_select_db("guestbook")or die("cannot select DB");

	$query=mysql_query("SELECT * FROM login WHERE username='".$user."'");
	$numrows=mysql_num_rows($query);
	if($numrows==0)
	{
	$sql="INSERT INTO login(username,password,firstname,lastname,email) VALUES('$user','$pass','$fname','$lname','$email')";

	$result1=mysql_query($sql);


	if($result1){
	echo "Successfully Created";
	} else {
	echo "Failed to connect!";
	}

	} else {
	echo "That user already exists! Please try again with another.";
	}

} else {
	echo "All fields are required!";
}
}
?>

</body>
</html>