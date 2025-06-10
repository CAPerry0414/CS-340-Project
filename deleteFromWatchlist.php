<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
	if(isset($_GET["TID"]) && !empty(trim($_GET["TID"]))){
		$_SESSION["TID"] = $_GET["TID"];
		$TID = $_GET["TID"];
	}

    require_once "config.php";
	// Delete an Dependents's record after confirmation
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_SESSION["TID"]) && !empty($_SESSION["TID"])){ 
			$TID = $_SESSION['TID'];
            $user_ID = $_SESSION['account_id'];
            $watch_list_ID = "";

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

			
			// Prepare a delete statement
			$sql = "DELETE FROM Lists WHERE watch_list_ID = ? AND title_ID = ?";
   
			if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ii", $param_watch_list_ID, $param_title_ID);
 
				// Set parameters
				$param_watch_list_ID = $watch_list_ID;
                $param_title_ID = $TID;
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
		if(empty(trim($_GET["TID"]))){
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
    <title>Delete Title</title>
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
                        <h1>Delete Title</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="title_ID" value="<?php echo ($_SESSION["account_id"]); ?>"/>
                            <p>Are you sure you want to delete the title with the ID: 
							     <?php echo ($_SESSION["TID"]); ?>?</p><br>
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