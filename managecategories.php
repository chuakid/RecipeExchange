<!doctype html>
<?php
require_once( 'connstring.php' );
?>
<html>

<head>
	<meta charset="utf-8">
	<title>Category Management</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/categories.css" rel="stylesheet">
</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Category Management</h2>
		<section>
			<section>
				<div>ID</div>
				<div>Category</div>
				<div>Delete</div>
				<div>Change Picture</div>
				<div>Banner</div>
			</section>
			<?php 
		$query = "SELECT * from category";
		$result = $conn->query($query);
		while($row = $result->fetch_assoc()){
			$id = $row['id'];
			echo "<section id=$id>";
			echo '<div>';
			echo $row['id'];
			echo '</div>';
			
			echo '<div>';
			echo $row['category'];
			echo '</div>';
			
			echo '<div>';
			echo '<button class="delete">Delete</button>';
			echo '</div>';
			
			echo '<div>';
			echo '<label class="imagelabel" for="picture' . $id .'">Change Picture</label>';
			echo '<input type="file" id="picture' . $id . '">';
			echo '</div>';
			
			echo '<div>';
			echo '<img src=categoryBanners/' . $id . '.' . $row['filetype'] . '>';
			echo '</div>';
			
			
			echo "</section>";
			
			
		}
		
		?>
		</section>
		<form id="addCat">
			<label for="image" class="imagelabel">Choose Image</label>
			<input type="file" name="image" id="image" accept="image/*" required value="Choose Image">
			<span id="file-selected"></span>

			<input placeholder='Category Name' id=name type="text" name="categoryName">
			<button>Add Category</button>
		</form>


	</main>
	<?php include_once('footer.php'); ?>
</body>

<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>
<script>
	function deleteCat( e ) {
		var section = $( e.target ).parent().parent(),
			id = section.attr( 'id' );
		console.log( e.target );
		$.ajax( {
			url: 'categoryscripts.php',
			data: {
				operation: 'delete',
				id: id
			}
		} ).done( function ( result ) {
			if ( result == '' ) {
				section.remove();
			} else {
				alert( result )
			};
		} )
	}

	function changePicture( e ) {
		var image = this.files[ 0 ],
			data = new FormData( this );

		data.append( 'image', image );
		var id = this.parentNode.parentNode.id;
		data.append( 'id', id );
		data.append( 'operation', 'changeimage' );
		$.ajax( {
			url: 'categoryscripts.php',
			data: data,
			type: 'POST',
			contentType: false,
			processData: false
		} ).done( function ( result ) {
			if (result == "File size too big"){
				alert(result);	
			} else {
			$('#' + id + ' img').attr('src', 'categoryBanners/' + id + '.' + result + '?t=' + new Date().getTime());
		}
		} )
	}

	function editCat( e ) {
		var target = e.target,
			name = $( target ).val(),
			id = $( target ).parent().parent().attr( 'id' );

		$.ajax( {
			url: 'categoryscripts.php',
			data: {
				operation: 'edit',
				name: name,
				id: id
			}
		} ).done( function ( result ) {
			$( target ).parent().text( result );

			$( target ).remove();

		} )

	}

	function addInput( e ) {
		var a = document.createElement( 'input' );
		a.value = e.target.textContent;

		//Check if input already exists, just use input's value
		if ( $( e.target ).children( 'input' )[ 0 ] != undefined ) {
			a.value = $( e.target ).children( 'input' )[ 0 ].val();
		}

		$( e.target ).children( 'input' ).remove();

		e.target.textContent = '';
		e.target.appendChild( a );
		$( a ).focus();
		$( a ).on( 'focusout', editCat );



	}

	$( 'main section div:nth-child(2)' ).click( addInput );


	$( '.delete' ).click( deleteCat );
	$( 'div input[type=file]' ).change( changePicture );

	$( 'main form' ).submit( function ( e ) {
		e.preventDefault();
		var formdata = new FormData( this );
		var name = $( '#name' ).val();
		$( '#name' ).val( '' );
		formdata.append( 'operation', 'add' );

		$.ajax( {
			url: 'categoryscripts.php',
			data: formdata,
			type: 'POST',
			processData: false,
			contentType: false,
		} ).done( function ( result ) {
			var section = document.createElement( 'section' ),
				idDiv = document.createElement( 'div' ),
				categoryDiv = document.createElement( 'div' ),
				btnDiv1 = document.createElement( 'div' ),
				btn1 = document.createElement( 'button' ),
				btnDiv2 = document.createElement( 'div' ),
				label = document.createElement( 'label' ),
				btn2 = document.createElement( 'input' ),
				imgdiv = document.createElement('div'),
				img = document.createElement('img');
			
			console.log(result);
			var array = result.split('.'), 
				type = array.pop(),
				result = array.join('');
			
			console.log(array);
			console.log(type);
			console.log(result);
			
			idDiv.textContent = result;
			categoryDiv.textContent = name;
			btn1.textContent = "Delete";

			label.textContent = "Change Picture";

			btn2.type = "file";
			btn2.id = 'picture' + result;

			btnDiv1.append( btn1 );
			btnDiv2.append( label );
			btnDiv2.append( btn2 );
			imgdiv.append(img);
			

			section.append( idDiv );
			section.append( categoryDiv );
			section.append( btnDiv1 );
			section.append( btnDiv2 );
			section.append(imgdiv);

			section.id = result;
			$( 'main>section' ).append( section );

			$( label ).addClass( 'imagelabel' );
			$( label ).attr( 'for', 'picture' + result )
			$( btn1 ).click( deleteCat );
			$( btn2 ).change( changePicture );
			$ (img ).attr('src','categoryBanners/' + result + '.' + type);

		} )
	} );
</script>

</html>