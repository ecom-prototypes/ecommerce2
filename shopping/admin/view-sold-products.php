
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

}
if(isset($_POST['updateordernote'])) {
	$oid= intval($_POST['oid']);
	$note = mysqli_real_escape_string($con, $_POST['ordernote']);
	mysqli_query($con,"update orders set orderNote='$note' where id=$oid");
	$_SESSION['msg']="Order Note updated !!";
}

if (isset($_GET['revert'])) {

    $oid = $_GET['oid'];

    // Prepare the select statement to avoid SQL injection
    $stmt = $con->prepare("SELECT productid, quantity FROM orders WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = $result->fetch_assoc();

    if (!$res) {
        $_SESSION['delmsg'] = "Order not found!";
        return;
    }

    $productid = $res['productid'];
    $orderQuantity = $res['quantity'];

    $con->begin_transaction();

    try {

        // Revert order
        $sql1 = $con->prepare("UPDATE orders SET orderReverted = TRUE, quantity = 0 WHERE id = ?");
        $sql1->bind_param("i", $oid);
        if (!$sql1->execute()) {
            throw new Exception("Error executing order update: " . $con->error);
        }

        // Return quantity to product stock
        $sql2 = $con->prepare("UPDATE products SET productQuantity = productQuantity + ? WHERE id = ?");
        $sql2->bind_param("ii", $orderQuantity, $productid);
        if (!$sql2->execute()) {
            throw new Exception("Error updating product quantity: " . $con->error);
        }

        $con->commit();
        $_SESSION['delmsg'] = "Order Reverted !!";

    } catch (Exception $e) {

        $con->rollback();
        $_SESSION['delmsg'] = "Order Revert failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Pending Orders</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<script language="javascript" type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

</script>
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
								<h3>Sold Details #<?php echo intval($_GET['oid']);?></h3>
							</div>
							<div class="module-body table">


									<br />

					<div class="table-responsive">		
			<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display table-responsive" >
	
<tbody>
<?php 
$orderid=intval($_GET['oid']);
$query=mysqli_query($con,"SELECT products.id as pid, products.*,orders.id as oid, orders.quantity as orderQuantity, orders.*,category.categoryName,subcategory.subcategory
    FROM products 
    JOIN category ON category.id=products.category 
    JOIN subcategory ON subcategory.id=products.subCategory
    JOIN orders ON orders.productid=products.id where orders.id='$orderid'");

while($row=mysqli_fetch_array($query))
{
?>										
										<tr>
											
											<th>Sold Date</th>
											<td><?php echo htmlentities($row['orderDate']);?></td>
											<th>Sold Status</th>
											<td><?php if($row['orderReverted']==0){
														echo "Sold";
													} else {
														echo "Reverted";
													} ?>
											</td>
										</tr>

										<tr>
											<th>Sold Note</th>
											<td class="editable" data-id="<?php echo $row['oid']; ?>">
												<span class="text-value"><?php echo htmlentities($row['orderNote']); ?></span>
												<textarea type="text" class="edit-input" style="display:none;"></textarea>
												<i class="icon-edit ordernote-edit" style="cursor:pointer; margin-left:5px;"></i>
												<i class="icon-save ordernote-save" style="cursor:pointer; display:none; margin-left:5px;"></i>
											</td>
										</tr>
										<tr>
											<th>Product Name</th>
											<td><?php echo htmlentities($row['productName']);?></td>
											<th>Product Company</th>
											<td><?php echo htmlentities($row['productCompany']);?></td>
										</tr>
										<tr>
											<th>Product Description</th>
											<td><?php echo $row['productDescription'];?></td>
											<th>Sold Quantity</th>
											<td><?php echo htmlentities($row['orderQuantity']);?></td>
										</tr>
										<tr>
											<th>Sold Price</th>
											<td>₹<?php echo htmlentities($row['soldPrice']);?></td>
												<th>Product Price</th>
											<td>₹<?php echo htmlentities($row['productPrice']);?></td>
										</tr>
                                        <tr>
											<th>Category</th>
											<td><?php echo htmlentities($row['categoryName']);?></td>
												<th>Sub Category</th>
											<td><?php echo htmlentities($row['subcategory']);?></td>
										</tr>
										
                                        <tr>
											
												
											<th>Product Image</th>
											<td><img src="productimages/<?php echo htmlentities($row['pid']."/".$row['productImage1']);?>" width="100"></td>
										</tr>
										
                                        
										

										</tbody>
								</table>
								



			<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped" style="margin-top:1%;" >

	
		
    
      
   
  



                <tr>
                    <td colspan="4">  <?php if ($row['orderReverted'] == 0) { ?>  <a href="view-sold-products.php?oid=<?php echo htmlentities($orderid);?>&revert=true"
											title="Update order"
											class="btn btn-primary"
											onclick="return confirm('Are you sure you want to revert this order?');">
											Revert
											</a> 
										<?php } ?>

                    </td>
                </tr>
            </table>
<?php } ?>
                        
            </div>
            </div>
        </div>						

						
						
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

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
	<script src="js/scripts.js"></script>
</body>