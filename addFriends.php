<?php
require_once('config.php');
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $user_ID = $_SESSION['user_ID'];
    $friend_username = trim($_POST['friend_username']);

    if ($friend_username === $_SESSION['username']) 
    { //error check
        $message = "You cannot add yourself as a friend.";
    } 
    else 
    {
        $stmt = $db->prepare("SELECT user_ID FROM User WHERE username = ?");
        $stmt->execute([$friend_username]);
        $friend = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$friend) 
        {
            $message = "User not found.";
        } 
        else 
        {
            $friend_ID = $friend['user_ID'];

            $check = $db->prepare("SELECT * FROM Befriends WHERE user_ID = ? AND friend_ID = ?"); //checks if already friends
            $check->execute([$user_ID, $friend_ID]);

            if ($check->rowCount() > 0) 
            {
                $message = "You are already friends with this user.";
            } 
            else 
            {
                $insert = $db->prepare("INSERT INTO Befriends (user_ID, friend_ID) VALUES (?, ?)"); //uses insert for friend
                if ($insert->execute([$user_ID, $friend_ID])) 
                {
                    $message = "Friend added successfully!";
                } 
                else 
                {
                    $message = "Failed to add friend.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Friend - Rotten Potatoes</title>
</head>
<body>
    <h1>Add a Friend</h1>
    <!-- <h2>Delete a Friend</h2>
    <a href="addFriend.php" class="btn btn-success pull-right">Friends</a> -->
    <form method="POST" action="addFriend.php">
        <label for="friend_username">Friend's Username:</label>
        <input type="text" name="friend_username" required>
        <button type="submit">Add Friend</button>
    </form>
    <p><?php echo htmlspecialchars($message); ?></p>
    <p><a href="home.php">Back to Home</a></p>
</body>
</html>
