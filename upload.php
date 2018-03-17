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

if(isset($_FILES['image'])){
	 $photos = $mysqli->prepare("SELECT num_photos FROM users WHERE id = ?");
	  $photos->bind_param("i", $id);
	  $photos->execute();
	  $photos->store_result();
	  $photos->bind_result($totalphotos);
	  
	  $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$file_type)));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
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

} else {
$_SESSION['msg'] = "Upload Failed";	
}

header('Location: /profile.php');



?>