<!doctype html>
<?php 

require_once('connstring.php'); 
	if(!isset($_SESSION['user'])){
		header('Location: index.php');
	}
	if(isset($_POST['title'])){
		
/*
		echo "<pre>";
		print_r($_POST);	
		print_r($_FILES);
		echo "</pre>";
*/
		
		$title= mysqli_real_escape_string($conn,$_POST['title']);
		$description= mysqli_real_escape_string($conn,$_POST['description']);
		$category = $_POST['category'];
		$userid = $_SESSION['userid'];
		$ingredient= $_POST['ingredient'];
		$steps= $_POST['step'];
		$servings = $_POST['servings'];
		$preptime = $_POST['preptime'];
		$timeneeded = $_POST['time'];
		$target_dir = "uploadedPhotos/";
		
		$type = explode('/',$_FILES['image']['type']);
		$type = array_pop($type);
		
		//Add to Recipe DB
		
		$query = "INSERT INTO recipe(name,submitter,description,category,status,timeneeded,imageext,preptime,servings) VALUES('$title','$userid','$description','$category','available','$timeneeded','$type','$preptime','$servings')";
		
		$conn->query($query);
		
		//Upload image
		$lastid = $conn->insert_id;
		$target_file = $target_dir . $lastid . '.' . $type;
		
		move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
		
		//Add to ingredients
		
		foreach($ingredient as $v){
			$query = "INSERT INTO ingredient(recipe,ingredient) VALUES('$lastid','$v')";
			$conn->query($query);
		}
		
		//Add to steps
		foreach($steps as $k => $v){
			$query = "INSERT INTO steps(description,recipeId,position) VALUES('$v','$lastid','$k')";
			$conn->query($query);
		}
	}
?>

<html>

<head>
	<meta charset="utf-8">
	<title>Add A Recipe</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/add.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Add a recipe</h2>
		<form id="add" action="recipeadd.php" method="post" enctype="multipart/form-data">
			<label for="title">Recipe Title</label>
			<input type="text" name="title" required>

			<label for="description">Recipe Description</label>
			<textarea name="description" required></textarea>

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
			</span><br>

			<button id="addingredientbtn">Add Ingredient</button>
			<br>

			<label>Steps</label>
			<span id="stepsList">
			</span>
		
			<button id="addStepsBtn">Add Step</button>

			<br>
			<label>Add Image</label><br>
			<label for="image" id="imagelabel">Choose Image</label>
			<input type="file" name="image" id="image" accept="image/*" required value="Choose Image">
			<span id="file-selected"></span>
			<br>

			<label for="prep">Prep Time Needed</label>
			<input placeholder="Time in Minutes" required id='prep' min="0" name='preptime'>

			<label for="time">Total Time Needed</label>
			<input id="time" name="time" type="number" required placeholder="Time in Minutes" min=0>

			<label for="servings">Servings</label>
			<input id="servings" name="servings" type="number" required placeholder="Servings" min=0>

			<input type="submit" value="Submit">
		</form>
	</main>

	<?php include_once('footer.php'); ?>

</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>
<script>
	var ingredientCount = 0,
		stepCount = 0;

	$( '#addingredientbtn' ).click( function ( e ) {
		ingredientCount++;
		var input = document.createElement( 'input' );
		input.type = "text";
		input.name = "ingredient[]";

		var button = document.createElement( 'button' );
		button.textContent = "Remove Ingredient";

		button.addEventListener('click',function() {
			$(this).parent().remove();
			e.preventDefault();
		})

		var section = document.createElement('section');
		section.appendChild(input);
		section.appendChild(button);
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

		button.addEventListener('click',function() {
			$(this).parent().remove();
			e.preventDefault();
		})
	
		var section = document.createElement('section');
		section.appendChild(input);
		section.appendChild(button);

		$( "#stepsList" ).append( section );
		e.preventDefault();

	} )

	$( '#image' ).on( 'change', function () {
		var fileName = $( this ).val().split( '\\' ).pop();
		$( '#file-selected' ).html( fileName );
	} )
</script>

</html>