<?php

require '../../../../wp-blog-header.php';
require '../../../../wp-admin/includes/image.php';

if ( !empty($_FILES['commentImage']) ) {

	$validExts = array(
		'jpeg',
		'jpg',
		'gif',
		'png',
	);
	$validExts = '(' . implode($validExts, ')|(') . ')';
	if ( !preg_match('/'.$validExts.'\z/i', $_FILES['commentImage']['name']) ) {
		echo 'Error: The uploaded file must be an image.';
		exit;
	}

	$path = $commentImageUploader->uploadPath();
	$path = $path['dir'];
	@mkdir($path, 0777, true);

	$fileName = basename($_FILES['commentImage']['name']);
	$fileName = preg_replace('/[^a-zA-Z0-9\.]/', '', $fileName);
	$fileName = strtolower($fileName);
	$dest = $path . '/' . $fileName;

	$i=1;
	while ( file_exists($dest) ) {
		$newName = $i . '_' . $fileName;
		$dest = $path . '/' . $newName;
		$i++;
	}

	if ( @move_uploaded_file( $_FILES['commentImage']['tmp_name'], $dest ) ) {
		$option = get_option('commentImageUploader');
		// thumb
		$thumbImg = image_resize($dest, $option['tmaxwidth'], $option['tmaxheight']);
		if ( $thumbImg ) {
			$thumbImgName = dirname($dest) . '/t_' . basename($dest);
			rename($thumbImg, $thumbImgName);
		}

		// large
		$largeImg = image_resize($dest, $option['lmaxwidth'], $option['lmaxheight']);
		if ( $largeImg ) {
			$largeImgName = dirname($dest) . '/l_' . basename($dest);
			rename($largeImg, $largeImgName);
		}

		echo basename($dest);
	} else {
		echo 'Error: The file could not be saved.';
	}
}

?>
