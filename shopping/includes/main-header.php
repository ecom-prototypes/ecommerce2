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
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 logo-holder">
					<!-- ============================================================= LOGO ============================================================= -->
					<div class="logo">
						<a href="index.php">
							
							<h2>Khusi Computer</h2>

						</a>
					</div>		
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 top-search-holder">
				<div class="search-area">
					<form name="search" method="post" action="search-result.php">
						<div class="control-group">

							<input class="search-field" placeholder="Search here..." name="product" required="required" />

							<button class="search-button" type="submit" name="search"></button>    

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

			