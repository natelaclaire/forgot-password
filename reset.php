<?php
//two new fields would be added to the user table:  
//file reset.php
session_start();
//function to store password as a hashed value
	   function randomString()
	   {
		   $string = md5(rand());
		   return $string;
	   }
$token=$_GET['token'];
include("inc_connect.php");
if(!isset($_POST['password'])){
$SQLstring="select email from members where token='".$token."' and used=0";
$QueryResult=mysql_query($SQLstring);
while($row=mysql_fetch_array($QueryResult))
   {
$email=$row['email'];
   }
if ($email!=''){
          $_SESSION['email']=$email;
}
else die("Invalid link or Password already changed");}
$pass=$_POST['password'];
$email=$_SESSION['email'];
if(!isset($pass)){
echo '<form method="POST">
Enter your new password:<input type="password" name="password" />
<input type="submit" name = "submit" value="Change Password">
</form>
';}
if(isset($_POST['password'])&&isset($_SESSION['email']))
{
	//NEED TO APPLY OUR HASH AND SALT
	 $hash = "";
 			   $password = $_POST['password'];
			   $salt = randomString();
			   $hash = md5($password.$salt);

$SQLstring="update users set password_hash='$hash', password_salt='$salt' where email='".$email."'";
$QueryResult=mysql_query($SQLstring);
if($QueryResult)mysql_query("update tokens set used=1 where token='".$token."'");echo "Your password is changed successfully";
if(!$QueryResult)echo "An error occurred";
	}