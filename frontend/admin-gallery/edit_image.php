<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/editimage.js"></script>
<?php
	$id = $_GET['id'];

	if( imageExists($id) )
	{
		$video = getImage($id);
		
		$image_id = $video['ImageID'];
		$name = $video['Name'];
		$description = $video['Description'];
		$upload_date = date("r", $video['UploadDate']);
		
		$album_id = $video['AlbumID'];
		$album = getAlbum($album_id);
		
		// Video URL
		$video_url = $video['VideoURL'];
		
		
		$image_path = $video['ImagePath'];
		$thumbnail_0 = dirname($image_path) . '/th_' . basename($image_path);
		$thumbnail_1 = dirname($image_path) . '/th1_' . basename($image_path);
		$thumbnail_2 = dirname($image_path) . '/th2_' . basename($image_path);
		$thumbnail_3 = dirname($image_path) . '/th3_' . basename($image_path);
		
		$imagesize = getimagesize($image_path);
		
		// Type
		$is_image = $video['Type'] == "image" ? true : false;
		$is_video = $video['Type'] == "video" ? true : false;
		
		$type_name = $is_video ? "Video" : "Image";
		
		
		// Has Image Or Not
		$has_image = file_exists($image_path) ? true : false;
		
		// Params
		$params = $video['Params'];
		
		
		if( $is_video && !$has_image )
		{
			$image_path = $params['thumbnail_url'];
		}
?>
<h1>Edit <?php echo $type_name; ?></h1>
<h3><?php echo $album['AlbumName']; ?> - <a href="?action=album&id=<?php echo $album_id; ?>" class="disabled_link">Back to album</a></h3>
<br />

<ul class="image_types">
	<li class="active"><a href="#" data-imagesrc="<?php echo $image_path; ?>?<?php echo mt_rand(10000,99990); ?>"><?php echo !$has_image ? 'External Thumbnail' : 'Original Image'; ?></a></li>
	<?php
	
	if( file_exists($thumbnail_0) )
	{
		?>
		<li><a href="#0" data-imagesrc="<?php echo $thumbnail_0; ?>?<?php echo mt_rand(10000,99990); ?>">Thumbnail</a></li>
		<?php
	}
	
	if( file_exists($thumbnail_1) )
	{
		?>
		<li><a href="#1" data-imagesrc="<?php echo $thumbnail_1; ?>?<?php echo mt_rand(10000,99990); ?>">IMG1</a></li>
		<?php
	}
	
	if( file_exists($thumbnail_2) )
	{
		?>
		<li><a href="#2" data-imagesrc="<?php echo $thumbnail_2; ?>?<?php echo mt_rand(10000,99990); ?>">IMG2</a></li>
		<?php
	}
	
	if( file_exists($thumbnail_3) )
	{
		?>
		<li><a href="#3" data-imagesrc="<?php echo $thumbnail_3; ?>?<?php echo mt_rand(10000,99990); ?>">IMG3</a></li>
		<?php
	}
	
	?>
</ul>
		
<div class="clear"></div>

<div class="view_image radius">
	<img src="<?php echo $image_path; ?>?<?php echo mt_rand(10000,99990); ?>" width="<?php echo $imagesize[0]; ?>" width="<?php echo $imagesize[0]; ?>" height="<?php echo $imagesize[1]; ?>" />
</div>

<?php
	if( $is_video && !$has_image )
	{
	?>
	<p class="warning">This video currently uses default thumbnail provided from the site of the video. You are recommended to upload an image as video cover!</p>
	<?php
	}
?>
        
  <div class="clear"></div>
        
        <br />
        <h3><?php echo $type_name; ?> Options</h3>
        
        <form name="form1" method="post" action="" class="image_options_form radius black">
			<input name="image_id" type="hidden" value="<?php echo $id; ?>">
			
			
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<td width="150">
							<label for="name"><?php echo $type_name; ?> Name:</label>
						</td>
						<td>
							<input type="text" name="name" id="name" value="<?php echo $name; ?>" class="text_input" />
						</td>
					</tr>
					
					<?php if( $is_video ){ ?>
					<tr>
						<td width="150">
							<label for="name">Video URL:</label>
						</td>
						<td>
							<input type="text" name="video_url" id="video_url" value="<?php echo $video_url; ?>" class="text_input" />
						</td>
					</tr>
					<?php } ?>
					
					<tr>
						<td valign="top">
							<label for="description">Description:</label>
						</td>
						<td>
							<textarea name="description" id="description" class="text_input" rows="4"><?php echo $description; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Upload Date:</label>
						</td>
						<td>
							<input type="text" name="upload_date" id="upload_date" value="<?php echo $upload_date; ?>" readonly="readonly" class="text_input_readonly" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" class="save_changes" name="change_img_name" id="change_img_name" value="Save changes">
						</td>
					</tr>
					<?php if( $has_image ){ ?>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Other Settings:</td>
						<td class="padding">
							<a href="?action=cropimage&id=<?php echo $image_id; ?>" class="crop_image bold" style="font-size:11px;">Crop Image</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		
		
        <br />
        <h3>
        <?php
        	if( $has_image )
        	{ 
        	?>
        	Switch/Replace Image
        	<?php
        	}
        	else
        	{
        	?>
        	Assign Image to this Video
        	<?php
        	}
        ?></h3>
        
        <form name="form1" method="post" action="?action=editimage&id=<?php echo $image_id; ?>&album_id=<?php echo $album_id; ?>" class="image_options_form radius" enctype="multipart/form-data">
        	<table border="0" cellpadding="0" cellspacing="0">
        		<tbody>
        			<tr>
        				<td>
        					<input type="file" name="new_image" id="new_image" />
        				</td>
        				<td>
        					&nbsp;<input type="submit" name="switch_image" id="switch_image" value="Upload Image">
        				</td>
        			</tr>
        		</tbody>
        	</table>
        </form>
    
        <?php
	}
	else
	{
		?>
<div class="error">Image doesn't exists! Please go back.</div>
<?php
	}
?>