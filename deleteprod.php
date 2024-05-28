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

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $conn->prepare("DELETE FROM product WHERE p_id = :id");
$statement->bindValue(':id', $id);
$statement->execute();

// Log the deletion to the audit table
try {
    $auditQuery = "INSERT INTO product_audit_log (p_id, user_id, user_name, action, change_date) VALUES (:p_id, :user_id, :user_name, 'delete', NOW())";
    $auditStmt = $conn->prepare($auditQuery);
    $auditStmt->bindParam(':p_id', $id);
    $auditStmt->bindParam(':user_id', $currentUserId);
    $auditStmt->bindParam(':user_name', $currentUserName);
    $auditStmt->execute();
} catch (Exception $e) {
    // Handle audit logging error
    error_log("Failed to log deletion action: " . $e->getMessage());
}

header("Location: index.php");
?>
