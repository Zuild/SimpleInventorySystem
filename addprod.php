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

$errors = [];
$result = false; // Initialize result as false by default
$p_name = '';
$p_price = '';
$p_stocks = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_stocks = $_POST['p_stocks'];
    $p_image = '';

    if (!$p_name) {
        $errors[] = 'Product Name is Required!';
    }
    if (!$p_price) {
        $errors[] = 'Product Price is Required!';
    }
    if (!$p_stocks) {
        $errors[] = 'Product Stocks is Required!';
    }
    if (!isset($_FILES['p_image']) || $_FILES['p_image']['error'] != UPLOAD_ERR_OK) {
        $errors[] = 'Product Image is Required!';
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $image = $_FILES['p_image'] ?? null;
        $imagePath = '';
        if ($image) {
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath), 0777, true);
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        if (empty($errors)) {
            $statement = $conn->prepare("INSERT INTO product (p_image, p_name, p_price, p_stocks) VALUES(:p_image, :p_name, :p_price, :p_stocks)");

            $statement->bindValue(':p_image', $imagePath);
            $statement->bindValue(':p_name', $p_name);
            $statement->bindValue(':p_price', $p_price);
            $statement->bindValue(':p_stocks', $p_stocks);

            $result = $statement->execute();

            if ($result) {
                // Log the addition to the audit table
                try {
                    $auditQuery = "INSERT INTO product_audit_log (p_id, user_id, user_name, action, change_date) VALUES (LAST_INSERT_ID(), :user_id, :user_name, 'add', NOW())";
                    $auditStmt = $conn->prepare($auditQuery);
                    $auditStmt->bindParam(':user_id', $currentUserId);
                    $auditStmt->bindParam(':user_name', $currentUserName);
                    $auditStmt->execute();
                } catch (Exception $e) {
                    // Handle audit logging error
                    error_log("Failed to log addition action: " . $e->getMessage());
                }

                $p_name = '';
                $p_price = '';
                $p_stocks = '';
                $p_image = '';
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hardware Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<h1 class="p-4">Products Table</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($result): ?>
    <div class="alert alert-success">
        <?php echo 'Added Successfully!'; ?>
    </div>
<?php endif; ?>


<form method="POST" class="form-inline" action="addprod.php" enctype="multipart/form-data">
    <div class="form-group ms-3 mt-3">
        <label>Product Image</label>
        <input type="file" class="form-control" name="p_image">
    </div>
    <div class="form-group ms-3 mt-3">
        <label>Product Name</label>
        <input type="text" class="form-control" name="p_name" value="<?php echo $p_name ?>">
    </div>
    <div class="form-group ms-3 mt-3">
        <label>Price</label>
        <input type="text" class="form-control" name="p_price" value="<?php echo $p_price ?>">
    </div>
    <div class="form-group ms-3 mt-3">
        <label for="name">Quantity</label>
        <input type="number" class="form-control" name="p_stocks" min="1" value="<?php echo $p_stocks ?>">
    </div>
    <button type="submit" class="btn btn-primary ms-3 mt-3" name="add">Add item</button>
    <a href="index.php" type="button" class="btn btn-danger ms-3 mt-3">Go Back</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
