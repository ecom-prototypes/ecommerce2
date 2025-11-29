
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

$query = mysqli_query($con,"select productName from products where id = '".$_GET['id']."' LIMIT 0,1");
$res = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {

    $productid = $_GET['id'];
    $sellQty = $_POST['orderQuantity']; // quantity you want to sell
    $sellNote= $_POST['orderNote'];

    // 1. Fetch current product stock
    $stmt = $con->prepare("SELECT productQuantity FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $productid);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        $_SESSION['delmsg'] = "Product not found!";
        return;
    }

    $currentStock = $product['productQuantity'];

    // Block selling if stock is insufficient
    if ($sellQty > $currentStock) {
        $_SESSION['delmsg'] = "Not enough stock!";
        return;
    }

    $con->begin_transaction();

    try {

        // 2. Reduce product stock
        $sql1 = $con->prepare("UPDATE products SET productQuantity = productQuantity - ? WHERE id = ?");
        $sql1->bind_param("ii", $sellQty, $productid);
        if (!$sql1->execute()) {
            throw new Exception("Error updating product stock: " . $con->error);
        }

        // 3. Insert new order entry
        $sql2 = $con->prepare("
            INSERT INTO orders (productid, quantity, orderNote) 
            VALUES (?, ?, ?)
        ");

        $sql2->bind_param("ii", $productid, $sellQty,$sellNote);
        if (!$sql2->execute()) {
            throw new Exception("Error creating order: " . $con->error);
        }

        $con->commit();
        $_SESSION['msg'] = "Product Sold Successfully!";

    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['delmsg'] = "Sale Failed: " . $e->getMessage();
    }
}

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
                <h3><?php echo $res['productName'] ?></h3>
            </div>
            <div class="module-body mx-2">
                <?php if(isset($_POST['submit'])) {?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Well done!</strong>	<?php echo htmlentities($_SESSION['msg']);?><?php echo htmlentities($_SESSION['msg']="");?>
                    </div>
                <?php } ?>
                <?php if($_SESSION['delmsg']!=""){ ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Error!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?> 
                    </div>
                <?php } ?>


                <form method="POST" class="align-items-center mx-2">

                    <div class="col-auto">
                        <input type="number" name="orderQuantity" class="form-control" 
                            placeholder="Qty" min="1" required>
                    </div>

                    <div class="col-auto">
                        <textarea type="text" name="orderNote" class="form-control" 
                            placeholder="Order Note"></textarea>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>

                </form>


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