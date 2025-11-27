
<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
date_default_timezone_set('Asia/Kolkata');// change according timezone
$currentTime = date( 'd-m-Y h:i:s A', time () );

if(isset($_GET['del']))
		  {
		          mysqli_query($con,"delete from products where id = '".$_GET['id']."'");
                  $_SESSION['delmsg']="Product deleted !!";
		  }

// ---------- PAGINATION SETTINGS ----------
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // items per page
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;   // current page
$search = isset($_GET['search']) ? mysqli_real_escape_string($con,$_GET['search']) : "";

$offset = ($page - 1) * $limit;

// Count total products
$countQuery = "SELECT COUNT(*) as total FROM products 
               JOIN category ON category.id=products.category 
               JOIN subcategory ON subcategory.id=products.subCategory
               WHERE products.productName LIKE '%$search%'";

$countResult = mysqli_query($con,$countQuery);
$totalData = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($totalData / $limit);

// Fetch products with LIMIT + OFFSET + SEARCH
$query = mysqli_query($con,"
    SELECT products.*,category.categoryName,subcategory.subcategory 
    FROM products 
    JOIN category ON category.id=products.category 
    JOIN subcategory ON subcategory.id=products.subCategory
    WHERE products.productName LIKE '%$search%'
    ORDER BY products.id DESC
    LIMIT $limit OFFSET $offset
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Manage Products</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
</head>
<body>
<?php include('include/header.php');?>

<div class="wrapper">
<div class="container">
<div class="row">
<?php include('include/sidebar.php');?>

<div class="span9">
<div class="content">

<div class="module">
<div class="module-head">
	<h3>Manage Products</h3>
</div>
<div class="module-body table">

<?php if(isset($_GET['del'])){ ?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>Deleted!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?> 
		<?php echo htmlentities($_SESSION['delmsg']=""); ?>
	</div>
<?php } ?>

<!-- SEARCH + LIMIT FORM -->
<form method="GET" class="form-inline mb-2" style="margin-bottom:15px;">
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
	<th>#</th>
	<th>Product Name</th>
	<th>Category</th>
	<th>Subcategory</th>
	<th>Company</th>
	<th>Created</th>
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
	<td><?php echo htmlentities($row['categoryName']); ?></td>
	<td><?php echo htmlentities($row['subcategory']); ?></td>
	<td><?php echo htmlentities($row['productCompany']); ?></td>
	<td><?php echo htmlentities($row['postingDate']); ?></td>
	<td>
		<a href="manage-products.php?id=<?php echo $row['id']; ?>&del=delete"
		onClick="return confirm('Are you sure?')">Revert</a>
	</td>
</tr>
<?php } ?>

</tbody>
</table>

<!-- PAGINATION -->
<div class="pagination">
<ul>

<?php if($page > 1){ ?>
	<li><a href="?page=<?php echo $page-1; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">Prev</a></li>
<?php } ?>

<?php for($i=1; $i <= $totalPages; $i++){ ?>
	<li class="<?php if($i==$page) echo 'active'; ?>">
		<a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">
			<?php echo $i; ?>
		</a>
	</li>
<?php } ?>

<?php if($page < $totalPages){ ?>
	<li><a href="?page=<?php echo $page+1; ?>&limit=<?php echo $limit; ?>&search=<?php echo $search; ?>">Next</a></li>
<?php } ?>

</ul>
</div>

</div>
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