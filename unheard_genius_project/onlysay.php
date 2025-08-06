<?php
session_start();
require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT user_id, username, password, is_admin FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $is_admin);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["is_admin"] = $is_admin;
                            // // Set cookies if "Remember Me" is checked
                            //     if (!empty($_POST["remember"])) {
                            //         setcookie("username", $username, time() + (5), "/"); 
                            //         setcookie("password", $password, time() + (5), "/"); 
                            //     } else {
                            //         // Clear cookies if checkbox is not checked
                            //         setcookie("username", "", time() - 3600, "/");
                            //         setcookie("password", "", time() - 3600, "/");
                            //     }

                            header("location: dashboard.php");
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>

<form action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Login</h2>

    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : $username; ?>">
    <span><?php echo $username_err; ?></span>
    <br>

    <label for="password">Password:</label>
    <input type="password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
    <span><?php echo $password_err; ?></span>
    <br>

    <input type="checkbox" name="remember" <?php if (isset($_COOKIE["username"])) echo "checked"; ?>> Remember Me
    <br>

    <input type="submit" value="Login">
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</form>


</body>
</html>