<div class="header-nav animate-dropdown d-none d-md-none d-lg-block">
    <div class="container">
        <div class="yamm navbar navbar-default" role="navigation">
            
            <div class="nav-bg-class">
                <div class="navbar-collapse collapse" id="mc-horizontal-menu-collapse">
	<div class="nav-outer">
		<ul class="nav navbar-nav">
			<li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?> dropdown yamm-fw">
                <a href="index.php">Home</a>
            </li>

       <?php 
$cid = isset($_GET['cid']) ? $_GET['cid'] : '';
$sql = mysqli_query($con,"SELECT id, categoryName FROM category LIMIT 6");

while($row = mysqli_fetch_array($sql)) {
    $active = ($cid == $row['id']) ? 'active' : '';
?>
    <li class="dropdown yamm <?php echo $active; ?>">
        <a href="category.php?cid=<?php echo $row['id']; ?>">
            <?php echo $row['categoryName']; ?>
        </a>
    </li>
<?php } ?>


		<div class="clearfix"></div>				
	</div>
</div>


            </div>
        </div>
    </div>
</div>