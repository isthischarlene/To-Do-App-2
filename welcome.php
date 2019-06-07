<?php
   session_start();

   //connect to database
   require_once "connect.php";

   /*$sql = "CREATE TABLE IF NOT EXISTS tasks(
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      task VARCHAR(50),
      duedate VARCHAR(50),
      active VARCHAR(50))";*/

   // create a user_id variable and username variable 
   // this user_id is the current logged in user
   $user_id = $_SESSION["id"];
   $username = $_SESSION["username"];
   $_SESSION["message"] = "";
   
   //query to display list of to do list tasks of the logged in user
   $result = mysqli_query($mysqli, "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY task ASC");

   //adding tasks to the database when the user inputs the task and due date and presses submit
   if(isset($_POST['submit'])){
       $duedate = $_POST['duedate'];
       $task = $_POST['task'];
       if(empty($task)){
           $errors = "You must input a task.";
           echo $errors;
       }else{
           mysqli_query($mysqli, "INSERT INTO tasks (task, duedate, user_id) VALUES ('$task', '$duedate', $user_id)");
           $_SESSION["message"] = "Task added";
           header('location: welcome.php');
       }
   }
   
   //to delete the task when the user presses the delete button
   if(isset($_GET["delete"])){
      $id = $_GET["delete"];
      mysqli_query($mysqli, "DELETE FROM tasks WHERE id=$id");
      header('location: welcome.php');
   }else{
      //echo "Did not delete task.";
   }

   //to edit the task when the user presses the edit button
   if(isset($_POST['edit'])){
      $taskID = $_POST['taskID'];
      $editTask = $_POST['editTask'];
      $editDate = $_POST['editDate'];
      //query to update table in database after user presses "update button
      $sql = "UPDATE tasks SET task='$editTask', duedate='$editDate' WHERE id='$taskID'";

      if (mysqli_query($mysqli, $sql) === TRUE) {
         echo "Record updated successfully <br>";
         header('location: welcome.php');
          } else {
              echo "Error: " . $sql . "<br>" . $db->error;
          }
      }
          

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>To-Do List V6 - Welcome Page</title>
      <link rel="stylesheet" type="text/css" href="css/style2.css">
      <link href="https://fonts.googleapis.com/css?family=Stardos+Stencil" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
   </head>
<body>
   <div class="wrapper">
      <header class="header">To Do List</header>
         <article class="main">
         <!-- Main form for user to add tasks and due date-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

               <?php if (isset($errors)){ ?>
                  <p><?php echo $errors; ?></p>
                  <?php  }?>
                  
                  <label id="label-1">Task:</label>
                  <input type="text" name="task" class="inputBox-1" placeholder="" value="">

                  <label id="label-2">Due Date:</label>
                  <input placeholder=""  class="inputBox-2" type="text" name="duedate" value="" onfocus="(this.type='date')" onblur="(this.type='text')" min="2019-05-21" max="2021-12-31">
   
                  <button type="submit" name="submit" class="button" id="add-task">Add Task</button><br><br> 
            
            <table class="table">
            </form> 

               <thead>
                  <tr>
                     <th class="task">Task ID</th>
                     <th class="task">Task</th>
                     <th class="due">Due Date</th>
                     <th class="delete">Delete</th>
                     <!--<th class="update">Edit</th>-->
                  </tr>
               </thead>

               <tbody>
                  <?php
                  //While loop to display row information (tasks and due date) in database
                  while($row = mysqli_fetch_array($result)){ ?>
                  <tr>
                     <td class="task"><?php echo $row['id'];?></td>
                     <td class="task"><?php echo $row['task'];?></td>
                     <td class="due"><?php echo $row['duedate'];?></td>
                     <td class="delete">
                        <button>
                           <a href="welcome.php?delete=<?php echo $row["id"];?>">
                              <img src="images/red-cross-2.jpg" alt="picture of red X">
                           </a>
                        </button>
                     </td>
                     <td>
                        <a href="welcome.php?edit=<?php echo $row["id"];?>" class="edit">
                        <!--<img src="images/pencil-original.png" alt="picture of pencil">-->
                  </td>

                  </tr>
                  <!--end of while loop-->
                  <?php }; ?>
               </tbody>
            </table>
            
            <div class ="update-block">
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <h3>Update Task</h3>
                     <label id="label-1">Task ID:</label>
                     <input type="text" name="taskID"  class="inputBox-2"  placeholder="Insert Task ID No.">
                     <label id="label-1">Task:</label>
                     <input type="text" name="editTask" class="inputBox-1" placeholder="Insert Changes">
                     <label id="label-1">Date:</label>
                     <input type="date" name="editDate"  class="inputBox-2" type="text" value="" onfocus="(this.type='date')" onblur="(this.type='text')" min="2019-05-21" max="2021-12-31">
                     <button type="submit" name="edit" class="button-2">✔</button><br><br>
               </form>   
            </div>

         </article> 


      <aside class="aside aside-1">
         <img src="images/avatar-3.png" alt="profile picture" class="profile-img">
         <h5> Welcome,<br> <?php echo $username; ?></h5>
         <button class="button button-profile" id="change-pass"><a href="password-change.php">Change Password</a></button><br>
         <button class="button button-profile" id="logout"><a href="logout.php">Logout</a></button>
      </aside>

      <footer class="footer">
      
      </footer>

   </div>
</body>
</html>