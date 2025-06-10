<?php
session_start();
ob_start();
$account_id = $_SESSION["account_id"];
$account_name = $_SESSION["account_name"];
require_once "config.php";
?>

<?php
$title_ID = "";
$rating = "";
$title_err = $rating_err = $account_err = $SQL_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Validate title
    $title_ID = trim($_POST["title_ID"]);
    if (empty($title_ID)) 
	{
        $title_err = "Please select a title.";
    }

    // Validate rating
    $rating = trim($_POST["rating"]);
    if (empty($rating) || !is_numeric($rating) || $rating < 0 || $rating > 5) 
	{
        $rating_err = "Please enter a rating between 0 and 5.";
    }

    if (empty($account_id)) 
	{
        $account_err = "Missing user ID.";
    }

    if (empty($title_err) && empty($rating_err) && empty($account_err)) 
	{
        $sql = "INSERT INTO Rates (user_ID, title_ID, rating) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) 
		{
            mysqli_stmt_bind_param($stmt, 'iii', $param_user, $param_title, $param_rating);

            $param_user = $account_id;
            $param_title = $title_ID;
            $param_rating = $rating;

            if (!mysqli_stmt_execute($stmt)) 
			{
                $SQL_err = "Error: " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Rating</title>
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
            <div class="col-md-10">
                <div class="page-header">
                    <h3>Add a Rating</h3>
                    <h4><?php echo htmlspecialchars($account_name); ?> (User ID: <?php echo htmlspecialchars($account_id); ?>)</h4>
                </div>

<?php
echo $SQL_err;
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Could not connect: " . mysqli_connect_error());
}
$sql = "SELECT title_ID, title_name FROM Title ORDER BY title_name ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Failed to fetch titles: " . mysqli_error($conn));
}
$num_rows = mysqli_num_rows($result);
?>

<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
    <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
        <label>Select a Movie or TV Show</label>
        <select name="title_ID" class="form-control">
            <?php
            while ($row = mysqli_fetch_row($result)) {
                echo "<option value='{$row[0]}'>" . htmlspecialchars($row[1]) . "</option>";
            }
            ?>
        </select>
        <span class="help-block"><?php echo $title_err; ?></span>
    </div>

    <div class="form-group <?php echo (!empty($rating_err)) ? 'has-error' : ''; ?>">
        <label>Rating (0 - 5)</label>
        <input type="number" name="rating" class="form-control" min="0" max="5" value="">
        <span class="help-block"><?php echo $rating_err; ?></span>
    </div>

    <div>
        <input type="submit" class="btn btn-success pull-left" value="Add Rating">
        &nbsp;
        <a href="viewRatings.php" class="btn btn-primary">View My Ratings</a>
    </div>
</form>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
