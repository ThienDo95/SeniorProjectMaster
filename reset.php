<?php
	include ('includes.php');
	include ('header_reset_password.php');	
	include ('mysql_connect.php');

	if(isset($_SESSION['login'])){
		header('location: /index.php');
	}
	
	if(isset($_GET['key'])){
		$key = $_GET['key'];
		$result = $mysqli->query("SELECT * FROM users WHERE email = '$key'");
		$row = $result->num_rows;
			if($row < 1)
				header("Location: index.php");
	} else {
		header("Location: index.php");
	}
	
	if(isset($_POST['email']) && ($_POST['newPassword']) && ($_POST['confirm']))
	{
		//Binding variables
		$email = $_POST['email'];
	
		//Connect to database to see if email does exist in syste,
		$result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");
		$row = $result->num_rows;
		
		
		if($row > 0)
		{
			if(($_POST['newPassword']) == $_POST['confirm'])
			{
				$stmt = $mysqli->prepare( "UPDATE users SET password = ? WHERE email = '$email'");

				$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
				
				$hashedpassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT, $options);

				$stmt->bind_param('s',$hashedpassword);

			  if($stmt->execute())
			  {
				$stmt->store_result();
				$stmt->close();
				
				$message = 'Password has been succesfully changed.\n';
				$message .= 'You are now redirecting back to Pi Face Recognition login.';
				echo "<script type='text/javascript'>alert('$message'); window.location ='index.php';</script>";
				
			  }
 
			}
			else
			{
				$_SESSION['msg'] = "Password does not match with confirm password";
			}
		}
		else
		{
			$_SESSION['msg'] = "Email doesn't exist in the system";
		}
	}
	
if(isset($_SESSION['msg'])){
?>
<center>
	<span class="help-block">
		<strong style="color:red;"><?php echo $_SESSION['msg']; ?></strong>
	</span>
</center>
<?php 
unset($_SESSION['msg']);
} ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Resetting Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="reset.php">
                        
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="<?php echo $_GET['key'];?>" required autofocus>   
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label for="newPassword" class="col-md-4 control-label">New Password</label>
                            <div class="col-md-6">
                                <input id="newPassword" type="password" class="form-control" name="newPassword" value="" required autofocus>   
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm" class="col-md-4 control-label">Confirm New Password</label>
                            <div class="col-md-6">
                                <input id="confirm" type="password" class="form-control" name="confirm" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>