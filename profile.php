<?php

include ('includes.php');

include ('header.php');

?>

 <center> <h3>Welcome User!</h3> </center>
 <center> <h4>To begin, please click on <b> Add User </b> to add a Rasberry Pi to your account </h4> </center>

<center>
<button type="button" class="btn btn-primary">
Add Device
</button>
<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
Upload Picture
</button>
</center>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          X
        </button>
      </div>
      <div class="modal-body">

		  <div class ="content">
			<form action ="" method="Post" enctype ="multipart/form-data">
			  <center><h4> Select image to upload: </h4></center>
			  <center><input type ="File" name"file" id= "file"><input type ="submit" value ="Upload" name="submit"></center>
			</form>
		  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





</body>
</html>
