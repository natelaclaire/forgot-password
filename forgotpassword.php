<?php
/*link to open this form is 
<p><a href = "forgotpassword.php">Reset Password</a></p>
*/
/
//file name: forgotpassword.php

//NEED TO MAKE NEW TABLE NAMED TOKENS THAT HAS THREE COLUMNS:  email as the primary key, token to store auto-generated password, and used to see if the password has been reset.
if(!isset($_GET['email'])){
	                  echo'<form action="forgotpassword.php">
	                      Enter Your Email Address:
	                         <input type="text" name="email" />
	                        <input type="submit" name = "submit" value="Reset My Password" />
	                         </form>'; 
exit();
				       }
$email=$_GET['email'];
include("includes/inc_connect.php");
$SQLstring="select email from users where email='".$email."'";
$QueryResult=mysql_query($SQLstring);
$numRows=mysql_num_rows($QueryResult);
$reset = 0;

//set the used to 0
$SQLstring = "update tokens set used='$reset' where email='".$email."'";
$QueryResult=mysql_query($SQLstring);
if($numRows==0){echo "Email address is not registered";die();}
$token=getRandomString(10);
$SQLstring="insert into tokens (token,email) values ('".$token."','".$email."')";
mysql_query($SQLstring);
function getRandomString($length) 
	   {
    $validCharacters = "ABCDEFGHIJKLMNPQRSTUXYVWZ123456789";
    $validCharNumber = strlen($validCharacters);
    $result = "";
 
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
	return $result;}
 function mailresetlink($to,$token){
$subject = "Forgot Password for Chinese Zodiac Social Networking Site";
$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
$message = '
<html>
<head>
<title>Forgot Password For Chinese Zodiac Social Networking Site</title>
</head>
<body>
<p>Click on the following link to reset your password <a href="'.$uri.'/reset.php?token='.$token.'">Reset Password</a></p>
 
</body>
</html>
';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: dkokoska@maine.edu>' . "\r\n";
//$headers .= 'Cc: Admin@example.com' . "\r\n";
 
if(mail($to,$subject,$message,$headers)){
	echo "We have sent the password reset link to your  email address <strong>".$to."</strong>"; 
}}
 
if(isset($_GET['email']))mailresetlink($email,$token);

?>