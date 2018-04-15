<?php
require_once( 'connstring.php' );

if ( !isset( $_GET[ 'operation' ] ) || !isset($_SESSION['role']) ) {
	header( 'Location: index.php' );
} else {
	$id = $_GET[ 'id' ];
	switch ( $_GET[ 'operation' ] ) {
		case 'report':
			$query = "UPDATE recipe SET status = 'reported' WHERE id = '$id'";
			$conn->query( $query );
			break;
		case 'favourite':
			$query = "INSERT INTO favourite VALUES({$_SESSION['userid']},'$id')";
			$conn->query( $query );
			break;
		case 'unfavourite':
			$query = "DELETE FROM favourite WHERE user = '{$_SESSION['userid']}' AND recipe = '$id'";
			$conn->query( $query );
			break;
		case 'censor':
			if ( $_SESSION[ 'role' ] > 1 ) {
				$query = "UPDATE recipe SET status = 'censored' WHERE id = '$id'";
				$conn->query( $query );
			}

			break;
		case 'delete':
			if ( $_SESSION[ 'role' ] > 1 ) {
				$query = "DELETE FROM RECIPE WHERE id = '$id'";
				$conn->query( $query );
			}
			break;
		case 'deletereview':
			if($_SESSION['role'] < 2){
				$query = "SELECT * FROM review WHERE id = '$id' AND submitter = '{$_SESSION['userid']}'";
				if($conn->query($query)->num_rows > 0){
					$query = "DELETE FROM review WHERE id = '$id'";
					$conn->query($query);
				}
				
			}
			else 		
				$query = "DELETE FROM review WHERE id = '$id'";
				$conn->query($query);
			break;

	}
	header( 'Location: index.php' );
}
?>