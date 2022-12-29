<?php

session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === 1){
    header("location: laman-dashboard-admin.php");
    exit;
}
 
require_once "config.php";
 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["pass"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["pass"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT a_username, a_password FROM admin WHERE a_username = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    
                    if(mysqli_stmt_fetch($stmt)){
                        if($password === $hashed_password){

                            session_start();
                            
                            $_SESSION["loggedin"] = 1;
                            $_SESSION["username"] = $username;

                            header("location: laman-dashboard-admin.php");
                        } else{
                            $login_err = "Invalid password.";
                        }
                    }
                } else {
                    $login_err = "Invalid username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | PPB</title>
</head>
<body>
    <?php 
        if(!empty($login_err)){
            echo "<script type='text/javascript'>alert('".$login_err."');</script>";
        }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Username</label>
        <input type="text" name="username">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
        <label>Password</label>
        <input type="password" name="pass">
        <input type="submit" name="signin" value="Sign In">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </form>
</body>
</html>