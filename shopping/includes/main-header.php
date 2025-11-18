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
			<div class="row">
				<div class="col-xs-1 col-sm-3 col-md-3 logo-holder">
					<!-- ============================================================= LOGO ============================================================= -->
					<div class="logo">
						<a href="index.php">
							
							<h2>KC</h2>

						</a>
					</div>		
				</div>
				<div class="col-xs-5 col-sm-6 col-md-6 top-search-holder">
					<div class="search-area">
						<form name="search" method="post" action="search-result.php">
							<div class="control-group">

								<input class="search-field" placeholder="Search here..." name="product" required="required" />

								<button class="search-button" type="submit" name="search"></button>    

							</div>
						</form>
					</div>
					
				</div>
				<div class="col-xs-1 col-sm-3 col-md-3">
				<button class="btn btn-outline-primary float-right d-block d-md-block d-lg-none " type="button" data-bs-toggle="offcanvas" data-bs-target="#rightSidebar">
					<i class="bi bi-bars"></i>
				</button>
				</div>
		</div>
	</div>

			