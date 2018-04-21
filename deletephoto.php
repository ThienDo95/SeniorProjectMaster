    <?php
	include ('includes.php');

	$userID = $_SESSION['login'];
	
	if($_POST['photos']){
		foreach($_POST['photos'] as $photo){
			unlink($photo);
			unlink("/var/www/html/users/target/1.jpg");
			passthru("python /var/www/html/pic_stitching.py $userID None");
		}
	}	 
	
	header('Location: /profile.php');
	
    ?>
	