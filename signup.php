<?php
// Include config file
require_once "connect.php";

 /*$sql = "CREATE TABLE IF NOT EXISTS tasks(
      id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(150),
      password VARCHAR (255))";*/
 

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>To Do App V6 - Sign Up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">
    </head>
    <body>

        <div class="container" id="container">
            <div class="form-container sign-in-container">

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Sign Up</h2>
                    <p>Start getting organized today. <br>Sign up for an account.</p>

                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <input type="text" name="username" class="input-field" value="<?php echo $username; ?>" placeholder="Type in your username" required> 
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>  
                    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <input type="password" name="password" class="input-field" value="<?php echo $password; ?>" placeholder="Enter your password" required>
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <input type="password" name="confirm_password" class="input-field" value="<?php echo $confirm_password; ?>" placeholder="Re-enter your password" required> 
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <button type="submit" value="Sign Up" id="button">SIGN UP</button>
                    <p>Already have an account? Click <a href="index.php"><strong> here</strong></a> to go to login page.</p>
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