<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
	ob_start();
	$user_ID = $_SESSION["account_id"];
	$username = $_SESSION["account_name"];
	// Include config file
	require_once "config.php";

?>


<?php 
	// Define variables and initialize with empty values
	$title_ID = $watch_list_ID = "";
	$title_ID_err = $user_ID_err = "" ;
	$SQL_err="";
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validate Project number
		$title_ID = trim($_POST["title_ID"]);
		if(empty($title_ID)){
			$title_ID_err = "Please select a valid title.";
		} 
    
		// // Validate Hours
		// $Hours = trim($_POST[""]);
		// if(empty($Hours)){
		// 	$Hours_err = "Please enter hours (1-40)";     
		// }
	
		// Validate the SSN
		if(empty($user_ID)){
			$user_ID_err = "No user ID.";     
		}

		// 🔍 Get watchlist ID
		$watchlist_sql = "SELECT watch_list_ID FROM WatchList WHERE user_ID = ?";
		if ($stmt_watchlist = mysqli_prepare($link, $watchlist_sql)) {
			mysqli_stmt_bind_param($stmt_watchlist, "i", $user_ID);
			if (mysqli_stmt_execute($stmt_watchlist)) {
				mysqli_stmt_bind_result($stmt_watchlist, $fetched_watchlist_ID);
				if (mysqli_stmt_fetch($stmt_watchlist)) {
					$watch_list_ID = $fetched_watchlist_ID;
				} else {
					$user_ID_err = "Watchlist not found for user.";
				}
			} else {
				$SQL_err = "Error retrieving watchlist.";
			}
			mysqli_stmt_close($stmt_watchlist);
		}


    // Check input errors before inserting in database
		if(empty($title_ID_err) && empty($user_ID_err) ){
        // Prepare an insert statement
			$sql = "INSERT INTO Lists (watch_list_ID, title_ID) VALUES (?, ?)";


        	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'ii', $param_watch_list_ID, $param_title_ID);
            
				// Set parameters
				$param_watch_list_ID = $watch_list_ID;
				$param_title_ID = $title_ID;
        
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
    <title>Company DB</title>
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
                        <h3>Add a Title to your WatchList </h3>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT T.title_name, T.title_ID 
			FROM Title T";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);	
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($user_ID_err)) ? 'has-error' : ''; ?>">
            <label>Title Name</label>
			<select name="title_ID" class="form-control">
			<?php

				for($i=0; $i<$num_row; $i++) {
					$names=mysqli_fetch_row($result);
					echo "<option value='$names[1]' >".$names[0]."  ".$names[1]."</option>";;
				}
			?>
			</select>	
            <span class="help-block"><?php echo $title_ID_err;?></span>
		</div>
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Title">	
			&nbsp;
			<a href="viewWatchlist.php" class="btn btn-primary">View Watchlist</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	