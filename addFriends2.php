<?php
	session_start();
	ob_start();
	$user_ID = $_SESSION["account_id"];
	$username = $_SESSION["account_name"];
	// Include config file
	require_once "config.php";

?>


<?php 
	// Define variables and initialize with empty values
	$friend_ID = "";
    $friend_username = "";
	$friend_ID_err = $friend_username_err = "" ;
	$SQL_err="";
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validate Project number
		$friend_ID = trim($_POST["friend_ID"]);
		if(empty($friend_ID)){
			$friend_ID_err = "Please select a valid ID.";
		} 
    
		// // Validate Hours
		// $Hours = trim($_POST["Hours"]);
		// if(empty($Hours)){
		// 	$Hours_err = "Please enter hours (1-40)";     
		// }
	
		// // Validate the SSN
		// if(empty($Ssn)){
		// 	$Ssn_err = "No SSN.";     
		// }


    // Check input errors before inserting in database
		if(empty($friend_ID_err) ){
        // Prepare an insert statement
			$sql = "INSERT INTO Befriends (user_ID, friend_ID) VALUES (?, ?)";


        	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'ii', $param_user_ID, $param_friend_ID);
            
				// Set parameters
				$param_user_ID = $user_ID;
				$param_friend_ID = $friend_ID;
        
            // Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
               // Records created successfully. Redirect to landing page
				//    header("location: index.php");
				//	exit();
				} else{
					// Error
					echo "Error";
					//exit();
					$SQL_err = mysqli_error($link);
				}
			}
         
        // Close statement
        mysqli_stmt_close($stmt);
		
	}   
		// Close connection
		mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Friend</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="page-header">
                        <h3>Add a Friend </h3>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT user_ID FROM User WHERE user_ID != $user_ID";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);	
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($Ssn_err)) ? 'has-error' : ''; ?>">
            <label>Friend_ID</label>
			<!-- <select name="friend_ID" class="form-control"> -->
			<?php

				// for($i=0; $i<$num_row; $i++) {
				// 	$friend_IDs=mysqli_fetch_row($result);
				// 	echo "<option value='$friend_IDs[0]' >".$friend_IDs[0]."  ".$friend_IDs[1]."</option>";
				// }
			?>
            <input class="form-input" type="text" name="friend_ID" placeholder="Friend ID" id="friend_ID" required>
			<!-- </select>	 -->
            <span class="help-block"><?php echo $Pno_err;?></span>
		</div>
		<!-- <div class="form-group <?php echo (!empty($Hours_err)) ? 'has-error' : ''; ?>">
			<label>Hours </label>
			<input type="number" name="Hours" class="form-control" min="1" max="80" value="">
			<span class="help-block"><?php echo $Hours_err;?></span>
		</div> -->
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Friend">	
			&nbsp;
			<a href="viewFriends.php" class="btn btn-primary">List Friends</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	