<?php
require_once( 'connstring.php' );
if ( $_SESSION[ 'role' ] > 1 ) {
	if ( isset( $_POST[ 'operation' ] ) ) {
		if ( $_POST[ 'operation' ] == 'add' ) {
			$category = mysqli_real_escape_string( $conn, $_POST[ 'categoryName' ] );
			$type = explode( '/', $_FILES[ 'image' ][ 'type' ] );
			$type = array_pop( $type );


			$query = "INSERT INTO category(category,filetype) VALUES('$category', '$type')";
			$conn->query( $query );
			

			$target_dir = "categoryBanners/";
			$lastid = $conn->insert_id;
			$target_file = $target_dir . $lastid . '.' . $type;
			
			echo $lastid . '.'. $type;

			move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $target_file );
		} else if ( $_POST[ 'operation' ] == 'changeimage' ) {
			if($_FILES['image']['error'] == 0){
			$type = explode( '/', $_FILES[ 'image' ][ 'type' ] );
			echo $type = array_pop( $type );
			
			$target_dir = "categoryBanners/";
			$id = $_POST[ 'id' ];
			$target_file = $target_dir . $id . '.' . $type;
			move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $target_file );

			$query = "UPDATE category SET filetype = '$type' WHERE id = {$_POST['id']} ";
			$conn->query( $query );
		}
			else if ($_FILES['image']['error'] == 1) {
				echo 'File size too big';
			}

		}
	} else if ( $_GET[ 'operation' ] == 'delete' ) {
		$query = "SELECT * FROM recipe WHERE category = {$_GET['id']} limit 1";
		if ( $conn->query( $query )->num_rows != 0 ) {
			echo "That category is in use";
		} else {
			$query = "DELETE FROM category WHERE id='{$_GET['id']}'";
			$conn->query( $query );
			echo '';
		}
	} else if ( $_GET[ 'operation' ] == 'edit' ) {
		$name = mysqli_real_escape_string( $conn, $_GET[ 'name' ] );
		$query = "UPDATE category SET category = '$name' WHERE id = '{$_GET['id']}'";
		$conn->query( $query );
		echo $_GET[ 'name' ];
	}

}
?>