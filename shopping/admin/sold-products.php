<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
    header('location:index.php');
}
else{
date_default_timezone_set('Asia/Kolkata');
$currentTime = date( 'd-m-Y h:i:s A', time () );

// ---------- PAGINATION SETTINGS ----------
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; 
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;   
$search = isset($_GET['search']) ? mysqli_real_escape_string($con,$_GET['search']) : "";
$offset = ($page - 1) * $limit;

// ---------- SORT SETTINGS ----------
$sort = isset($_GET['sort']) ? $_GET['sort'] : "oid";
$order = isset($_GET['order']) ? $_GET['order'] : "DESC";

$allowedSort = [
    "productName", "soldPrice", "categoryName", "subcategory",
    "orderQuantity", "orderReverted", "orderDate", "oid"
];

$allowedOrder = ["ASC","DESC"];

if(!in_array($sort, $allowedSort)) $sort = "oid";
if(!in_array($order, $allowedOrder)) $order = "DESC";

$nextOrder = ($order == "ASC") ? "DESC" : "ASC";

// ---------- COUNT TOTAL ----------
$countQuery = "
    SELECT COUNT(*) as total 
    FROM orders
    JOIN products ON orders.productid=products.id 
    WHERE products.productName LIKE '%$search%'
";

$countResult = mysqli_query($con,$countQuery);
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

// ---------- FETCH DATA ----------
$query = mysqli_query($con,"
    SELECT products.*, orders.id as oid, orders.quantity as orderQuantity, 
           orders.*, category.categoryName, subcategory.subcategory
    FROM products 
    JOIN category ON category.id = products.category 
    JOIN subcategory ON subcategory.id = products.subCategory
    JOIN orders ON orders.productid = products.id
    WHERE products.productName LIKE '%$search%'
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin | Manage Sold Products</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
</head>
<body>

<?php include('include/header.php'); ?>

<div class="wrapper">
<div class="container">
<div class="row">

<?php include('include/sidebar.php'); ?>

<div class="span9">
<div class="content">

<div class="module">
<div class="module-head">
	<h3>Manage Sold Products</h3>
</div>
<div class="module-body table">

<!-- SEARCH + LIMIT FORM -->
<form method="GET" class="form-inline" style="margin-bottom:15px;">
	<input type="text" name="search" class="input-medium" placeholder="Search Product"
		value="<?php echo $search; ?>">

	<select name="limit" class="input-small">
		<option value="5"  <?php if($limit==5) echo "selected";?>>5</option>
		<option value="10" <?php if($limit==10) echo "selected";?>>10</option>
		<option value="25" <?php if($limit==25) echo "selected";?>>25</option>
		<option value="50" <?php if($limit==50) echo "selected";?>>50</option>
	</select>

	<button type="submit" class="btn btn-primary">Apply</button>
</form>

<table class="table table-bordered table-striped">
<thead>
<tr>
	<th>
		<a href="?sort=id&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			SN. <?php if($sort=="id") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>
		<a href="?sort=productName&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			Product Name <?php if($sort=="productName") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>
		<a href="?sort=soldPrice&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			Sold Price <?php if($sort=="soldPrice") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>
			Category
		
	</th>

	<th>
		
			Subcategory 
		
	</th>

	<th>
		<a href="?sort=orderQuantity&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			Quantity <?php if($sort=="orderQuantity") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>
		<a href="?sort=orderReverted&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			Status <?php if($sort=="orderReverted") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>
		<a href="?sort=orderDate&order=<?php echo $nextOrder; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			Created <?php if($sort=="orderDate") echo ($order=="ASC"?"↑":"↓"); ?>
		</a>
	</th>

	<th>Action</th>
</tr>
</thead>
<tbody>

<?php 
$cnt = $offset + 1;
while($row = mysqli_fetch_array($query)){ ?>
<tr>
	<td><?php echo $cnt++; ?></td>
	<td><?php echo htmlentities($row['productName']); ?></td>
	<td>₹<?php echo htmlentities($row['soldPrice']); ?></td>
	<td><?php echo htmlentities($row['categoryName']); ?></td>
	<td><?php echo htmlentities($row['subcategory']); ?></td>
	<td><?php echo htmlentities($row['orderQuantity']); ?></td>
    <td><?php echo ($row['orderReverted']==0 ? "Sold" : "Reverted"); ?></td>
	<td><?php echo htmlentities($row['orderDate']); ?></td>
	<td><a href="view-sold-products.php?oid=<?php echo $row['oid']; ?>">View</a></td>
</tr>
<?php } ?>

</tbody>
</table>

<!-- PAGINATION -->
<div class="pagination"><ul>

<?php if($page > 1){ ?>
	<li><a href="?page=<?php echo $page-1; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Prev</a></li>
<?php } ?>

<?php for($i=1; $i <= $totalPages; $i++){ ?>
	<li class="<?php if($i==$page) echo 'active'; ?>">
		<a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">
			<?php echo $i; ?>
		</a>
	</li>
<?php } ?>

<?php if($page < $totalPages){ ?>
	<li><a href="?page=<?php echo $page+1; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Next</a></li>
<?php } ?>

</ul></div>

</div>
</div>

</div>
</div>
</div>
</div>

<?php include('include/footer.php'); ?>
<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
<script src="scripts/datatables/jquery.dataTables.js"></script>
<script>
	$(document).ready(function() {
		$('.datatable-1').dataTable();
		$('.dataTables_paginate').addClass("btn-group datatable-pagination");
		$('.dataTables_paginate > a').wrapInner('<span />');
		$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
		$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
	} );
</script>
</body>
<?php } ?>
