<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $theory_id = $_GET['id'];

    if (!filter_var($theory_id, FILTER_VALIDATE_INT)) {
        echo "Invalid theory ID.";
        exit;
    }

    $sql = "SELECT user_id FROM theory WHERE theory_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $theory_id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $owner_user_id);
                mysqli_stmt_fetch($stmt);

                if ($_SESSION['user_id'] == $owner_user_id) {
                    $delete_sql = "DELETE FROM theory WHERE theory_id = ?";
                    if ($delete_stmt = mysqli_prepare($link, $delete_sql)) {
                        mysqli_stmt_bind_param($delete_stmt, "i", $theory_id);

                        if (mysqli_stmt_execute($delete_stmt)) {
                            header('Location: explore_theories_user.php');
                            exit;
                        } else {
                            echo "Error: Could not delete the theory.";
                        }
                    }
                } else {
                    echo "You are not authorized to delete this theory.";
                }
            } else {
                echo "No such theory found.";
            }
        } else {
            echo "Error: Could not execute query.";
        }
    }
} else {
    echo "No theory ID provided.";
}

mysqli_close($link);
?>