<?php
session_start();
include('include/config.php');  // your DB connection file

if(strlen($_SESSION['alogin']) == 0){
    header('location:index.php');
    exit;
}

// Fetch summary counts
function getCount($con, $table, $where = "") {
    $sql = "SELECT COUNT(*) AS total FROM $table $where";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row['total'];
}

$totalProducts      = getCount($con, "products");
$totalCategories    = getCount($con, "category");
$totalSubCategories = getCount($con, "subcategory");
$totalOrders        = getCount($con, "orders");
$totalReverted      = getCount($con, "orders", "WHERE orderReverted = 1");

// Total sold quantity
$sql = "SELECT SUM(quantity) AS qty FROM orders WHERE orderReverted = 0";
$res = mysqli_query($con, $sql);
$soldQty = mysqli_fetch_assoc($res)['qty'] ?? 0;

// Low stock products
$lowStockQuery = mysqli_query($con, "SELECT productName, productQuantity FROM products WHERE productQuantity <= 2 ORDER BY productQuantity ASC");

// Latest orders
$latestOrders = mysqli_query($con, "
    SELECT o.id, p.productName, o.quantity, o.soldPrice, o.orderDate, o.orderReverted 
    FROM orders o 
    JOIN products p ON p.id = o.productId
    ORDER BY o.id DESC LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    <title>Dashboard | Admin</title>

    

    <style>
        body { background: #f7f7f7; }
        .card-box { padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,.2); }
        .card-title { font-size:20px; font-weight:600; }
        .value { font-size:30px; font-weight:bold; }
    </style>
</head>
<body>
<?php include('include/header.php');?>

<div class="wrapper">
    <div class="container mt-5">
        <div class="row">
        <?php include('include/sidebar.php');?>

            <div class="span9">
                <h2 class="mb-4">Admin Dashboard</h2>

                <!-- Summary cards -->
                <div class="row rowflex">
                    <div class="col-md-3">
                        <div class="card-box bg-primary text-white">
                            <div class="card-title">Total Products</div>
                            <div class="value"><?= $totalProducts ?></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-box bg-success text-white">
                            <div class="card-title">Categories</div>
                            <div class="value"><?= $totalCategories ?></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-box bg-info text-white">
                            <div class="card-title">Subcategories</div>
                            <div class="value"><?= $totalSubCategories ?></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-box bg-warning text-dark">
                            <div class="card-title">Total Orders</div>
                            <div class="value"><?= $totalOrders ?></div>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="card-box bg-danger text-white">
                            <div class="card-title">Reverted Orders</div>
                            <div class="value"><?= $totalReverted ?></div>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="card-box bg-dark text-white">
                            <div class="card-title">Total Sold Qty</div>
                            <div class="value"><?= $soldQty ?></div>
                        </div>
                    </div>
                </div>
                <br />
                <!-- Low Stock -->
                <div class="mt-5">
                    <h4>Low Stock Products (≤ 2)</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Available Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($lowStockQuery)) { ?>
                                <tr>
                                    <td><?= $row['productName'] ?></td>
                                    <td><?= $row['productQuantity'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br />
                <!-- Latest Orders -->
                <div class="mt-5">
                    <h4>Latest Orders</h4>
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = mysqli_fetch_assoc($latestOrders)) { ?>
                                <tr>
                                    <td><?= $o['id'] ?></td>
                                    <td><?= $o['productName'] ?></td>
                                    <td><?= $o['quantity'] ?></td>
                                    <td>₹<?= $o['soldPrice'] ?></td>
                                    <td><?= $o['orderDate'] ?></td>
                                    <td>
                                        <?php if($o['orderReverted']) { ?>
                                            <span class="badge bg-danger">Reverted</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php');?>

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	
</body>
</html>
