<?php
	$target_dir = '../assets/images/uploads/';
	$target_file = $target_dir . basename($_FILES['file']['name']);

	$image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

	// Check file size
	if(!$_FILES['file']['size'] || $_FILES['file']['size'] >= 2000000){
	  	exit(json_encode(array(
            'Type' => 'error',
            'Reply' => 'File size (2 mb) limit exceeds!'
        )));
	}

	// Check if image file is a actual image or fake image
  	if(!getimagesize($_FILES['file']['tmp_name'])){
	    exit(json_encode(array(
            'Type' => 'error',
            'Reply' => 'File is not an image!'
        )));
	}

	// Check if file already exists
	/*if(file_exists($target_file)){
	  	exit(json_encode(array(
            'Type' => 'error',
            'Reply' => 'File already exists! Please choose a different one, or rename it.'
        )));
	}*/

	// Allow certain file formats
	if($image_file_type !== 'jpg' && $image_file_type !== 'png' && $image_file_type !== 'jpeg'){
	  	exit(json_encode(array(
            'Type' => 'error',
            'Reply' => 'Invalid file type! File type should be jpg, jpeg or png'
        )));
	}

	// Upload image file
	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
	    exit(json_encode(array(
            'Type' => 'success',
            // 'Reply' => 'The image file ' . htmlspecialchars(basename($_FILES['file']['name'])) . ' has been uploaded on server successfuly!'
            'Reply' => 'Parts image has been uploaded on server successfuly!'
        )));
	} else{
	    exit(json_encode(array(
            'Type' => 'error',
            'Reply' => 'An error occured uploading the image file! Please try again.'
        )));
	}
?>