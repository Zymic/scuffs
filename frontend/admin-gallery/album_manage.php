<?php
	
	$id = $_GET['id'];
	
	if( albumExists($id) )
	{
		$album = getAlbum($id);
		$images = getAlbumItems($id);
		
		$total_images = count($images);
		
		$album_cover = $album['AlbumCover'];
		$album_cover_img = "css/images/album_cover.png";
		
		if( is_array($album_cover) )
		{
			$album_cover_img = $album_cover['DefaultThumbnail'];
		}
?>
<script type="text/javascript" src="js/album_manage.js"></script>
<script type="text/javascript" src="js/openframe.js"></script>
<script type="text/javascript" src="js/video_add.js"></script>
<script type="text/javascript" src="js/album_cover.js"></script>

<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<a href="#" class="image_cover radius" title="Set or change album cover">
				<img src="<?php echo $album_cover_img; ?>" alt="album_cover" width="60" height="51" />
			</a>
		</td>
		<td valign="top">
			<h1 class="album_edit">
				<strong><?php echo $album['AlbumName']; ?></strong> 
				<br />
				<a class="album_edit" href="?action=album&id=<?php echo $id . (isset($_GET['edit']) ? '' : '&edit'); ?>">Edit</a>
				<span>/ <span class="images_uploaded_num"><?php echo $total_images; ?></span> image<span class="plural"><?php echo $total_images != 1 ? 's' : ''; ?></span></span>
			</h1>		
		</td>
	</tr>
</table>

<div class="separator"></div>
<br />

<form action="" method="post" enctype="multipart/form-data" name="uploadImageForm" id="uploadImageForm">
	
	<table border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<input type="hidden" name="album_id" id="album_id" value="<?php echo $album['AlbumID']; ?>">
					<input type="file" name="upload_image" id="upload_image" class="button">
				</td>
				<td valign="top">
					<a href="#" class="add_video_button">Add Video</a>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="imagesQueue"></div>
</form>

<?php
	if( isset($_GET['edit']) )
	{
		include("edit_album.php");
	}
?>
<div class="clear"></div>

<form method="post" name="form2">
<div class="selection_action">
	<div>Selected items:</div>
	<input type="hidden" name="take_group_action" id="take_group_action" value="1" />
	<input type="hidden" name="group_action_type" id="group_action_type" value="none" />
	
	<input type="submit" name="delete" id="delete_btn" value="Delete" class="button confirm" />
	<input type="submit" name="rename" id="rename_btn" value="Rename" class="button" />
	<input type="button" name="change_album" id="change_album_btn" value="Move to another album" class="button" />
	
	<div class="move_to_another_album">
		<select name="album_id" class="change_album">
		<?php
			$albums = getAllAlbums();
			
			foreach($albums as $album)
			{
				?>
				<option<?php echo $_GET['id'] == $album['AlbumID'] ? ' selected' : ''; ?> value="<?php echo $album['AlbumID']; ?>"><?php echo $album['AlbumName']; ?></option>
				<?php
			}
		?>
		</select>
		
		<input type="submit" name="move_images" id="move_images_btn" value="Move album" class="button" />
	</div>
</div>
<?php
			?>
            <ul class="album_images">
            <?php
			
			foreach($images as $image)
			{
				$image_id = $image['ImageID'];
				$image_path = $image['ImagePath'];
				$video_url = $image['VideoURL'];
				$name = $image['Name'];
				
				$params = $image['Params'];
				
				$is_image = $image['Type'] == 'image' ? true : false;
				$is_video = $image['Type'] == "video" ? true : false;
				
				$thumbnail = dirname($image_path) . '/th_' . basename($image_path);
				
				// Video Options
				if( $is_video && !$image_path )
				{
					$thumbnail = $params['thumbnail_url'];
				}
				
				?>
                <li class="image radius<?php echo $is_video ? ' video' : ''; ?>" data-imageid="<?php echo $image['ImageID']; ?>">
                	<?php if( $is_video ){ ?>
                		<div class="video_icon"></div>
                	<?php } ?>
                	
					<img src="<?php echo $thumbnail; ?>?<?php echo mt_rand(10000,99999); ?>" width="110" height="95" alt="<?php echo addslashes($name); ?>" title="<?php echo addslashes($name); ?>" />
                    <div class="clear"></div>
                    
                    <div class="check_button">
                    	<label for="image_id_<?php echo $image_id; ?>" class="image_name<?php echo !$name ? ' noname' : ''; ?>" title="<?php echo $name; ?>"><?php echo $name ? $name : '(No name)'; ?></label>
                    	<input type="checkbox"<?php echo is_array($selected_images) && in_array($image_id, $selected_images) && !defined("IMAGE_NAMES_CHANGED") ? ' checked="checked"' : ''; ?> name="selected_images[]" class="image_checkbox" id="image_id_<?php echo $image_id; ?>" value="<?php echo $image_id; ?>" />
                    	<div class="clear"></div>
                    </div>
                    
                    <div class="image_options_dialog radius">
                    	
                    	<ul>
                    		<?php if( $is_image ){ ?>
                    		<li>
                    			<a href="admin.php?action=editimage&id=<?php echo $image_id; ?>" class="edit_image">Edit / Change</a>
                    		</li>
                    		<li>
                    			<a href="?action=cropimage&id=<?php echo $image_id; ?>" class="crop_image">Crop</a>
                    		</li>
                    		<?php } ?>
                    		
                    		<?php if( $is_video ){ ?>
                    		<li>
                    			<a href="admin.php?action=editimage&id=<?php echo $image_id; ?>" class="edit_video">Edit / Change</a>
                    		</li>
                    		<li>
                    			<a href="<?php echo $video_url; ?>" class="play_video" target="_blank">Play Video</a>
                    		</li>
                    		<?php } ?>
                    		
                    		<li>
                    			<a href="#" class="delete_image">Delete</a>
                    		</li>
                    	</ul>
                    	
                    </div>
                    
                    <?php
                    	if( defined("DO_RENAME") && in_array($image_id, $selected_images) )
                    	{
                    	?>
                    	<div class="rename_image">
                    		<input type="text" tabindex="1" name="rename_<?php echo $image_id; ?>" value="<?php echo htmlentities($name); ?>" placeholder="Set image name" />
                    	</div>
                    	<?php
                    	}
                    ?>
                </li>
                <?php
			}
			
			if( !count($images) )
			{
				?>
                <li class="noimages">
                	There are no images in this album
                </li>
                <?php
			}
			
			?>
            </ul>
            
            <?php
            	if( defined("DO_RENAME") )
            	{
            	?>
            		<div class="clear"></div>
            		
            		<div class="rename_button_env">
            			<input type="submit" name="rename_images" id="rename_images" value="Save Changes" class="button" />
            		</div>
            	<?php
            	}
            ?>
</form>
            <div class="clear"></div>
            
   			<div class="select_unselect_all_items<?php echo !count($images) ? ' hide' : ''; ?>">
   				<a href="#" rel="select">Select all</a>  <a href="#" rel="unselect">Select none</a>
   			</div>
       		
            
            <a href="admin.php" class="go_back">&laquo; Go back</a>
            <div class="separator"></div>
            <?php
	}
	else
	{
?>
<br /><br />
<div class="error">Album doesn't exists! Please go back.</div>
<?php
	}
?>
<div class="loader">Loading...</div>

<!-- Add Video Dialog -->
<div id="add_video_dialog">
	
	<form method="post">
		<label for="video_url">Enter Video URL:</label>
		<input type="text" name="video_url" id="video_url" />
		
		<button type="submit" name="add_video" id="add_video">Add Video</button>
	</form>
	
	<div class="video_load">
		Adding video...
	</div>
	
	<div class="video_error">
		Only videos from YouTube and Vimeo are accepted!
	</div>
	
	<div class="clear"></div>
	
	<span class="note">Note: Only videos from <strong>YouTube</strong> or <strong>Vimeo</strong> are accepted!</span>
</div>
<!-- End Of: Add Video Dialog -->