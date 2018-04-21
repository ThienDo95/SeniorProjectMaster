<?php
///////////////////////////
include ('includes.php');
include ('mysql_connect.php');
///////////////////////////
error_reporting(E_ALL);
ini_set('display_errors', 1);
///////////////////////////
$id = $_SESSION ['login'];
$dir = 'users/'.$id.'/';
///////////////////////////


if(!isset($_FILES['images'])) {
	$_SESSION['msg'] = "Upload Failed";
} else {
	
	$image_array = $_FILES['images'];
	
	for($i = 0;  $i < count($image_array['name']); $i++) {
		
		
		/* //IS THIS NEEDED? VARIABLE NOT USED.
		$photos = $mysqli->prepare("SELECT num_photos FROM users WHERE id = ?");
		$photos->bind_param("i", $id);
		$photos->execute();
		$photos->store_result();
		$photos->bind_result($totalphotos);
		*/
		
		$file_name = $image_array['name'][$i];
		$file_size = $image_array['size'][$i];
		$file_tmp = $image_array['tmp_name'][$i];
		$file_type = $image_array['type'][$i];
		$a = explode('/',$file_type);
		$b = end($a);
		$file_ext = strtolower($b);		
		$expensions = array("jpeg","jpg","png");
		 
		if(in_array($file_ext,$expensions) === false){
			$_SESSION['msg'] = "extension not allowed, please choose a JPEG or PNG file.";
		}
		
		if($file_size > 2097152){
			$_SESSION['msg'] ='File size must be less than or equal to 2 MB';
		}
		
		if(empty($errors)==true){
			move_uploaded_file($file_tmp,$dir.$file_name);
			chmod($dir.$file_name, 0777);
			$_SESSION['msg'] = "Upload Success";
		}else{
			$_SESSION['msg'] = $errors;
		}
		$dir .= "target/";
		$a = scandir($dir);
		$num_targets = count($a) - 2;
		
		//If there are no images in target directory, add image as target
		if($num_targets == 0) {
			$dir .= "1.jpg";
			file_put_contents($dir, $file_tmp);
			//$fn = "1.jpg";
			//move_uploaded_file($file_tmp,$dir.$fn);
			chmod($dir, 0777);
		} else {
			/*THIS IS WHERE WE STITCH PHOTO
			 * $id = user's ID
			 * $file_name = name of the new image uploaded to the user's directory
			 */
			echo "User ID - $id -";
			echo "File Name - $file_name - ";
			#WE GOT SOME ERRORS HERE
			#shell_execute("python /var/www/html/pic_stitching.py $id $file_name");
			passthru("python /var/www/html/pic_stitching.py $id $file_name $num_targets $file_size");
		}
		
		
		
	} //end loop
}
header('Location: /profile.php');

?>
