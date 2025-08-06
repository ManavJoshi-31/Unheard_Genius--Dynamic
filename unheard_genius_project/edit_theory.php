<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Check if ID is set
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo "Invalid theory ID.";
    exit;
}

$theory_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch the theory to ensure ownership
$sql = "SELECT t.title, t.description, t.category_id, c.category_name
        FROM theory t
        JOIN category c ON t.category_id = c.category_id
        WHERE t.theory_id = ? AND t.user_id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $theory_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $description = $row['description'];
        $category_name = $row['category_name'];
    } else {
        echo "Theory not found or you don't have permission to edit.";
        exit;
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = trim($_POST["title"]);
    $new_description = trim($_POST["description"]);

    $update_sql = "UPDATE theory SET title = ?, description = ? WHERE theory_id = ? AND user_id = ?";
    if ($stmt = mysqli_prepare($link, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "ssii", $new_title, $new_description, $theory_id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            header("location: explore_theories_user.php");
            exit;
        } else {
            echo "Error updating theory.";
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Theory</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #333;
    margin-top: 30px;
}

form {
    max-width: 600px;
    background-color: #ffffff;
    padding: 30px;
    margin: 30px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #444;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px 15px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    box-sizing: border-box;
    background-color: #fdfdfd;
}

input[disabled] {
    background-color: #f0f0f0;
    color: #777;
}

textarea {
    resize: vertical;
    min-height: 120px;
}

input[type="submit"] {
    display: block;
    width: 100%;
    margin-top: 25px;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
        </style>
</head>
<body>
    <h2>Edit Your Theory</h2>
    <form method="post">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo ($title); ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="5" required><?php echo ($description); ?></textarea><br><br>

        <label>Category:</label><br>
        <input type="text" value="<?php echo ($category_name); ?>" disabled><br><br>

        <input type="submit" value="Update Theory">
    </form>
</body>
</html>