<?php
session_start();
require_once "config.php";

// Use session-stored user info
if (!isset($_SESSION["account_id"]) || !isset($_SESSION["account_name"])) {
    header("location: login.php");
    exit();
}

$user_ID = $_SESSION["account_id"];
$username = $_SESSION["account_name"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Ratings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style>
        .wrapper {
            width: 750px;
            margin: 0 auto;
        }
        .page-header h2 {
            margin-top: 0;
        }
        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script>
        $(document).ready(function () 
        {
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
                    <h2 class="pull-left">Ratings for <?php echo htmlspecialchars($username); ?> (User ID: <?php echo htmlspecialchars($user_ID); ?>)</h2>
                    <a href="createRatings.php" class="btn btn-success pull-right">Add Rating</a>
                </div>
                <?php
                // Connect using MySQLi (to match your template style)
                $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                if (!$conn) 
                {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "
                    SELECT t.title_name, r.rating
                    FROM Rates r
                    JOIN Title t ON r.title_ID = t.title_ID
                    WHERE r.user_ID = ?
                ";

                if ($stmt = mysqli_prepare($conn, $sql)) 
                {
                    mysqli_stmt_bind_param($stmt, "i", $user_ID);
                    if (mysqli_stmt_execute($stmt)) 
                    {
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) 
                        {
                            echo "<table class='table table-bordered table-striped'>";
                            echo "<thead>";
                            echo "<tr><th>Title</th><th>Rating</th></tr>";
                            echo "</thead><tbody>";

                            while ($row = mysqli_fetch_assoc($result)) 
                            {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                                echo "</tr>";
                            }

                            echo "</tbody></table>";
                        } 
                        else 
                        {
                            echo "<p class='lead'><em>No ratings found.</em></p>";
                        }

                        mysqli_free_result($result);
                    } 
                    else 
                    {
                        echo "<p class='text-danger'>Error executing query.</p>";
                    }

                    mysqli_stmt_close($stmt);
                }

                mysqli_close($conn);
                ?>

                <p><a href="home.php" class="btn btn-primary">Back to Home</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>