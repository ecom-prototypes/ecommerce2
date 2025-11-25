<?php 

 if(isset($_Get['action'])){
		if(!empty($_SESSION['cart'])){
		foreach($_POST['quantity'] as $key => $val){
			if($val==0){
				unset($_SESSION['cart'][$key]);
			}else{
				$_SESSION['cart'][$key]['quantity']=$val;
			}
		}
		}
	}
?>
	<div class="main-header">
		<div class="container mb-1">
			<div class="row d-flex">
				<div class="col-xs-3 col-sm-3 col-md-3 logo-holder">
					<!-- ============================================================= LOGO ============================================================= -->
					<div class="logo">
						<a href="index.php">
							
							<h2>KC</h2>

						</a>
					</div>		
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 top-search-holder">
					<div class="search-area">
						<form name="search" method="post" action="search-result.php">
							<div class="control-group">

								<input class="search-field" placeholder="Search here..." name="product" required="required" />

								<button class="search-button" type="submit" name="search"></button>    

							</div>
						</form>
					</div>
					
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3 d-flex align-items-center justify-content-end">
					<button data-component="sidebar" data-target="left" class="btn btn-outline-primary float-right d-block d-md-block d-lg-none sidebar-toggle-btn " type="button" >
						<i class="bi bi-list"></i>
					</button>
				</div>
		</div>
	</div>
	<div class="na left sidebar" id="left">
		<div class="side-menu">
		<div class="head">Category</div>
	
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
</div>
	</div>


			