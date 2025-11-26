<?php 
session_start();
error_reporting(0);
include('includes/config.php');
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
$pid=intval($_GET['pid']);

if(isset($_POST['submit']))
{
	$qty=$_POST['quality'];
	$price=$_POST['price'];
	$value=$_POST['value'];
	$name=$_POST['name'];
	$summary=$_POST['summary'];
	$review=$_POST['review'];
	mysqli_query($con,"insert into productreviews(productId,quality,price,value,name,summary,review) values('$pid','$qty','$price','$value','$name','$summary','$review')");
}


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="description" content="">
		<meta name="author" content="">
	    <meta name="keywords" content="MediaCenter, Template, eCommerce">
	    <meta name="robots" content="all">
	    <title>Product Details</title>
	    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
	    <link rel="stylesheet" href="assets/css/main.css">
	    <link rel="stylesheet" href="assets/css/red.css">
	    <link rel="stylesheet" href="assets/css/owl.carousel.css">
		<link rel="stylesheet" href="assets/css/owl.transitions.css">
		<link href="assets/css/lightbox.css" rel="stylesheet">
		<link rel="stylesheet" href="assets/css/animate.min.css">
		<link rel="stylesheet" href="assets/css/rateit.css">
		<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
		<link rel="stylesheet" href="assets/css/config.css">

		<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
		<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
		<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
		<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
		<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <!-- Fonts --> 
		<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
		<link rel="shortcut icon" href="assets/images/favicon.ico">
	</head>
    <body class="cnt-home">
	
<header class="header-style-1">
<div class="contain">
	<!-- ============================================== TOP MENU ============================================== -->
<?php include('includes/top-header.php');?>
<!-- ============================================== TOP MENU : END ============================================== -->
<?php include('includes/main-header.php');?>
	<!-- ============================================== NAVBAR ============================================== -->
<?php include('includes/menu-bar.php');?>
<!-- ============================================== NAVBAR : END ============================================== -->
</div>
</header>

<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container">
		<div class="breadcrumb-inner">
<?php
$ret=mysqli_query($con,"select category.categoryName as catname,subcategory.subcategory as subcatname,products.productName as pname from products join category on category.id=products.category join subcategory on subcategory.id=products.subcategory where products.id='$pid'");
while ($rw=mysqli_fetch_array($ret)) {

?>


			<ul class="list-inline list-unstyled">
				<li><a href="index.php">Home</a></li>
				<li><?php echo htmlentities($rw['catname']);?></a></li>
				<li><?php echo htmlentities($rw['subcatname']);?></li>
				<li class='active'><?php echo htmlentities($rw['pname']);?></li>
			</ul>
			<?php }?>
		</div><!-- /.breadcrumb-inner -->
	</div><!-- /.container -->
</div><!-- /.breadcrumb -->
<div class="body-content outer-top-xs">
	<div class='container'>
		<div class='row single-product outer-bottom-sm '>
			<div class='col-md-3 sidebar d-none d-lg-block'>
				<div class="sidebar-module-container">


					<!-- ==============================================CATEGORY============================================== -->
<div class="sidebar-widget outer-bottom-xs wow fadeInUp">
	<h3 class="section-title">Category</h3>
	<div class="sidebar-widget-body m-t-10">
		<div class="accordion">

		            <?php $sql=mysqli_query($con,"select id,categoryName  from category");
while($row=mysqli_fetch_array($sql))
{
    ?>
	    	<div class="accordion-group">
	            <div class="accordion-heading">
	                <a href="category.php?cid=<?php echo $row['id'];?>"  class="accordion-toggle collapsed">
	                   <?php echo $row['categoryName'];?>
	                </a>
	            </div>
	          
	        </div>
	        <?php } ?>
	    </div>
	</div>
</div>
	<!-- ============================================== CATEGORY : END ============================================== -->					<!-- ============================================== HOT DEALS ============================================== -->
<div class="sidebar-widget hot-deals wow fadeInUp">
	<h3 class="section-title">hot deals</h3>
	<div class="owl-carousel sidebar-carousel custom-carousel owl-theme">
		
								   <?php
$ret=mysqli_query($con,"select * from products order by rand() limit 4 ");
while ($rws=mysqli_fetch_array($ret)) {

?>

								        
					<div class="item">
              <div class="products">
                <div class="product text-center"> <!-- text-center ensures centering -->
                  <div class="product-image mb-3">
                    <a href="product-details.php?pid=<?php echo htmlentities($rws['id']); ?>">
                      <img
                        src="admin/productimages/<?php echo htmlentities($rws['id']); ?>/<?php echo htmlentities($rws['productImage1']); ?>"
                        alt="<?php echo htmlentities($rws['productName']); ?>"
                        class="img-fluid mx-auto d-block product-img"
                      >
                    </a>
                  </div>

                  <div class="product-info">
                    <h3 class="name mb-2">
                      <a href="product-details.php?pid=<?php echo htmlentities($rws['id']); ?>">
                        <?php echo htmlentities($rws['productName']); ?>
                      </a>
                    </h3>

                    <div class="product-price mb-2">
                      <span class="price">₹<?php echo htmlentities($rws['productPrice']); ?></span>
                      <span class="price-before-discount text-muted" style="text-decoration: line-through;">
                        ₹<?php echo htmlentities($rws['productPriceBeforeDiscount']); ?>
                      </span>
                    </div>

                    <?php if ($rws['productAvailability'] != 'In Stock') { ?>
                        <div class="text-danger mt-2">Out of Stock</div>
                      <?php }  ?>
                  </div>
                </div>
              </div>
            </div>
					<?php } ?>        
						
	    
    </div><!-- /.sidebar-widget -->
</div>

<!-- ============================================== COLOR: END ============================================== -->
				</div>
			</div><!-- /.sidebar -->
<?php 
$ret=mysqli_query($con,"select * from products where id='$pid'");
while($row=mysqli_fetch_array($ret))
{
$img1="admin/productimages/".$row['id']."/". $row['productImage1'];
$img2="admin/productimages/".$row['id']."/". $row['productImage2'];
$img3="admin/productimages/".$row['id']."/". $row['productImage3'];
?>


	<div class='col-md-9'>
		<div class="row  wow fadeInUp">
			<div class="col-xs-12 col-sm-6 col-md-5 gallery-holder text-center">
    			
				<div class="image-viewer">
					<div class="main-image-box">
						<img id="mainImage" src="<?php echo $img1; ?>" alt="Product">
					</div>

					<div class="thumbnails">
						<img src="<?php echo $img1; ?>" onclick="changeImage('<?php echo $img1; ?>')">
						<img src="<?php echo $img2; ?>" onclick="changeImage('<?php echo $img2; ?>')">
						<img src="<?php echo $img3; ?>" onclick="changeImage('<?php echo $img3; ?>')">
					</div>
				</div>
			</div>     			




			<div class='col-sm-6 col-md-7 product-info-block'>
				<div class="product-info">
					<h1 class="name"><?php echo htmlentities($row['productName']);?></h1>
<?php 
$rt = mysqli_query($con,"SELECT * FROM productreviews WHERE productId='$pid'");
$num = mysqli_num_rows($rt);

$overall_rating = 0;

if ($num > 0) {

    $total_quality = 0;
    $total_price   = 0;
    $total_value   = 0;

    while ($rowa = mysqli_fetch_assoc($rt)) {
        $total_quality += (int)$rowa['quality'];
        $total_price   += (int)$rowa['price'];
        $total_value   += (int)$rowa['value'];
    }

    // Average of all 3 rating columns
    $avg_quality = $total_quality / $num;
    $avg_price   = $total_price / $num;
    $avg_value   = $total_value / $num;

    // Final product rating out of 5
    $overall_rating = ($avg_quality + $avg_price + $avg_value) / 3;
    $overall_rating = round($overall_rating, 1); // e.g. 4.3
}
?>


							<div class="stock-container info-container m-t-10">
								<div class="row">
									<div class="col-sm-4">
										<div class="stock-box">
											<span class="label">Availability :</span>
										</div>	
									</div>
									<div class="col-sm-8">
										<div class="stock-box">
											<span class="value"><?php echo htmlentities($row['productAvailability']);?></span>
										</div>	
									</div>
								</div><!-- /.row -->	
							</div>



<div class="stock-container info-container m-t-10">
								<div class="row">
									<div class="col-sm-4">
										<div class="stock-box">
											<span class="label">Product Brand :</span>
										</div>	
									</div>
									<div class="col-sm-8">
										<div class="stock-box">
											<span class="value"><?php echo htmlentities($row['productCompany']);?></span>
										</div>	
									</div>
								</div><!-- /.row -->	
							</div>


<div class="stock-container info-container m-t-10">
								<div class="row">
									<div class="col-sm-4">
										<div class="stock-box">
											<span class="label">Shipping Charge :</span>
										</div>	
									</div>
									<div class="col-sm-8">
										<div class="stock-box">
											<span class="value"><?php if($row['shippingCharge']==0)
											{
												echo "Free";
											}
											else
											{
												echo htmlentities($row['shippingCharge']);
											}

											?></span>
										</div>	
									</div>
								</div><!-- /.row -->	
							</div>

							<div class="price-container info-container m-t-20">
								<div class="row">
									

									<div class="col-sm-6">
										<div class="price-box">
											<span class="price">₹ <?php echo htmlentities($row['productPrice']);?></span>
											<span class="price-strike">₹<?php echo htmlentities($row['productPriceBeforeDiscount']);?></span>
										</div>
									</div>




									

								</div><!-- /.row -->
							</div><!-- /.price-container -->

	




							<div class="quantity-container info-container">
								<div class="row">
									
									

									<div class="col-sm-7">
<?php if($row['productAvailability']!='In Stock'){?>
										
							<div class="action" style="color:red">Out of Stock</div>
					<?php } ?>
									</div>

									
								</div><!-- /.row -->
							</div><!-- /.quantity-container -->

				
							

							
						</div><!-- /.product-info -->
					</div><!-- /.col-sm-7 -->
				</div><!-- /.row -->

				
				<div class="product-tabs inner-bottom-xs  wow fadeInUp">
					<div class="row">
						<div class="col-sm-3">
							<ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
								<li class="active"><a data-toggle="tab" href="#description">DESCRIPTION</a></li>
								
							</ul><!-- /.nav-tabs #product-tabs -->
						</div>
						<div class="col-sm-9">

							<div class="tab-content">
								
								<div id="description" class="tab-pane in active">
									<div class="product-tab">
										<p class="text"><?php echo $row['productDescription'];?></p>
									</div>	
								</div><!-- /.tab-pane -->

																	
										
							        

				

							</div><!-- /.tab-content -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.product-tabs -->

<?php $cid=$row['category'];
			$subcid=$row['subCategory']; } ?>
				<!-- ============================================== UPSELL PRODUCTS ============================================== -->
<section class="section featured-product wow fadeInUp">
	<h3 class="section-title">Realted Products </h3>
	<div class="owl-carousel home-owl-carousel upsell-product custom-carousel owl-theme outer-top-xs">
	   
		<?php 
$qry=mysqli_query($con,"select * from products where subCategory='$subcid' and category='$cid' limit 10");
while($rw=mysqli_fetch_array($qry))
{

			?>	


		<div class="item">
              <div class="products">
                <div class="product text-center" style="height:350px;"> <!-- text-center ensures centering -->
                  <div class="product-image mb-3">
                    <a href="product-details.php?pid=<?php echo htmlentities($rw['id']); ?>">
                      <img
                        src="admin/productimages/<?php echo htmlentities($rw['id']); ?>/<?php echo htmlentities($rw['productImage1']); ?>"
                        alt="<?php echo htmlentities($rrwow['productName']); ?>"
                        class="img-fluid mx-auto d-block product-img"
                      >
                    </a>
                  </div>

                  <div class="product-info">
                    <h3 class="name mb-2">
                      <a href="product-details.php?pid=<?php echo htmlentities($rw['id']); ?>">
                        <?php echo htmlentities($rw['productName']); ?>
                      </a>
                    </h3>

                    <div class="product-price mb-2">
                      <span class="price">₹<?php echo htmlentities($rw['productPrice']); ?></span>
                      <span class="price-before-discount text-muted" style="text-decoration: line-through;">
                        ₹<?php echo htmlentities($rw['productPriceBeforeDiscount']); ?>
                      </span>
                    </div>

                    <?php if ($rw['productAvailability'] != 'In Stock') { ?>
                        <div class="text-danger mt-2">Out of Stock</div>
                      <?php }  ?>
                  </div>
                </div>
              </div>
            </div>
		<?php } ?>
	
		
			</div><!-- /.home-owl-carousel -->
</section><!-- /.section -->


<!-- ============================================== UPSELL PRODUCTS : END ============================================== -->
			
			</div><!-- /.col -->
			<div class="clearfix"></div>
		</div>
<?php include('includes/brands-slider.php');?>
</div>
</div>
<?php include('includes/footer.php');?>

	<script src="assets/js/jquery-1.11.1.min.js"></script>
	
	<script src="assets/js/bootstrap.min.js"></script>
	
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

	<!-- For demo purposes – can be removed on production -->
	
	<script src="switchstylesheet/switchstylesheet.js"></script>
	
	<script>
		$(document).ready(function(){ 
			$(".changecolor").switchstylesheet( { seperator:"color"} );
			$('.show-theme-options').click(function(){
				$(this).parent().toggleClass('open');
				return false;
			});
		});

		$(window).bind("load", function() {
		   $('.show-theme-options').delay(2000).trigger('click');
		});
	</script>
	<!-- For demo purposes – can be removed on production : End -->

	

</body>
</html>