<?php
require_once('config.php');
session_start();

$stmt = $db->prepare("SELECT theater_name, location FROM Theater ORDER BY theater_name ASC");
$stmt->execute();
$theaters = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetches all theaters
?>

<!DOCTYPE html>
<html>
<head>
    <title>Theaters</title>
</head>
<body>
    <h1>Movie Theaters</h1>

    <?php if (count($theaters) === 0): ?>
        <p style="text-align:center;">No theaters found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Theater Name</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($theaters as $theater): ?>
                    <tr>
                        <td><?= htmlspecialchars($theater['theater_name']) ?></td>
                        <td><?= htmlspecialchars($theater['location']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="home.php">Back to Home</a>
</body>
</html>
