<?php
include_once("connect.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $repeat_password = $_POST['repeat_password'];
    $userid = $_SESSION['userid'];
    $errors = [];

    if (empty($new_username)) {
        $errors[] = "emptyInputs";
    }
    if (!empty($new_password) && $new_password !== $repeat_password) {
        $errors[] = "PasswordMismatch";
    }
    if (!empty($new_password) && strlen($new_password) < 6) {
        $errors[] = "PasswordLengthTooShort";
    }

    // Check if the new username already exists in the database
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_name = :new_username AND user_id != :userid");
    $stmt->bindParam(':new_username', $new_username);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "UsernameExists";
    }

    if (empty($errors)) {
        try {
            $conn->beginTransaction();
            $updateQuery = "UPDATE users SET user_name = :new_username";
            if (!empty($new_password)) {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $updateQuery .= ", user_password = :new_password";
            }
            $updateQuery .= " WHERE user_id = :userid";

            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':new_username', $new_username);
            if (!empty($new_password)) {
                $stmt->bindParam(':new_password', $new_password_hashed);
            }
            $stmt->bindParam(':userid', $userid);

            if ($stmt->execute()) {
                $conn->commit();
                $_SESSION['username'] = $new_username;
                header("Location: ../edituser.php?success=true");
                exit();
            } else {
                $conn->rollBack();
                header("Location: ../edituser.php?error=updateFailed");
                exit();
            }
        } catch (Exception $e) {
            $conn->rollBack();
            header("Location: ../edituser.php?error=updateFailed");
            exit();
        }
    } else {
        header("Location: ../edituser.php?error=" . implode(",", $errors));
        exit();
    }
} else {
    header("Location: ../edituser.php");
    exit();
}
?>
