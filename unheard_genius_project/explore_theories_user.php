<link rel="stylesheet" type="text/css" href="style.css">

<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

$sql = "SELECT t.theory_id, t.title, t.description, t.created_at, u.username, t.user_id, c.category_name
        FROM theory t
        JOIN users u ON t.user_id = u.user_id
        JOIN category c ON t.category_id = c.category_id
        WHERE u.is_admin = 0"; 

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>User Theories</h2>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<div class='theory'>";
            echo "<h3>" . ($row['title']) . "</h3>";
            echo "<p><strong>By:</strong> " . ($row['username']) . "</p>";
            echo "<p><strong>Description:</strong> " . ($row['description']) . "</p>";
            echo "<p><strong>Category:</strong> " . ($row['category_name']) . "</p>";
            echo "<p><strong>Posted on:</strong> " . $row['created_at'] . "</p>";

            if ($_SESSION['user_id'] == $row['user_id']) {
                echo "<div class='theory-buttons'>";
                echo "<a class='edit-btn' href='edit_theory.php?id=" . $row['theory_id'] . "'>Edit</a>";
                echo "<a class='delete-btn' href='delete_theory.php?id=" . $row['theory_id'] . "'>Delete</a>";

                echo "</div>";
            }

            echo "</div>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>No theories found for regular users.</p>";
    }
} else {
    echo "<p>Error: Could not execute query. " . mysqli_error($link) . "</p>";
}

mysqli_close($link);
?>

<html>
    <body>
        <br><br>
    <center><a href="dashboard.php">← Back to Dashboard</a></center>
    </body>
</html>