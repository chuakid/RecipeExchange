<?php
require_once( "connstring.php" );
if ( !isset( $_GET[ 'searchstring' ] ) ) {
	header( 'Location: index.php' );
}
$searchstring = $_GET[ 'searchstring' ];

 if ( $searchstring == '' && isset( $_GET[ 'index' ] ) ) {
	?>
	<section id="toprecipes">
		<h2>Top Rated Recipes</h2>
		<section class="recipes">
			<?php 
				$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.status = 'available' GROUP BY b.id ORDER BY stars";
				$result = $conn->query($query);				
				
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


			<?php } ?>
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
	<?php
} else {
	echo '<section id="otherrecipes">';
	if ( isset( $_GET[ 'catid' ] ) ) {
		$catid = $_GET['catid'];
		$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.name LIKE '%$searchstring%' AND category = '$catid' GROUP BY b.id ORDER BY stars";
	} else if ( isset( $_GET[ 'order' ] ) ) {
		$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.name LIKE '%$searchstring%' GROUP BY b.id ORDER BY b.date {$_GET['order']}";
		?>
			<h2>Ordered By Date</h2>
			<select id="order">
				<option value=asc >Ascending</option>
				<option value=desc <?php echo $_GET['order'] == 'desc' ? 'selected' : null?>>Descending</option>
			</select>
			<?php

			} else {
				$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.name LIKE '%$searchstring%' GROUP BY b.id ORDER BY stars";
			}

			?>
				<?php 
				$result = $conn->query($query);		
				if(isset($catid)){
					$row2 = $conn->query("SELECT * from category where id = '$catid'")->fetch_assoc();
					echo "<h2>" . $row2['category'] . "</h2>";
					echo "<img id='banner' src='categoryBanners/$catid" . '.' . "{$row2['filetype']}'>";
				}
	 			echo '<section class="recipes">';
			
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
		<?php } ?>