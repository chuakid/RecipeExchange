<!doctype html>
<?php
require_once( 'connstring.php' );
?>
<html>

<head>
	<meta charset="utf-8">
	<title>Home</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/index.css" rel="stylesheet">
</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<section id="toprecipes">
			<h2>Top Rated Recipes</h2>
			<section class="recipes">
				<?php 
				if(isset($_GET['searchstring'])){
				$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.status = 'available' AND name like '%{$_GET['searchstring']}%' GROUP BY b.id ORDER BY stars desc";

				}
				else {
				$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.status = 'available' GROUP BY b.id ORDER BY stars desc";
				}
					$result = $conn->query($query);				
				if($result->num_rows > 0){
				for($i2 = 0; $i2<2; $i2++ ){
					$row = $result->fetch_assoc();
					$title = $row['name'];
					$author = $row['displayName'];
					$Id = $row['id'];
					$imageext = $row['imageext'];
					
					
					$query2 = "SELECT count(*) AS count FROM favourite WHERE recipe = '$Id' GROUP BY recipe";
					$result2 = $conn->query($query2);
					$row2 = $conn->query($query2)->fetch_assoc();
					$favourites = $result2->num_rows > 0 ? $row2['count'] : 0;
					
					$query2 = "SELECT avg(stars) as avg FROM review WHERE recipe = '$Id'";
					$row2 = $conn->query($query2)->fetch_assoc();
					$avgstars = round($row2['avg']);
				?>
				<article class="recipe">
					<h3>
						<?php echo $title; ?>
					</h3>
					<a href="viewrecipe.php?id=<?php echo $Id; ?>">
						<img class="foodimage" src="uploadedPhotos/<?php echo $Id . '.' . $imageext;?>">
					</a>
				







					<p>Recipe By
						<span>
							<?php echo $author;?>
						</span>
					</p>
					<?php 
					//Stars
					for($i=0; $i<$avgstars;$i++){ 
						echo '<img class="star" src="Assets/Icons_OrangeStar.png">';
					}
					for($i=0; $i<5 - $avgstars;$i++){ 
						echo '<img class="star" src="Assets/Icons_GrayStar.png">';
					}
						
					?>
					<span class="likes"><img src="Assets/Icons_OrangeHeart.png"><?php echo $favourites; ?></span>
				</article>


				<?php } }?>
			</section>
		</section>

		<section id="otherrecipes">
			<h2>Other Recipes</h2>
			<section class="recipes">
				<?php 
				while($row = $result->fetch_assoc()){			
					$imageext = $row['imageext'];
					$title = $row['name'];
					$author = $row['displayName'];
					$Id = $row['id'];
					
					$query2 = "SELECT count(*) AS count FROM favourite WHERE recipe = '$Id' GROUP BY recipe";
					$result2 =  $conn->query($query2);
					$row2 =  $result2->fetch_assoc();
					$favourites = $result2->num_rows > 0 ? $row2['count'] : 0;
					
					$query2 = "SELECT avg(stars) as avg FROM review WHERE recipe = '$Id'";
					$row2 = $conn->query($query2)->fetch_assoc();
					$avgstars = round($row2['avg']);
				?>
				<article class="recipe">
					<h3>
						<?php echo $title; ?>
					</h3>
					<a href="viewrecipe.php?id=<?php echo $Id; ?>">
						<img class="foodimage" src="uploadedPhotos/<?php echo $Id . '.' . $imageext;?>">
					</a>
				








					<p>Recipe By
						<span>
							<?php echo $author;?>
						</span>
					</p>
					<?php 
					//Stars
					for($i=0; $i<$avgstars;$i++){ 
						echo '<img class="star" src="Assets/Icons_OrangeStar.png">';
					}
					for($i=0; $i<5 - $avgstars;$i++){ 
						echo '<img class="star" src="Assets/Icons_GrayStar.png">';
					}
						
					?>
					<span class="likes"><img src="Assets/Icons_OrangeHeart.png"> <?php echo $favourites; ?></span>
				</article>


				<?php } ?>
			</section>
		</section>
	</main>
	<?php include_once('footer.php'); ?>
</body>

<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>

</html>