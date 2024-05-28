<?php

if(isset($_POST["submit"])){
    
    $user_name = $_POST["user_name"];
    $user_password = $_POST["user_password"];
    $repeat_password = $_POST["repeat_password"];

    require_once 'connect.php';
    require_once 'functions.inc.php';

    if(emptyInput($user_name, $user_password, $repeat_password) !== false){
        header("Location: ../signup.php?error=emptyInputs");
        exit();
    }

    if(invalidUsername($user_name) !== false ){
        header("Location: ../signup.php?error=InvalidUsername");
        exit();
    }

    if(passMatch($user_password, $repeat_password) !== false){
        header("Location: ../signup.php?error=PasswordMismatch");
        exit();
    }

    if(strlen($user_password) < 6){
        header("Location: ../signup.php?error=PasswordLengthTooShort");
        exit();
    }

    if(userExist($conn, $user_name) !== false ){
        header("Location: ../signup.php?error=UserIsAlreadyExisting");
        exit();
    }

    createUser($conn, $user_name, $user_password);

}else{
    header("Location: ../signup.php");
    exit();
}
