<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

include_once("includes/connect.php");

// Fetch all products initially
$statement = $conn->prepare("SELECT * FROM product");
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Handle search input
$search_term = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = htmlspecialchars($_GET['search']);
    $statement = $conn->prepare("SELECT * FROM product WHERE p_name LIKE :search");
    $search_param = "%$search_term%";
    $statement->bindParam(':search', $search_param);
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hardware Inventory</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script type="text/javascript">
        function confirmDeletion() {
            return confirm("Are you sure you want to delete this item?");
        }

        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("productsTable");
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3]; // index 3 is the Name column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</head>
<body>

<?php
if (isset($_SESSION["userid"])) {
    echo '<div class="container-fluid">
        <div class="d-flex justify-content-end mt-2">
            <a href="edituser.php" class="link-dark link-offset-2 link-underline-opacity-100 link-opacity--hover text-decoration-none ms-2 mt-2">
                <i class="bx bx-user-circle bx-tada bx-flip-horizontal" style="font-size: 24px;"></i>
            </a>
            <a href="includes/logout.inc.php" class="link-danger link-offset-2 link-underline-opacity-100 link-opacity--hover text-decoration-none ms-2 mt-2">
                <i class="bx bx-log-out bx-rotate-180" style="font-size: 24px;"></i>
            </a>
        </div>
    </div>';

} else {
    echo '<div class="container-fluid">
        <div class="d-flex justify-content-end mt-2">
            <a href="signup.php" class="btn btn-secondary ms-2">Sign-up</a>
            <a href="login.php" class="text-decoration-none ms-2 mt-2">Log-in</a>
        </div>
    </div>';
}
?>

<?php
if (isset($_SESSION["username"])) {
    echo '<div class="row justify-content-center">
        <div class="col-auto">
            <h3>Welcome ' . htmlspecialchars($_SESSION["username"]) . '!</h3>
        </div>
    </div>';
}
?>

<h1>Products Table</h1>

<a href="audit.php" type="button" class="btn btn-success mb-2">Logs</a>

<a href="addprod.php" type="button" class="btn btn-primary mb-2">Add Product</a>

  <!-- Search Form -->
  <form action="" method="GET" class="mb-3">
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Search by product name" name="search" id="searchInput" value="<?php echo htmlspecialchars($search_term); ?>" oninput="searchTable()">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
  </form>

  <div class="table-responsive">
      <table class="table table-hover" id="productsTable">
          <thead>
              <tr class="table-primary">
                  <th scope="col">#</th>
                  <th scope="col">Product ID</th>
                  <th scope="col">Image</th>
                  <th scope="col">Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Stocks</th>
                  <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($products as $i => $product): ?>
                  <tr>
                      <th scope="row"><?php echo $i + 1 ?></th>
                      <td><?php echo htmlspecialchars($product['p_id']) ?></td>
                      <td><img src="<?php echo htmlspecialchars($product['p_image']) ?>" class="thumbnail"></td>
                      <td><?php echo htmlspecialchars($product['p_name']) ?></td>
                      <td><?php echo htmlspecialchars($product['p_price']) ?></td>
                      <td><?php echo htmlspecialchars($product['p_stocks']) ?></td>
                      <td>
                          <a href="editprod.php?id=<?php echo htmlspecialchars($product['p_id']) ?>" type="button" class="btn btn-primary btn-sm">Edit</a>
                          <form style="display: inline-block" method="POST" action="deleteprod.php" onsubmit="return confirmDeletion();">
                              <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['p_id']) ?>">
                              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                          </form>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
  </html>
