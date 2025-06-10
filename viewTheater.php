<?php
session_start();
ob_start();

// Optional: pull in session variables for display
$UserID = $_SESSION["user_ID"] ?? "Unknown";
$Username = $_SESSION["username"] ?? "User";

// Include config file
require_once "config.php";

$SQL_err = "";

// Connect using MySQLi for consistency with your example
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    $SQL_err = "Could not connect: " . mysqli_connect_error();
} else {
    $sql = "SELECT theater_name, location FROM Theater ORDER BY theater_name ASC";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $SQL_err = "Query failed: " . mysqli_error($conn);
    } else {
        $num_rows = mysqli_num_rows($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Theaters - Rotten Potatoes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 700px;
            margin: 0 auto;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h3>All Movie Theaters</h3>
                    <h4>Logged in as: <?php echo htmlspecialchars($Username); ?> (ID: <?php echo htmlspecialchars($UserID); ?>)</h4>
                </div>

                <?php if (!empty($SQL_err)): ?>
                    <div class="alert alert-danger"><?php echo $SQL_err; ?></div>
                <?php elseif ($num_rows === 0): ?>
                    <p class="lead">No theaters found in the database.</p>
                <?php else: ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Theater Name</th>
                            <th>Location</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['theater_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <a href="home.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<?php
// Free result and close connection
if (isset($result)) {
    mysqli_free_result($result);
}
if (isset($conn)) {
    mysqli_close($conn);
}
?>
</body>
</html>
