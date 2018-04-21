<?php
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	//redirecting user to homepage
	$message = 'Mail has successfully sent to your email.\n';
	$message .= 'You are now redirecting back to Pi Face Recognition homepage.';
	echo "<script type='text/javascript'>alert('$message'); window.location ='index.php';</script>";
	
?>