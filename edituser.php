<?php
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="css/edituser.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  
  <div class="wrapper">
    <a href="index.php" class="exit-btn"><i class='bx bx-x-circle bx-flip-horizontal'></i></a>
    <form action="includes/edituser.inc.php" method="POST">
      <h1>Edit Profile</h1>
      <?php
      if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
          case "emptyInputs":
            echo "<p style='color:red; text-align:center;'>Please fill all fields!</p>";
            break;
          case "PasswordMismatch":
            echo "<p style='color:red; text-align:center;'>Passwords do not match!</p>";
            break;
          case "PasswordLengthTooShort":
            echo "<p style='color:red; text-align:center;'>Password must be at least 6 characters long!</p>";
            break;
          case "UsernameExists":
            echo "<p style='color:red; text-align:center;'>Username already exists!</p>";
            break;
          case "updateFailed":
            echo "<p style='color:red; text-align:center;'>Error updating profile!</p>";
            break;
        }
      }

      if (isset($_GET["success"])) {
        echo "<p style='color:green; text-align:center;'>Profile updated successfully!</p>";
      }
      ?>
      <div class="input-box">
        <label for="new_username">Username</label>
        <input type="text" placeholder="Username" name="new_username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <label for="new_password">New Password (Leave blank to keep current password)</label>
        <input type="password" name="new_password">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="input-box">
        <label for="repeat_password">Repeat Password</label>
        <input type="password" name="repeat_password">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" class="btn" name="submit">Save</button>
    </form>
  </div>
</body>
</html>
