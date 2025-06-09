<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
	if(isset($_GET["fUser_ID"]) && !empty(trim($_GET["fUser_ID"]))){
		$_SESSION["fUser_ID"] = $_GET["fUser_ID"];
		$fUser_ID = $_GET["fUser_ID"];
	}

    require_once "config.php";
	// Delete an Dependents's record after confirmation
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_SESSION["fUser_ID"]) && !empty($_SESSION["fUser_ID"])){ 
			$fUser_ID = $_SESSION['fUser_ID'];
            $user_ID = $_SESSION['account_id'];
			
			// Prepare a delete statement
			$sql = "DELETE FROM Befriends WHERE user_ID = ? AND friend_ID = ?";
   
			if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ii", $param_user_ID, $param_friend_ID);
 
				// Set parameters
				$param_user_ID = $user_ID;
                $param_friend_ID = $fUser_ID;
				//echo $Essn;
				//echo $Dname;

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Records deleted successfully. Redirect to landing page
					header("location: index.php");
					exit();
				} else{
					echo "Error deleting the friend";
				}
			}
		}
		// Close statement
		mysqli_stmt_close($stmt);
    
		// Close connection
		mysqli_close($link);
	} else{
		// Check existence of id parameter
		if(empty(trim($_GET["fUser_ID"]))){
			// URL doesn't contain id parameter. Redirect to error page
			header("location: error.php");
			exit();
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Delete Friend</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="user_ID" value="<?php echo ($_SESSION["account_id"]); ?>"/>
                            <p>Are you sure you want to delete your friend with the ID: 
							     <?php echo ($_SESSION["fUser_ID"]); ?>?</p><br>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>