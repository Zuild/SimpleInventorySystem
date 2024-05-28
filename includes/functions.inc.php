<?php

function emptyInput($user_name, $user_password, $repeat_password){
    $result = false;
    if(empty($user_name)  || empty($user_password) || empty($repeat_password)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function invalidUsername($user_name){
    $result = false;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $user_name)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function passMatch($user_password, $repeat_password){
    $result = false;
    if($user_password !== $repeat_password){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function createUser($conn, $user_name, $user_password) {
    $stmt = $conn->prepare("INSERT INTO users (user_name, user_password) VALUES (:user_name, :user_password)");

    $hashedPassword = password_hash($user_password, PASSWORD_DEFAULT);

    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':user_password', $hashedPassword); // Use the hashed password here

    $stmt->execute();
    header("Location: ../signup.php?error=none");
    exit();
}

function userExist($conn, $user_name) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :user_name");
    $stmt->bindParam(':user_name', $user_name);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$data) {
        return false;
    } else {
        return true;
    }
}

function emptyInputLogin($user_name, $user_password){
    $result = false;
    if(empty($user_name)  || empty($user_password)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function loginUser($conn, $user_name, $user_password){
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :user_name");
    $stmt->bindParam(':user_name', $user_name);
    $stmt->execute();
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$userDetails){
        header("Location: ../login.php?error=wronglog");
        exit();
    }

    $hashedPassword = $userDetails["user_password"];
    $checkPassword = password_verify($user_password, $hashedPassword);

    if($checkPassword === false){
        header("Location: ../login.php?error=wronglog");
        exit();
    } else {
        session_start();
        $_SESSION["userid"] = $userDetails["user_id"];
        $_SESSION["username"] = $userDetails["user_name"];

        header("Location: ../index.php");
        exit();
    }
}

?>
