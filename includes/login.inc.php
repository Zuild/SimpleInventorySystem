<?php
if (isset($_POST['submit'])) {
    require 'connect.php';
    require 'functions.inc.php';

    $user_name = $_POST['user_name'];
    $user_password = $_POST['user_password'];

    if (empty($user_name) || empty($user_password)) {
        header("Location: ../login.php?error=emptyInputs");
        exit();
    }

    loginUser($conn, $user_name, $user_password);
} else {
    header("Location: ../login.php");
    exit();
}
?>
