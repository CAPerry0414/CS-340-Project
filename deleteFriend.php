<?php
require_once('config.php');
session_start();

$user_ID = $_SESSION['user_ID'];
$message = "";

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['friend_ID'])) 
{
    $friend_ID = $_POST['friend_ID'];

    $stmt = $db->prepare("DELETE FROM Befriends WHERE user_ID = ? AND friend_ID = ?");
    if ($stmt->execute([$user_ID, $friend_ID])) 
    {
        $message = "Friend removed successfully.";
    } 
    else 
    {
        $message = "Error removing friend.";
    }
}

// Fetch current friends
$friends_stmt = $db->prepare("
    SELECT u.user_ID, u.username 
    FROM Befriends b 
    JOIN User u ON b.friend_ID = u.user_ID 
    WHERE b.user_ID = ?");
$friends_stmt->execute([$user_ID]);
$friends = $friends_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Friend - Rotten Potatoes</title>
</head>
<body>
    <h1>Remove a Friend</h1>

    <?php if (!empty($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>

    <?php if (count($friends) === 0): ?>
        <p>You have no friends to remove.</p>
    <?php else: ?>
        <form method="POST" action="deleteFriend.php">
            <label for="friend_ID">Select a friend to remove:</label>
            <select name="friend_ID" required>
                <?php foreach ($friends as $friend): ?>
                    <option value="<?= htmlspecialchars($friend['user_ID']) ?>">
                        <?= htmlspecialchars($friend['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Remove Friend</button>
        </form>
    <?php endif; ?>

    <p><a href="home.php">Back to Home</a></p>
</body>
</html>
