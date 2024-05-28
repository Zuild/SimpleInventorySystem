<?php
include_once("includes/connect.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['userid']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Set the current user context in MySQL session variables
$currentUserId = $_SESSION['userid'];
$currentUserName = $_SESSION['username'];

$conn->exec("SET @current_user_id = {$currentUserId}");
$conn->exec("SET @current_user_name = '{$currentUserName}'");

$id = $_GET['id'] ?? null;
$errors = [];
$result = false;
$product = null;

if ($id) {
    // Fetch the product to be edited
    $statement = $conn->prepare('SELECT * FROM product WHERE p_id = :id');
    $statement->bindValue(':id', $id);
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id) {
        // Update existing product
        $p_name = $_POST['p_name'];
        $p_price = $_POST['p_price'];
        $p_stocks = $_POST['p_stocks'];
        $p_image = $product['p_image'] ?? '';

        if (!$p_name) {
            $errors[] = 'Product Name is Required!';
        }
        if (!$p_price) {
            $errors[] = 'Product Price is Required!';
        }
        if (!$p_stocks) {
            $errors[] = 'Product Stocks is Required!';
        }

        // Check if a new image is uploaded
        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['p_image'];
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath), 0777, true);
            move_uploaded_file($image['tmp_name'], $imagePath);

            // Remove old image if exists
            if ($product['p_image']) {
                unlink($product['p_image']);
                // Remove the directory if it becomes empty
                $dir = dirname($product['p_image']);
                if (is_dir($dir) && count(scandir($dir)) == 2) { // Check if directory is empty
                    rmdir($dir);
                }
            }

            $p_image = $imagePath;
        } elseif (isset($_POST['remove_image'])) {
            // Remove image and directory
            if ($product['p_image']) {
                unlink($product['p_image']);
                $dir = dirname($product['p_image']);
                if (is_dir($dir) && count(scandir($dir)) == 2) { // Check if directory is empty
                    rmdir($dir);
                }
            }
            $p_image = '';
        }

        if (empty($errors)) {
            $statement = $conn->prepare("UPDATE product SET p_image = :p_image, p_name = :p_name, p_price = :p_price, p_stocks = :p_stocks WHERE p_id = :id");

            $statement->bindValue(':p_image', $p_image);
            $statement->bindValue(':p_name', $p_name);
            $statement->bindValue(':p_price', $p_price);
            $statement->bindValue(':p_stocks', $p_stocks);
            $statement->bindValue(':id', $id);

            $result = $statement->execute();

            if ($result) {
                // Log the update to the audit table
                try {
                    $auditQuery = "INSERT INTO product_audit_log (p_id, user_id, user_name, action, change_date) VALUES (:p_id, :user_id, :user_name, 'update', NOW())";
                    $auditStmt = $conn->prepare($auditQuery);
                    $auditStmt->bindParam(':p_id', $id);
                    $auditStmt->bindParam(':user_id', $currentUserId);
                    $auditStmt->bindParam(':user_name', $currentUserName);
                    $auditStmt->execute();
                } catch (Exception $e) {
                    // Handle audit logging error
                }
            }
        }
    } else {
        // Add new product
        $p_name = $_POST['p_name'];
        $p_price = $_POST['p_price'];
        $p_stocks = $_POST['p_stocks'];
        $p_image = '';

        $errors = [];

        if (!$p_name) {
            $errors[] = 'Product Name is Required!';
        }
        if (!$p_price) {
            $errors[] = 'Product Price is Required!';
        }
        if (!$p_stocks) {
            $errors[] = 'Product Stocks is Required!';
        }

        // Check if a new image is uploaded
        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['p_image'];
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath), 0777, true);
            move_uploaded_file($image['tmp_name'], $imagePath);
            $p_image = $imagePath;
        }

        if (empty($errors)) {
            $statement = $conn->prepare("INSERT INTO product (p_image, p_name, p_price, p_stocks) VALUES (:p_image, :p_name, :p_price, :p_stocks)");

            $statement->bindValue(':p_image', $p_image);
            $statement->bindValue(':p_name', $p_name);
            $statement->bindValue(':p_price', $p_price);
            $statement->bindValue(':p_stocks', $p_stocks);

            $result = $statement->execute();

            if ($result) {
                // Log the addition to the audit table
                try {
                    $newProductId = $conn->lastInsertId();
                    $auditQuery = "INSERT INTO product_audit_log (p_id, user_id, user_name, action, change_date) VALUES (:p_id, :user_id, :user_name, 'add', NOW())";
                    $auditStmt = $conn->prepare($auditQuery);
                    $auditStmt->bindParam(':p_id', $newProductId);
                    $auditStmt->bindParam(':user_id', $currentUserId);
                    $auditStmt->bindParam(':user_name', $currentUserName);
                    $auditStmt->execute();
                } catch (Exception $e) {
                    // Handle audit logging error
                }
            }
        }
    }
}

function randomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}
?>