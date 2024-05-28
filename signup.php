<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log-in Page</title>
  <link rel="stylesheet" href="css/signup.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
    
<body>
  <div class="wrapper">
    <form action="includes/signup.inc.php" method="POST">

      <h1>Sign-Up</h1>

      <?php

        if(isset($_GET["error"])){
          if($_GET["error"] == "emptyInputs"){
            echo "<p style='color:red; text-align:center;'> Please fill all fields!</p>";
          }else if($_GET["error"] == "InvalidUsername"){
            echo "<p style='color:red; text-align:center;'> Invalid Username Format!</p>";
          }else if($_GET["error"] == "PasswordMismatch"){
            echo "<p style='color:red; text-align:center;'> Password did not match!</p>";
          }else if($_GET["error"] == "UserIsAlreadyExisting"){
            echo "<p style='color:red; text-align:center;'> User already exist!!</p>";
          }else if($_GET["error"] == "none"){
            echo "<p style='color:green; text-align:center;'> Successfully Signed-up!</p>";
          }else if($_GET["error"] == "PasswordLengthTooShort"){
            echo "<p style='color:red; text-align:center;'> Password is too short!Must contain 6 or more characters.</p>";
          }
        }


      ?>
      <div class="input-box">
          <input type="text" placeholder="Username" name="user_name" required>
          <i class='bx bxs-user'></i>
      </div>

      <div class="input-box">
        <input type="password" placeholder="Password" name="user_password">
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Repeat Password" name="repeat_password">
        <i class='bx bxs-lock-alt' ></i>
      </div>

    <button type="submit" class="btn" name="submit">Sign Up</button>

    <div class="register-link">
      <p>Already have an account? <a href="login.php">Log-in</a></p>
    </div>
    </form>
  </div>
  
</body>

</html>
