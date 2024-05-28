<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log-in Page</title>
  <link rel="stylesheet" href="css/signup.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper">
    <form action="includes/login.inc.php" method="POST">
      <h1>Log-in</h1>
      <?php
        if(isset($_GET["error"])){
          if($_GET["error"] == "emptyInputs"){
            echo "<p style='color:red; text-align:center;'> Please fill all fields!</p>";
          }else if($_GET["error"] == "wronglog"){
            echo "<p style='color:red; text-align:center;'> Invalid Login Details!</p>";
          }
        }
      ?>
      <div class="input-box">
        <input type="text" placeholder="Username" name="user_name" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Password" name="user_password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" class="btn" name="submit">Log-in</button>
      <div class="register-link">
        <p>Don't have an account? <a href="signup.php">Sign-Up</a></p>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
