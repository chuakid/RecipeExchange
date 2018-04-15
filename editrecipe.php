<!doctype html>
<?php 

require_once('connstring.php'); 
	if(!isset($_SESSION['user'])){
		header('Location: index.php');
	}

	else if($_SESSION['role'] < 2){
		$query = "SELECT submitter FROM recipe WHERE id = '{$_GET['id']}'";
		$row = $conn->query($query)->fetch_assoc();
		if($row['submitter'] != $_SESSION['userid']){
			header('Location: index.php');
		}
	}

	if(isset($_POST['title'])){
		
/*
		echo "<pre>";
		print_r($_POST);	
		print_r($_FILES);
		echo "</pre>";
*/
		$id = $_POST['id'];
		$title= mysqli_real_escape_string($conn,$_POST['title']);
		$description= mysqli_real_escape_string($conn,$_POST['description']);
		$category = $_POST['category'];
		$ingredient= $_POST['ingredient'];
		$steps= $_POST['step'];
		$preptime = $_POST['preptime'];
		$servings = $_POST['servings'];
		$timeneeded = $_POST['time'];
			
		
		//Upload image
		if(isset($_FILES['image'])){
			if($_FILES['image']['error'] == 0){
			$target_dir = "uploadedPhotos/";		
			$type = explode('/',$_FILES['image']['type']);
			$type = array_pop($type);
			$target_file = $target_dir . $id . '.' . $type;
			move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
		}
			}	
		//Add to Recipe DB
		if(isset($type)){
			$query = "UPDATE recipe
					SET title = '$title',
					description = '$description',
					category = '$category',
					timeneeded = '$timeneeded',
					type = '$type'
					preptime = '$preptime',
					servings = '$servings',
					WHERE id = '$id'";
			}
		else {
			$query = "UPDATE recipe
					SET title = '$title',
					description = '$description',
					category = '$category',			
					preptime = '$preptime',
					servings = '$servings',
					timeneeded = '$timeneeded' WHERE id = '$id'";
		}
	
		$conn->query($query);
		
		//Add to ingredients
		$query = "DELETE FROM ingredient WHERE recipe = '$id'";
		$conn->query($query);
		foreach($ingredient as $v){
			$query = "INSERT INTO ingredient(recipe,ingredient) VALUES('$id','$v')";
			$conn->query($query);
		}
		
		//Add to steps
		$query = "DELETE FROM steps WHERE recipeId = '$id'";
		$conn->query($query);
		foreach($steps as $k => $v){
			$query = "INSERT INTO steps(description,recipeId,position) VALUES('$v','$id','$k')";
			$conn->query($query);
		}
		
		header('location:index.php');
	
	}



	if(isset($_GET['id'])){
		$id = $_GET['id'];
		$query = "SELECT * FROM recipe WHERE id = '$id'";
		$result = $conn->query($query);
		if($result->num_rows==0){
			header('Location: index.php');
		}
		
		$row = $result->fetch_assoc();
		
		
		$title= $row['name'];
		$description= $row['description'];
		$category = $row['category'];
		$timeneeded = $row['timeneeded'];
		$preptime = $row['preptime'];
		$servings = $row['servings'];

		//Get ingredients
		$query = "SELECT * FROM ingredient WHERE recipe='$id'";
		$result = $conn->query($query);
		$counter = 0;
		while($row = $result->fetch_assoc()){
			$ingredients[$counter]= $row['ingredient'];
			$counter++;
		}
		
		$query = "SELECT * FROM steps WHERE recipeId='$id'";
		$result = $conn->query($query);
		$counter = 0;
		while($row = $result->fetch_assoc()){
			$steps[$counter]= $row['description'];
			$counter++;
		}
	}	

else {
	header('index.php');
}

?>

<html>

<head>
	<meta charset="utf-8">
	<title>Edit Recipe</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/add.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Edit Recipe</h2>
		<form id="add" action="editrecipe.php" method="post" enctype="multipart/form-data">
			<label for="title">Recipe Title</label>
			<input type="text" value='<?php echo $title?>' name="title" required>

			<label for="description">Recipe Description</label>
			<textarea name="description" required><?php echo $description ?>
			</textarea>

			<label for="category">Recipe Category</label>
			<select name="category">
				<?php
				$query = "SELECT * FROM CATEGORY";
				$result = $conn->query( $query );
				while ( $row = $result->fetch_assoc() ) {
					echo "<option value='{$row['id']}'>{$row['category']}</option>";
				}

				?>
			</select>

			<label>Ingredients</label>
			<span id="ingredientslist">
				<?php 
			
						foreach($ingredients as $k => $v){
						echo '<section>';
						echo '<input type="text" value="' . $v . '" name="ingredient[]">';
							echo '<button>Remove Ingredient</button>';
							echo '</section>';
						}		
				

				
				?>
			</span>

			<button id="addingredientbtn">Add Ingredient</button>
			<br>

			<label>Steps</label>
			<span id="stepsList">
				<?php 
						foreach($steps as $k => $v){
							echo '<section>';
							echo '<textarea name="step[]" required>'. $v .'</textarea>';
							echo '<button>Remove Step</button>';
							echo '</section>';
						}		

				
				?>

			</span>

			<button id="addStepsBtn">Add Step</button>

			<br>
			<label>Add Image</label><br>
			<label for="image" id="imagelabel">Choose Image</label>
			<input type="file" name="image" id="image" accept="image/*" value="Choose Image">
			<span id="file-selected"></span>
			<br>

			<label for="prep">Prep Time Needed</label>
			<input placeholder="Time in Minutes" required id='prep' value="<?php echo $preptime; ?>" min="0" name='preptime'>

			<label for="time">Total Time Needed</label>
			<input id="time" name="time" type="number" required placeholder="Time in Minutes" min=0 value="<?php echo $timeneeded ?>">

			<label for="servings">Servings</label>
			<input id="servings" name="servings" type="number" required placeholder="Servings" min=0 value="<?php echo $servings ?>">

			<input type="submit" value="Submit">
			<input type="hidden" name='id' value="<?php echo $_GET['id'] ?>">
		</form>
	</main>

	<?php include_once('footer.php'); ?>

</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>
<script>
	var ingredientCount = <?php echo count($ingredients)  ?>,
		stepCount = <?php echo count($steps)  ?>;

	$( "#add" ).submit( function ( e ) {
		if ( ingredientCount < 1 ) {
			alert( "Need ingredients" );
			e.preventDefault();
		} else if ( stepCount < 1 ) {
			alert( "Need steps" );
			e.preventDefault();
		}

	} )

	$( '#ingredientslist section button' ).click( function ( e ) {
		$( this ).parent().remove();
		e.preventDefault();
	} )
	$( '#stepsList section button' ).click( function ( e ) {
		$( this ).parent().remove();
		e.preventDefault();
	} )


	$( '#addingredientbtn' ).click( function ( e ) {
		ingredientCount++;
		var input = document.createElement( 'input' );
		input.type = "text";
		input.name = "ingredient[]";

		var button = document.createElement( 'button' );
		button.textContent = "Remove Ingredient";

		button.addEventListener( 'click', function () {
			$( this ).parent().remove();
			e.preventDefault();
		} )

		var section = document.createElement( 'section' );
		section.appendChild( input );
		section.appendChild( button );
		$( "#ingredientslist" ).append( section );

		e.preventDefault();

	} )
	$( '#addStepsBtn' ).click( function ( e ) {
		stepCount++;
		var input = document.createElement( 'textarea' );
		input.type = "text";
		input.name = "step[]";

		var button = document.createElement( 'button' );
		button.textContent = "Remove Step";

		button.addEventListener( 'click', function () {
			$( this ).parent().remove();
			e.preventDefault();
		} )

		var section = document.createElement( 'section' );
		section.appendChild( input );
		section.appendChild( button );

		$( "#stepsList" ).append( section );
		e.preventDefault();

	} )

	$( '#image' ).on( 'change', function () {
		var fileName = $( this ).val().split( '\\' ).pop();
		$( '#file-selected' ).html( fileName );
	} )
</script>

</html>