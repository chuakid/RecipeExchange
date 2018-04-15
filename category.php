<!doctype html>
<?php
require_once( 'connstring.php' );
if ( !isset( $_GET[ 'catid' ] ) ) {
	header( 'Location: index.php' );
} else {
	$id = $_GET[ 'catid' ];
}
?>
<html>

<head>
	<meta charset="utf-8">
	<title>Category</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/index.css" rel="stylesheet">
	<style>
		#banner {
			margin: 0 auto;
			display: block;
			width: 1000px;
			height: 300px;
			box-shadow: 0px 3px 2px 2px gray;
			filter: brightness(50%);
		}
		#otherrecipes{
			overflow: hidden;
		}
		
		main>section>h2 {
			position: absolute;
			color: white;
			margin: 0 auto;
			left: 0;
			right: 0;
			text-align: center;
			top: 200px;
			z-index: 2;
			font-size: 72px;
		}
	</style>
</head>

<body>
	<?php include_once('nav.php');
		
	?>
	<main>

		<section id="otherrecipes">
			<h2>
				<?php $query = "SELECT * FROM category where id = '$id'"  ;
						$result = $conn->query($query);
				if($result->num_rows == 0 ){
					header('location:index.php');
				}
				else {
					$row = $result->fetch_assoc();
					echo "<h2>{$row['category']}</h2>";
					echo "<img id='banner' src='categoryBanners/$id" . '.' . "{$row['filetype']}'>";

				}
				?>
			</h2>
			<section class="recipes">
				<?php 
				$query = "SELECT b.imageext,b.id, a.displayName, b.name, avg(c.stars) as stars FROM user as a JOIN recipe as b ON b.submitter = a.id LEFT JOIN review as c ON c.recipe = b.id WHERE b.category = '$id' GROUP BY b.id ORDER BY stars";
				$result = $conn->query($query);		
				
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
<script>
	var id = <?php echo $id ?>
</script>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>

</html>