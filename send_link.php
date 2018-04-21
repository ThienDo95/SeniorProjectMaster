<?php

include ('includes.php');
include ('mysql_connect.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['email']))
{
 $eml = $_POST['email'];
$sql = "SELECT name,email,password
		FROM users
		WHERE email = ?";

$select = $mysqli->prepare($sql);    
$select->bind_param("s", $eml);  

if (!$select->execute()) 
{
	echo "Execute failed: (" . $select->errno . ") " . $select->error;
}
  $select->store_result();
  $select->bind_result($name, $email, $password);
  $select->fetch();
  
  if($select->num_rows==1)
  {
	  
	  
	//header so mail client doesn't believe it is plain text
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	//Addtional Headers
	$headers .= 'From: Pi Face Recognition <pifacerecognitionsystem@gmail.com>' . "\r\n";
	
    require_once('vendor/autoload.php');
	
    // the message
	$msg = '<html><body>';
	$msg .='<h3> Dear ' ;
	$msg .= $name;
	$msg .= ' ,</h3>';
	$msg .='<p>It seems like you have requested a password reset. Please link the that is provided below to reset your password.</p>';
	$msg .= '<p style="color:red;"><strong>If it was not you that requested a password reset, please contact our 24/7 support team to sort this problem out.</strong></p>';
	$msg .='<p> </p>';
	$msg .='<a href= "ec2-52-207-254-139.compute-1.amazonaws.com/reset.php?key='.$eml.'">Click Here To Reset Password </a>';
	$msg .='<p> </p>';
	$msg .='<p>Thank you</p>';
	$msg .='<p>Pi Face Recognition</p>';
	$msg .= '</body></html>';

	// use wordwrap() if lines are longer than 70 characters
	$msg = wordwrap($msg,70);

	mail($email, "Pi Face Recognition Resetting Password", $msg, $headers);
	header("Location: confirmation.php");
  }	
}
?>
