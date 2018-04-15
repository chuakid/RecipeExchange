<?php 
session_start();
echo $_SESSION['userid'];

print_r($_GET);
print_r($_POST);
print_r($_FILES);
$id='333';
		if(isset($_FILES['image'])){
			if($_FILES['image']['error'] == 0){
			$target_dir = "uploadedPhotos/";			$type = explode('/',$_FILES['image']['type']);
			$type = array_pop($type);

			$target_file = $target_dir . $id . '.' . $type;
			move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
		} }

?>
