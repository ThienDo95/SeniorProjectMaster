<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



include ('includes.php');

include ('header.php');

include ('mysql_connect.php');

if(!isset($_SESSION['login'])){
	header('Location: /');
}

//Used to add devices to account
if(isset($_POST['devicename'])){
	$id = $_SESSION['login'];
	$name = $_POST['devicename'];
	$token = sha1(uniqid(rand(), true));
	
	
	$stmt = $mysqli->prepare( "INSERT INTO devices(`user_id`,`token`,`device_name`)".
      "VALUES (?,?,?)");

    $stmt->bind_param('iss',$id,$token , $name);
	
	 if($stmt->execute()){
		$stmt->store_result();
		$stmt->close();
	 } else {
		$_SESSION['msg'] = "Failed to add device";
	 }
	
}

//Display message
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

 <center> <h3>Welcome <?php echo (isset($_SESSION['user']) ?  $_SESSION['user'] : " User" ); ?> </h3> </center>
 <center> <h4>To begin, please click on <b> Add Device </b> to add a Rasberry Pi to your account </h4> </center>
 <br>
<center>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deviceModal">
Add Device
</button>
<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#uploadModal">
Upload Picture
</button>
</center>




<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          X
        </button>
      </div>
      <div class="modal-body">
			
		  <div class ="content">
			<form action ="upload.php" method="Post" enctype ="multipart/form-data">
			  <center><h4> Select image to upload: </h4></center>
			  <center><input type="File" name="file" id="file"></center>
			  <br>
			  <center><button type="submit" class="btn btn-primary">Upload</button></center>
			</form>
		  </div>
					
      </div>
    </div>
  </div>
</div>

<!-- Device Modal -->
<div class="modal fade" id="deviceModal" tabindex="-1" role="dialog" aria-labelledby="deviceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          X
        </button>
      </div>
      <div class="modal-body">
					
		  
			<form action ="" method="Post" class="form-horizontal" enctype ="multipart/form-data">
				<div class="form-group">
					<label for="devicename" class="col-md-4 control-label">Device Name</label>
					<div class="col-md-6">
						<input id="devicename" type="devicename" class="form-control" name="devicename" value="" required autofocus>   
					</div>
                 </div>
			 
				<div class="form-group">
				<center><button type="submit" class="btn btn-primary">Generate Token</button></center>
				</div>
			</form>
		  
					
      </div>
    </div>
  </div>
</div>


<?php
	//Find any devices added and if there are at least one, dynamically generate a Bootstrap table
	$id = $_SESSION['login'];
	
	$findDevices = $mysqli->prepare("SELECT token,device_name FROM devices WHERE user_id = ?");
	$findDevices->bind_param("i", $id);
	$findDevices->execute();
	$findDevices->store_result();
	$findDevices->bind_result($token, $device_name);
	
	if($findDevices->num_rows > 0){
	
?>
<br>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="text-align: center">Device Name</th>
						<th style="text-align: center">Token</th>
					</tr>
				</thead>
						<tbody>

							<?php
								while ($findDevices->fetch()) {
							?>
								  <tr>
									<td style="text-align: center"><?php echo $device_name;?></td>
									<td style="text-align: center"><?php echo $token;?></td>
								  </tr>
							<?php		
								}
							?>
						</tbody>
		</table>
	
		</div>
	</div>
</div>
	
<?php	
	
	}	
?>




<?php include ('footer.php');?>
