<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config.php";

if (!isset($_SESSION["account_id"]) || !isset($_SESSION["account_name"])) 
{
    header("location: login.php");
    exit();
}

$user_ID = $_SESSION["account_id"];
$username = $_SESSION["account_name"];

if (!isset($_GET["title_ID"]) || empty(trim($_GET["title_ID"]))) 
{
    die("Missing title_ID in URL.");
}
$title_ID = intval($_GET["title_ID"]);

$rating = "";
$rating_err = "";

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $rating = trim($_POST["rating"]);

    if (empty($rating) || !is_numeric($rating) || $rating < 0 || $rating > 5) 
    {
        $rating_err = "Please enter a rating between 0 and 5.";
    }

    if (empty($rating_err)) 
    {
        $sql = "UPDATE Rates SET rating = ? WHERE user_ID = ? AND title_ID = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iii", $rating, $user_ID, $title_ID);
            if (mysqli_stmt_execute($stmt)) 
            {
                header("location: viewRatings.php");
                exit();
            } 
            else 
            {
                die("Error updating rating: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
} 
else 
{
    $sql = "SELECT rating FROM Rates WHERE user_ID = ? AND title_ID = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) 
    {
        mysqli_stmt_bind_param($stmt, "ii", $user_ID, $title_ID);
        if (mysqli_stmt_execute($stmt)) 
        {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) 
            {
                $rating = $row["rating"];
            } 
            else 
            {
                die("No rating found.");
            }
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Rating</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
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
                    <h2>Update Rating</h2>
                    <h4><?php echo htmlspecialchars($username); ?> — Title ID: <?php echo htmlspecialchars($title_ID); ?></h4>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?title_ID=" . $title_ID); ?>" method="post">
                    <div class="form-group <?php echo (!empty($rating_err)) ? 'has-error' : ''; ?>">
                        <label>New Rating (0 - 5):</label>
                        <input type="number" name="rating" class="form-control" min="0" max="100"
                               value="<?php echo htmlspecialchars($rating); ?>">
                        <span class="help-block"><?php echo $rating_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Update">
                    <a href="viewRatings.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
