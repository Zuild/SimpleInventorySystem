<?php
include_once("includes/connect.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM product_audit_log ORDER BY change_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Audit Log</title>
  <link rel="stylesheet" href="css/audit.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script type="text/javascript"></script>
</head>
<body>
    <h1>Audit Log</h1>
    <div class="logout-button">
      <a href="index.php" class="link-danger link-offset-2 link-underline-opacity-100 link-opacity--hover text-decoration-none"><i class="bx bx-log-out bx-rotate-180" style="font-size: 24px;"></i></a>
    </div>
    <table class="table table-hover">
      <thead>
        <tr class="table-primary">
          <th>Audit ID</th>
          <th>User ID</th>
          <th>User Name</th>
          <th>Action</th>
          <th>Table</th>
          <th>Column</th>
          <th>Old Value</th>
          <th>New Value</th>
          <th>Change Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $log): ?>
          <tr>
            <td><?php echo htmlspecialchars($log['audit_id']); ?></td>
            <td><?php echo htmlspecialchars($log['user_id']); ?></td>
            <td><?php echo htmlspecialchars($log['user_name']); ?></td>
            <td><?php echo htmlspecialchars($log['action']); ?></td>
            <td><?php echo htmlspecialchars($log['table_name']); ?></td>
            <td><?php echo htmlspecialchars($log['column_name']); ?></td>
            <td><?php echo htmlspecialchars($log['old_value']); ?></td>
            <td><?php echo htmlspecialchars($log['new_value']); ?></td>
            <td><?php echo htmlspecialchars($log['change_date']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</body>
</html>
