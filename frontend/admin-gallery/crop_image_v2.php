<?php
	$id = $_GET['id'];

	if( imageExists($id) )
	{
		$image = getImage($id);
		
		$image_id = $image['ImageID'];
		$name = $image['Name'];
		$description = $image['Description'];
		$upload_date = date("r", $image['UploadDate']);
		
		$album_id = $image['AlbumID'];
		$album = getAlbum($album_id);
		
		$image_path = $image['ImagePath'];
		$thumbnail_0 = dirname($image_path) . '/th_' . basename($image_path);
		$thumbnail_1 = dirname($image_path) . '/th1_' . basename($image_path);
		$thumbnail_2 = dirname($image_path) . '/th2_' . basename($image_path);
		$thumbnail_3 = dirname($image_path) . '/th3_' . basename($image_path);
		
		$imagesize = getimagesize($image_path);
		
		$cropping_image = "original";
		
		switch( $_GET['image_type'] )
		{
			case "thumbnail_0":
			case "thumbnail_1":
			case "thumbnail_2":
			case "thumbnail_3":
				$cropping_image = $_GET['image_type'];
				break;
		}
		
		?>
		<h1>Crop Image</h1>
		<h3><?php echo $album['AlbumName']; ?> - <a href="?action=album&id=<?php echo $album_id; ?>" class="disabled_link">Back to album</a></h3>
		<br />
		
		Select Image to Crop:
		<ul class="image_types">
			<li<?php echo $cropping_image == "original" ? ' class="active"' : ''; ?>><a href="?action=cropimage&id=<?php echo $image_id; ?>&image_type=original" data-imagesrc="<?php echo $image_path; ?>?<?php echo mt_rand(10000,99990); ?>">Original Image</a></li>
			<?php
			
			if( file_exists($thumbnail_0) )
			{
				?>
				<li<?php echo $cropping_image == "thumbnail_0" ? ' class="active"' : ''; ?>><a href="?action=cropimage&id=<?php echo $image_id; ?>&image_type=thumbnail_0" data-imagesrc="<?php echo $thumbnail_0; ?>?<?php echo mt_rand(10000,99990); ?>">Thumbnail</a></li>
				<?php
			}
			
			if( file_exists($thumbnail_1) )
			{
				?>
				<li<?php echo $cropping_image == "thumbnail_1" ? ' class="active"' : ''; ?>><a href="?action=cropimage&id=<?php echo $image_id; ?>&image_type=thumbnail_1" data-imagesrc="<?php echo $thumbnail_1; ?>?<?php echo mt_rand(10000,99990); ?>">IMG1</a></li>
				<?php
			}
			
			if( file_exists($thumbnail_2) )
			{
				?>
				<li<?php echo $cropping_image == "thumbnail_2" ? ' class="active"' : ''; ?>><a href="?action=cropimage&id=<?php echo $image_id; ?>&image_type=thumbnail_2" data-imagesrc="<?php echo $thumbnail_2; ?>?<?php echo mt_rand(10000,99990); ?>">IMG2</a></li>
				<?php
			}
			
			if( file_exists($thumbnail_3) )
			{
				?>
				<li<?php echo $cropping_image == "thumbnail_3" ? ' class="active"' : ''; ?>><a href="?action=cropimage&id=<?php echo $image_id; ?>&image_type=thumbnail_3" data-imagesrc="<?php echo $thumbnail_3; ?>?<?php echo mt_rand(10000,99990); ?>">IMG3</a></li>
				<?php
			}
			
			?>
		</ul>
		<?php
		
		
		// Cropping Original Image
		
		if( $cropping_image == "original" )
		{
			include_once("crop_image_original.php");
		}
		else
		if( $cropping_image == "thumbnail_0" )
		{
			$thumbnail_type = 0;
			include_once("crop_image_thumbnail.php");
		}
		else
		if( $cropping_image == "thumbnail_1" )
		{
			$thumbnail_type = 1;
			include_once("crop_image_thumbnail.php");
		}
		else
		if( $cropping_image == "thumbnail_2" )
		{
			$thumbnail_type = 2;
			include_once("crop_image_thumbnail.php");
		}
		else
		if( $cropping_image == "thumbnail_3" )
		{
			$thumbnail_type = 3;
			include_once("crop_image_thumbnail.php");
		}
	}
?>