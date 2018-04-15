<nav>
	<a href="index.php"><img src="Assets/Logo.png"></a>
	<section id="browse">
		<div>
			<button id="browsebtn">BROWSE BY<img src="Assets/Icons_GrayDown Arrow.png"></button>
			<section id="browseDropDown">
				<button id="categorybtn" class="filter">Categories</button>
				<?php 
				$query = "SELECT * FROM category";
				$result = $conn->query( $query );
				while ( $row = $result->fetch_assoc() ) {
					echo '<a href="category.php?catid=' . $row[ 'id' ] . '" class="category">' . $row[ 'category' ] . '</a>';
				}

				?>
				<a href="orderbydate.php" class="filter">By Date</a>
			</section>
		</div>
		<form id="searchform" action='index.php' method=get>
			<input type="text" <?php if(isset($_GET['searchstring'])) echo "value='{$_GET['searchstring']}'" ?> id="search" name="searchstring" placeholder="Search">
			<input type="image" value="submit" src="Assets/Icons_WhiteSearch.png">
		</form>

	</section>
	<div>
		<button id="profile"><img id="profileicon" src="Assets/Icons_WhiteProfile.png">PROFILE<img src="Assets/Icons_WhiteDown Arrow.png"></button>
		<button id="profilesmall"><img id="smallprofileicon" src="Assets/Icons_Hamburger.png"></button>
		<?php 
		if(!isset($_SESSION['role'])){ ?>
		<section id='profileDropDown'>
			<a class="profileitem" href="login.php">Login</a>
			<a class="profileitem" href="register.php">Register</a>
		</section>
		<?php
		} else if ( $_SESSION[ 'role' ] == '1' ) {
			?>
		<section id='profileDropDown'>
			<a class="profileitem" href="recipeadd.php">Add Recipe</a>
			<a class="profileitem" href="favourites.php">Favourites</a>
			<a class="profileitem" href="editprofile.php">Edit Profile</a>
			<a class="profileitem" href="uploaded.php">My Recipes</a>
			<a class="profileitem" href="logout.php">Sign Out</a>
		</section>
		<?php 
		} else if($_SESSION['role'] == '2'){ ?>
		<section id='profileDropDown'>
			<a class="profileitem" href="recipeadd.php">Add Recipe</a>
			<a class="profileitem" href="managecategories.php">Manage Categories</a>
			<a class="profileitem" href="editprofile.php">Edit Profile</a>
			<a class="profileitem" href="uploaded.php">My Recipes</a>
			<a class="profileitem" href="reported.php">Reported Recipes</a>
			<a class="profileitem" href="logout.php">Sign Out</a>
		</section>
		<?php } ?>
	</div>
</nav>