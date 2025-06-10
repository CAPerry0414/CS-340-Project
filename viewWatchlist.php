<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Watchlist</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
	   <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Your Watchlist</h2>
                        <a href="addToWatchlist.php" class="btn btn-success pull-right">Add Title</a>
						
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["account_id"]) && !empty(trim($_GET["account_id"]))){
	$_SESSION["account_id"] = $_GET["account_id"];
}
if(isset($_GET["account_name"]) && !empty(trim($_GET["account_name"]))){
	$_SESSION["account_name"] = $_GET["account_name"];
}

if(isset($_SESSION["account_id"]) ){
	
	
    // Prepare a select statement
    $sql = "SELECT T.title_name, T.title_ID, W.watch_list_ID 
            FROM WatchList W 
            LEFT JOIN Lists L ON W.watch_list_ID = L.watch_list_ID
            LEFT JOIN Title T ON L.title_ID = T.title_ID
            WHERE W.user_ID = ? ";

  
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_ID);      
        // Set parameters
       $param_user_ID = $_SESSION["account_id"];
	   $username = $_SESSION["account_name"];

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			echo"<h4> Watchlist for ".$username." &nbsp      SSN =".$param_user_ID."</h4><p>";
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Title</th>";
                            // echo "<th>Sex</th>";
							// echo "<th>Birthdate </th>";
							// echo "<th>Relationship</th>";
							// echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['title_name'] . "</td>";
                        // echo "<td>" . $row['Sex'] . "</td>";
						// echo "<td>" . $row['Bdate']."</td>";
                        // echo "<td>" . $row['Relationship'] . "</td>";
						echo "<td>";
						//   echo "<a href='updateDependent.php?Dname=". $row['Dependent_name'] ."' title='Update Dependent' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                          echo "<a href='deleteFromWatchlist.php?TID=". $row['title_ID'] ."' title='Delete Title' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                        echo "</td>";
						echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "No Titles Listed. ";
			}
//				mysqli_free_result($result);
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }
    }     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>					                 					
	<p><a href="index.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>