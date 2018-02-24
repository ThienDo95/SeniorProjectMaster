<?php

include ('includes.php');

include ('header.php');

?>

 <center> <h3>Welcome User!</h3> </center>
 <center> <h4>To begin, please click on <b> Add User </b> to add a Rasberry Pi to your account </h4> </center>

<center><a href="addDevice" class="btn btn-default">Add User</a></center>

<body>
    <div class ="container">
      <div class ="content">
        <center> <h1> Upload Your Picture </h1> </center>
        <form action ="" method="Post" enctype ="multipart/form-data">
          <center><h4> Select image to upload: </h4></center>
          <center><input type ="File" name"file" id= "file"><input type ="submit" value ="Upload" name="submit"></center>
        </form>
      </div>
    </div>
</body>

</html>
