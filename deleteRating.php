<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config.php";

// Store title_ID from GET request
if (isset($_GET["title_ID"]) && !empty(trim($_GET["title_ID"]))) {
    $_SESSION["title_ID"] = $_GET["title_ID"];
    $title_ID = $_GET["title_ID"];
}

// Handle deletion on POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["title_ID"]) && isset($_SESSION["account_id"])) {
        $title_ID = $_SESSION["title_ID"];
        $user_ID = $_SESSION["account_id"];

        $sql = "DELETE FROM Rates WHERE user_ID = ? AND title_ID = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $param_user, $param_title);
            $param_user = $user_ID;
            $param_title = $title_ID;

            if (mysqli_stmt_execute($stmt)) {
                header("location: viewRatings.php");
                exit();
            } else {
                echo "Error deleting the rating.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }
} else {
    // Redirect if no ID passed
    if (empty(trim($_GET["title_ID"]))) {
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Rating</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
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
                    <h1>Delete Rating</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger fade in">
                        <input type="hidden" name="user_ID" value="<?php echo ($_SESSION["account_id"]); ?>"/>
                        <p>Are you sure you want to delete your rating for title ID: 
                            <?php echo htmlspecialchars($_SESSION["title_ID"]); ?>?</p><br>
                        <input type="submit" value="Yes" class="btn btn-danger">
                        <a href="viewRatings.php" class="btn btn-default">No</a>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>
</body>
</html>
