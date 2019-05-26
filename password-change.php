<?php
    //initialize session
    session_start();

    //check if user is logged in. If not, redirect them to login page
    if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: login.php");
        //if you reach here the output messgae and terminate the script
        exit;
    }

    //include connection.php page
    require_once "connect.php";

    //define variables and initialize with empty values
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";

    //processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //validate new password
        if (empty(trim($_POST["new_password"]))){
            $new_password_err = "Please enter your new password.";
        } elseif (strlen(trim($_POST["new_password"])) < 8) {
            $new_password_err = "Password must have atleast 8 chracters.";
        } else {
            $new_password = trim($_POST["password"]);
        }

        //validate confirm password
        if(empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match. Please re-enter password";
            }
        }

        //check input errors before upating the database
        if(empty($new_password_err) && empty($confirm_password_err)) {
            //prepare and update statement
            $sql = "UPDATE users SET password = ? WHERE id = ?";

            if($stmt = $mysqli->prepare($sql)) {
                //bind variables to the prepared statement as parameters
                $stmt->bind_param("si", $param_password,$param_id);

                //set parameters
                $param_passowrd = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];

                //attempt to execute the prepared statement
                if($stmt->execute()) {
                    //password updated successfully. Destroy the session. Redirect to login page
                    header("location: welcome.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            //close statement
            $stmt->close();
        }
        //close connection
        $mysqli->close();
    }

?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>To Do App V2 - Password Reset</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">
    </head>
    <body>

        <div class="container" id="container">
            <div class="form-container sign-in-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Reset Password</h2>
                    <p>Please fill in the form to reset your password</p>

                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <input type="password" name="new_password" value="<?php echo $new_password; ?>" placeholder="Enter your password" required>
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <input type="password" name="confirm_password" placeholder="Confirm new password" required> 
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <button type="submit" name="submit" id="button">Reset Password</button>
                    <button><a href="welcome.php">Go back to main page</a></button>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <img src="" alt="image for app">
                </div>
            </div>
            </div>
    </body>
</html>