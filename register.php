<?php

include ('includes.php');

include ('header.php');

include ('mysql_connect.php');

if(isset($_SESSION['login'])){
	header('location: /profile.php');
}


if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['password_confirmation'])){
  
  if($_POST['password']==$_POST['password_confirmation']){
	  
      $stmt = $mysqli->prepare( "INSERT INTO users(`name`,`email`,`password`,`remember_token`,`updated_at`,`user_filepath`,`num_photos`)".
      "VALUES (?,?,?,NULL,NULL,NULL,0)");

      $options = [
          'cost' => 11,
          'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
      ];
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

      $stmt->bind_param('sss',$_POST['name'], $_POST['email'], $password);

	  
      if($stmt->execute()){
		$stmt->store_result();
		$stmt->close();
		
		$id = mysqli_insert_id($mysqli);
		
		$dir = 'users/'.$id;
		
		if (!is_dir($dir)) {
			mkdir($dir, 0777);
			chmod($dir, 0777);
			mkdir($dir.'/target', 0777);
			chmod($dir.'/target', 0777);
		}

		$_SESSION['login']=$id;
		$_SESSION['user']=$_POST['name'];
		$_SESSION['registered']=1;
		
		header('Location: /profile.php');
		
	  
	  } 
	  else {
		$_SESSION['msg'] = "Email already exists";
	  }

  }else {
    $_SESSION['msg'] = "Passwords entered do not match";
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
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="register.php">


                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
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
