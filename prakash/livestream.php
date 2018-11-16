<?php
$servername = "localhost";
$username = "root";

$password = "";
 
$db = "tcs_connect";

// Create connection
 
$conn = mysqli_connect($servername, $username, $password,$db);
 

// Check connection
 
if (!$conn) {
 
   die("Connection failed: " . mysqli_connect_error());
 
}
 
 $query = "select post_content from tcs_posts where id = 666";
 $exe = mysqli_query($conn,$query);
 if($exe){
	 $data = array();
	$row = mysqli_fetch_assoc($exe);
	$date =  explode( '"' , $row['post_content']);
	
	$sub = explode( '?' , $date[1]);
	//print_r($sub[0]);
 
 }


 
		
		if($_SERVER['REQUEST_METHOD']=='POST')
           {	
           		if($_POST['url'] != ''){


               //$update = "update tcs_posts set post_content ='<iframe src=\"'".$_POST['url']."'\?&vq=720p&rel=0&showinfo=0\" width=\"360\" height=\"330\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe>' where id = 667";

			   	$newlink =  "<iframe src=\"".$_POST['url']."?&vq=720p&rel=0&showinfo=0\" width=\"360\" height=\"330\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\">"."</iframe>";
			   	//echo $newlink;
			   	$link = mysqli_real_escape_string($conn,$newlink);

			   	$directpc = "<iframe width=\"760\" height=\"425\" src=\"".$_POST['url']."?&vq=720p&rel=0&showinfo=0\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";

			   	
			   	$linkpc = mysqli_real_escape_string($conn,$directpc);

			   	$update = "update tcs_posts set post_content ='".$link."' where id = 666";
			   	$updatepc = "update tcs_posts set post_content ='".$linkpc."' where id = 525";
			   	//echo "update tcs_posts set post_content ='".$newlink."'' where id = 667";
				$up = mysqli_query($conn,$update);
				$up2 = mysqli_query($conn,$updatepc);


				// echo '<script type="text/javascript"> 
				
				// alert("Link Updated");
				
				// </script>';

				//header("Refresh:0");

				}
				else{
					
				}

           } 
	
	 /*
	 <iframe width="760" height="425" src="https://www.youtube.com/embed/l9sgtAWbXyg?&vq=720p&rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

	 <iframe src="https://www.youtube.com/embed/l9sgtAWbXyg?&vq=720p&rel=0&showinfo=0" width="360" height="330" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
	 */

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>WP UPDATE</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
		<div class="container">
		<h1>Wordpress Live Stream Update...</h1>
		<form method="post" accept-charset="utf-8" action = "livestream.php">
			<label for="OLD LINK"form-group" style="font-size: 25px;background-color: white">OLD LINK :</label>
			
		<input for="data" class="form-control" style="font-size: 25px;background-color: white" value="<?php echo $sub[0];?>"><br><br><br>
		<label for="NEW LINK"form-group" style="font-size: 25px;background-color: white" >NEW LINK :</label>
		<input type="text"  required class="form-control"name="url" placeholder="" style="font-size: 25px;background-color: white"><br>
		<button type="submit" name="update" class="btn btn-success">Save</button>
		<div class="form-group" style="display: none" id="meg">
        <div class="col-sm-10 col-sm-offset-2">
              <div class='alert alert-success'>
              	<strong>Success!</strong> Link Updated!
              </div>
              <script type="text/javascript" >
              		
              </script>
        </div>
    	</div>
		</form>
		<script type="text/javascript">
			
  

		</script>
		  
		
		

          
        
		
	</body>
</html>