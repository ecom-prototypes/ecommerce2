<?php
session_start();

include('includes/config.php');
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
$scid = isset($_GET['scid']) ? intval($_GET['scid']): 0;
if(isset($_GET['action']) && $_GET['action']=="add"){
	$id=intval($_GET['id']);
	if(isset($_SESSION['cart'][$id])){
		$_SESSION['cart'][$id]['quantity']++;
	}else{
		$sql_p="SELECT * FROM products WHERE id={$id}";
		$query_p=mysqli_query($con,$sql_p);
		if(mysqli_num_rows($query_p)!=0){
			$row_p=mysqli_fetch_array($query_p);
			$_SESSION['cart'][$row_p['id']]=array("quantity" => 1, "price" => $row_p['productPrice']);
				echo "<script>alert('Product has been added to the cart')</script>";
		echo "<script type='text/javascript'> document.location ='my-cart.php'; </script>";
		}else{
			$message="Product ID is invalid";
		}
	}
	
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta -->
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="description" content="">
		<meta name="author" content="">
	    <meta name="keywords" content="MediaCenter, Template, eCommerce">
	    <meta name="robots" content="all">

	    <title>Product Category</title>

	    <!-- Bootstrap Core CSS -->
	    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
	    
	    <!-- Customizable CSS -->
	    <link rel="stylesheet" href="assets/css/main.css">
	    <link rel="stylesheet" href="assets/css/red.css">
	    <link rel="stylesheet" href="assets/css/owl.carousel.css">
		<link rel="stylesheet" href="assets/css/owl.transitions.css">
		<!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
		<link href="assets/css/lightbox.css" rel="stylesheet">
		<link rel="stylesheet" href="assets/css/animate.min.css">
		<link rel="stylesheet" href="assets/css/rateit.css">
		<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

		<!-- Demo Purpose Only. Should be removed in production -->
		<link rel="stylesheet" href="assets/css/config.css">

		<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
		<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
		<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
		<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
		<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
		<!-- Demo Purpose Only. Should be removed in production : END -->

		
		<!-- Icons/Glyphs -->
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <!-- Fonts --> 
		<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="assets/images/favicon.ico">

		<!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

	</head>
    <body class="cnt-home">
	
<header class="header-style-1">
<div class="contain">
	<!-- ============================================== TOP MENU ============================================== -->
<?php include('includes/main-header.php');?>
	<!-- ============================================== NAVBAR ============================================== -->
<?php include('includes/menu-bar.php');?>
</div>
</div>
<!-- ============================================== TOP MENU : END ============================================== -->

<!-- ============================================== NAVBAR : END ============================================== -->

</header>
<!-- ============================================== HEADER : END ============================================== -->



<div class="body-content outer-top-xs">
	<div class='container'>
		
		<div class='row outer-bottom-sm'>
			<div class='col-md-3 sidebar d-none d-md-none d-lg-block'>
	            <!-- ================================== TOP NAVIGATION ================================== -->

<!-- ================================== TOP NAVIGATION : END ================================== -->	            
 					<div class="sidebar-module-container">
	            		<h3 class="section-title">shop by</h3>
	            		<div class="sidebar-filter">
		            	<!-- ============================================== SIDEBAR CATEGORY ============================================== -->
						<div class="side-menu animate-dropdown outer-bottom-xs">	
						
						<div class="head"><i class="icon fa fa-align-justify fa-fw"></i>Category</div>
	
	         <?php 
			 if (isset($_GET['scid'])){
				$sql=mysqli_query($con,"select id,categoryName  from category where id=$cid");
			 } else {

				$sql=mysqli_query($con,"select id,categoryName  from category");
			 }
while($row=mysqli_fetch_array($sql))
{
    ?>
    <nav class="yamm megamenu-horizontal" role="navigation">
	    	<ul class="nav">
	            <li class="dropdown menu-item">
	                <a href="sub-category.php?cid=<?php echo $row['id'];?>&scid=0"  class="dropdown-toggle collapsed">
	                   <?php echo $row['categoryName'];?>
	                </a>
  				</li>  
			</ul>
</nav>
	    <?php } ?>
	
</div><!-- /.sidebar-widget -->

    
<!-- ============================================== COLOR: END ============================================== -->

	            	</div><!-- /.sidebar-filter -->
	            </div><!-- /.sidebar-module-container -->
            </div><!-- /.sidebar -->
			<div class='col-md-9'>
					<!-- ========================================== SECTION – HERO ========================================= -->



				<div class="search-result-container">
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane active " id="grid-container">
							<div class="category-product  inner-top-vs">
								<div class="row">									
			<?php
$ret=mysqli_query($con,"select * from products where category='$cid'");
$num=mysqli_num_rows($ret);
if($num>0)
{
while ($row=mysqli_fetch_array($ret)) 
{?>							
		<div class="item col-xs-6 col-sm-4 col-md-4 wow fadeInUp">
                <div class="products">
                  <div class="product text-center">
                    
                    <!-- Product Image -->
                    <div class="product-image">
                      <a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>">
                        <img
                          src="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>"
                          alt="<?php echo htmlentities($row['productName']); ?>"
                          class="img-fluid product-img"
                        >
                      </a>
                    </div>

                    <!-- Product Info -->
                    <div class="product-info">
                      <h3 class="name">
                        <a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>">
                          <?php echo htmlentities($row['productName']); ?>
                        </a>
                      </h3>

                      <div class="product-price">
                        <span class="price">₹<?php echo htmlentities($row['productPrice']); ?></span>
                        <span class="price-before-discount">₹<?php echo htmlentities($row['productPriceBeforeDiscount']); ?></span>
                      </div>
                    </div>

                    <!-- Add to Cart / Out of Stock -->
                    <div class="cart mt-2">
                      <?php if ($row['productAvailability'] != 'In Stock') { ?>
                        <div class="text-danger mt-2">Out of Stock</div>
                      <?php }  ?>
                        
                    </div>

                  </div><!-- /.product -->
                </div><!-- /.products -->
              </div><!-- /.col -->
	  <?php } } else {?>
	
		<div class="col-sm-6 col-md-4 wow fadeInUp"> <h3>No Product Found</h3>
		</div>
		
<?php } ?>	
		
	
		
		
	
		
	
		
	
		
										</div><!-- /.row -->
							</div><!-- /.category-product -->
						
						</div><!-- /.tab-pane -->
						
				

				</div><!-- /.search-result-container -->

			</div><!-- /.col -->
		</div></div>
		<?php include('includes/brands-slider.php');?>

</div>
</div>
<?php include('includes/footer.php');?>
	<script src="assets/js/jquery-1.11.1.min.js"></script>
	
	
	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	
	<script src="assets/js/echo.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
	<script src="assets/js/bootstrap-slider.min.js"></script>
    <script src="assets/js/jquery.rateit.min.js"></script>
    <script type="text/javascript" src="assets/js/lightbox.min.js"></script>
    <script src="assets/js/bootstrap-select.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
	<script src="assets/js/scripts.js"></script>



	

</body>
</html>