<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR );


/* Change for your username and password for phpMyAdmin*/
define('DB_SERVER', 'classmysql.engr.oregonstate.edu');
define('DB_USERNAME', 'cs340_perrycod');
define('DB_PASSWORD', '7317');
define('DB_NAME', 'cs340_perrycod');
    
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Include config file
// require_once "config.php";
// if (!$con) {
//     die("Database connection failed");
// }
 
// Now we check if the data from the login form was submitted, isset() will check if the data exists
if (!isset($_POST['username'], $_POST['password'])) {
    // Could not get the data that should have been sent
    exit('Please fill both the username and password fields!');
}
// Prepare our SQL, which will prevent SQL injection
if ($stmt = $link->prepare('SELECT user_ID, password FROM User WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database
    $stmt->store_result();
    
    // Check if account exists with the input username
    if ($stmt->num_rows > 0) {
        // Account exists, so bind the results to variables
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Note: remember to use password_hash in your registration file to store the hashed passwords
        if ($_POST['password'] === $password) {
            // Password is correct! User has logged in!
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id();
            // Declare session variables (they basically act like cookies but the data is remembered on the server)
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_name'] = $_POST['username'];
            $_SESSION['account_id'] = $id;
            // Output success message
            echo 'Welcome back, ' . htmlspecialchars($_SESSION['account_name'], ENT_QUOTES) . '!';
            header("location: index.php");
            exit;
        } else {
            // Incorrect password
            echo 'Incorrect password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username!';
    }

    // Close the prepared statement
    $stmt->close();
}
?>
