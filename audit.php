<?php
include_once("includes/connect.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

// Initialize variables for date filtering
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Prepare the SQL query based on date filtering
$query = "SELECT * FROM product_audit_log";
$params = [];

if (!empty($start_date) && !empty($end_date)) {
    $query .= " WHERE change_date BETWEEN :start_date AND :end_date";
    $params[':start_date'] = $start_date;
    $params[':end_date'] = $end_date;
}
$query .= " ORDER BY change_date DESC";

$stmt = $conn->prepare($query);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ensure $logs is defined as an array
if ($logs === false) {
    $logs = [];
}
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
</head>
<body>
    <h1>Audit Log</h1>
    <div class="logout-button">
      <a href="index.php" class="link-danger link-offset-2 link-underline-opacity-100 link-opacity--hover text-decoration-none"><i class='bx bx-x-circle bx-flip-horizontal'></i></a>
    </div>

    <form method="get" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-secondary" onclick="window.print();">Print</button>
            </div>
        </div>
    </form>

    <div class="print-container">
        <div class="table-container">
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-tW6rKTA1q5vLw5z/x2+3QC3g/EQsJ1hzk7Fv+5iJ0aow5BwaM8XpMgnSb9hO/L+" crossorigin="anonymous"></script>
</body>
</html>
