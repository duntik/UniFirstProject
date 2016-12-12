<?php
/* 
Registration
*/

include('/db/simpleDB.php');
include('/layouts/HTMLcomponents.php');
//error_reporting(0);
//Ulogin(1);
DidTheUserAdmin(1);


if(isset($_POST["name"]))  
{  
	$name = mysqli_real_escape_string($CONNECT, $_POST["name"]);  
	$message = mysqli_real_escape_string($CONNECT, $_POST["message"]);  

	$sql = "INSERT INTO clubgenre(code, category) VALUES ('".$name."', '".$message."')";  
	if(mysqli_query($CONNECT, $sql))  
	{  
		echo "Message Saved";  
	}  
}  





// Navbar
top("Welcome to Portlethen");

//Other page content

?>


<div class="container">
	<div class="section">

		<div class="row">
			<div class="col s12 center">
				<h3><i class="mdi-content-send brown-text"></i></h3>
				<h4>Admin Panel - See the not approved clubs</h4>
				<p class="center-align light">There you can approve the clubs.</p>
				<div class="collection">
					<a href="adminpanel" class="collection-item">Show Admin Panel</a>

					<h5>Not approved clubs</h5>

					<?php

if ($CONNECT->connect_error) {
     die("Connection failed: " . $CONNECT->connect_error);
} 

$sql = "SELECT club_id, name, approved FROM clubs";
$result = $CONNECT->query($sql);

if ($result->num_rows > 0) {
     echo '<table class="centered highlight">
        <thead>
          <tr>
              <th>Club ID</th>
              <th>Name</th>
              <th>More information</th>
          </tr>
        </thead>
        ';
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if ($row["approved"] == 0){
        echo "<tbody>
          <tr>
            <td>".$row["club_id"]."</td><td>".$row["name"]."</td><td> ".'<input type="button" id="button_id" value="More about the Club" onClick="MoreAboutTheClub(' . $row["club_id"] .');" class="waves-effect waves-light btn" ></a>' ."</td></tr></p>";
     }}
} else {
     echo "0 results";
}

$CONNECT->close();
?>  
				</div>
			</div>
		</div>

	</div>
</div>

<script>  
function MoreAboutTheClub(id)
  {
      jQuery.ajax({
       type: "POST",
       url: "adminusers",
       data: 'id='+id,
       cache: false,
       success: function(data, response)
       {
         //url for Arnis!!!
       }
     });
 }
</script>  