<?php
	session_start();
	//$currentpage="View Employees";
    if (!isset($_SESSION['account_loggedin'])) {
        header('Location: index.php');
        exit;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rotten Potatoes</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <?php
        // Include config file
        require_once "config.php";
//		include "header.php";
	?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		     <h2> Rotten Potatoes</h2> 
                       <p> Project should include CRUD operations. In this website you can:
				<ol> 	<li> CREATE new Users, Ratings, Friends and Watchlists </li>
					<li> RETRIEVE Friends, Watchlists, Ratings for each user, Theaters showing movies, and Titles.</li>
                                        <li> UPDATE Ratings</li>
					<li> DELETE Users, Watchlists, Friends, and Ratings. </li>
				</ol>
		       <h2 class="pull-left">Registered Titles</h2>
                        <a href="logout.php" class="btn btn-success pull-right">Logout</a>
                        <a href="viewTheater.php" class="btn btn-success pull-right">View Theaters</a>
                        <a href="viewRatings.php" class="btn btn-success pull-right">View Ratings</a>
                        <?php
                        echo '<a href="viewFriends.php?user_ID=' . $_SESSION['account_id'] . '&username=' . $_SESSION['account_name'] . '" class="btn btn-success pull-right">Friends</a>';
                        echo '<a href="viewWatchlist.php?user_ID=' . $_SESSION['account_id'] . '&username=' . $_SESSION['account_name'] . '" class="btn btn-success pull-right">Your Watchlist</a>';
                        ?>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                                
					
                    // Select Department Stats
					// You will need to Create a DEPT_STATS table
					
                    $sql2 = "SELECT * FROM Title";
                    if($result2 = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result2) > 0){
							echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=10%>ID</th>";
                                        echo "<th width = 15%>Name</th>";
                                        echo "<th width = 10%>Release Date</th>";
                                        echo "<th width = 10%>Genre</th>";
                                        echo "<th width = 45%>Description</th>";
                                        echo "<th width = 10%>Media Type</th>";
	
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['title_ID'] . "</td>";
                                        echo "<td>" . $row['title_name'] . "</td>";
                                        echo "<td>" . $row['release_date'] . "</td>";
                                        echo "<td>" . $row['genre'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['media_type'] . "</td>";
               
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No records were found for Dept Stats.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql2. <br>" . mysqli_error($link);
                    }
					
                    // Close connection
                    mysqli_close($link);
                    ?>

</body>
</html>
