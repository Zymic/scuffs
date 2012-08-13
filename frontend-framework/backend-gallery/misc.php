<?php

	// Check if its a legitimate request (a little security)
	if( !defined('ADMIN_AREA') )
		die("You can't access this file!");
		
	// Delete
	if( $id = $_GET['deletealbum'] )
	{
		if( deleteAlbum($id) )
		{
			define('_SUCCESS_', 'Album has been deleted successfully.');
		}
		else
		{
			define('_ERROR_', "Album doesn't exists or it has been deleted before!");
		}
	}
	
	// Move Up
	if( $id = $_GET['moveup'] )
	{
		moveAlbumUP($id);
	}
	
	// Move Down
	if( $id = $_GET['movedown'] )
	{
		moveAlbumDOWN($id);
	}
	
	// Create New Album
	if( isset($_POST['create_album']) )
	{
		$name = mysql_real_escape_string($_POST['album_name']);
		$description = mysql_real_escape_string($_POST['description']);
		
		$thumbnail1 = $_POST['thumbnail1'];
		$thumbnail1fit = $_POST['thumbnail1fit'];
		$size1W = strtolower($_POST['size1W']);
		$size1H = strtolower($_POST['size1H']);
		
		$thumbnail2 = $_POST['thumbnail2'];
		$thumbnail2fit = $_POST['thumbnail2fit'];
		$size2W = strtolower($_POST['size2W']);
		$size2H = strtolower($_POST['size2H']);
		
		$thumbnail3 = $_POST['thumbnail3'];
		$thumbnail3fit = $_POST['thumbnail3fit'];
		$size3W = strtolower($_POST['size3W']);
		$size3H = strtolower($_POST['size3H']);
		
		if( $name )
		{
			$date_created = time();
			$order_id = time();
			$q = mysql_query("INSERT INTO `".dbprefix()."albums`(`AlbumName`,`Description`,`DateCreated`,`OrderID`) VALUES('$name','$description','$date_created',$order_id)") or die(mysql_error());
			
			define("_SUCCESS_", "Album created successfully.");
			$last_id = mysql_insert_id();
			
			// Thumbnail 1
			$size1_x = $size1W . "x" . $size1H . "x" . $thumbnail1fit;
			mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail1Size` = '$size1_x' WHERE `AlbumID` = '$last_id'");
			
			
			// Thumbnail 2
			$size2_x = $size2W . "x" . $size2H . "x" . $thumbnail2fit;
			mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail2Size` = '$size2_x' WHERE `AlbumID` = '$last_id'");
			
			
			// Thumbnail 3
			$size3_x = $size3W . "x" . $size3H . "x" . $thumbnail3fit;
			mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail3Size` = '$size3_x' WHERE `AlbumID` = '$last_id'");
		}
	}
	
	
	// Crop Thumbnail
	if( isset($_GET['crop_thumbnail']) && $_POST['image_id'] )
	{
		$image_id 		= $_POST['image_id'];
		$thumbnail_path = base64_decode($_GET['crop_thumbnail']);
		
		// Crop Options
		$width 	= $_POST['w'];
		$height = $_POST['h'];
		
		$img_w 	= $_POST['img_w'];
		
		$x 		= $_POST['x'];
		$y 		= $_POST['y'];
		
		$th_id 	= $_POST['th_id'];
		
		
		if( imageExists($image_id) && file_exists($thumbnail_path) )
		{
			$original_image = getImage($image_id);
			$original_image_path = $original_image['ImagePath'];
			
			$thumbnail_dir = dirname($thumbnail_path) . "/";
			$thumbnail_name = basename($thumbnail_path);
			
			$img = new ImageTools($original_image_path);
			$img->cropImage($x, $y, $width, $height);
			$img->resizeWidth($img_w);
			$img->save($thumbnail_dir, $thumbnail_name, 90, true);
			
			$params = getImageParams($image_id);
			
			$params['crop_info']['th_id_' . $th_id] = array("x" => $x, "y" => $y, "width" => $width, "height" => $height);
			
			setImageParams($image_id, $params);
			
			echo 1;
		}
		else
		{
			echo 0;
		}
		mysql_close();
		exit;
	}
	
	// Switch Image
	if( $switch_image = $_FILES['new_image'] )
	{
		switch( uploadImage($_GET['album_id'], $switch_image) )
		{
			case 1:
				imageChangeFilePath($_GET['id'], UPLOADED_IMAGE_PATH);
				define('_SUCCESS_', 'Image has been switched');
				break;
			
			case 0:
				define("_ERROR_", "Invalid Image Type!");
				break;
			
			case -1:
				define("_ERROR_", "Album doesn't exists!");
		}
	}

	 	
	// Upload Image Files
	if( $upload_image = $_FILES['upload_image'] )
	{
		$album_id = $_GET['album_id'];
		
		switch( uploadImage($album_id, $upload_image) )
		{
			case 1:
				addImage($album_id, UPLOADED_IMAGE_PATH);
				
				$last_id = mysql_insert_id();
				
				$image = getImage($last_id);
				$image['errors'] = false;
				$image['thumbnailUrl'] = dirname($image['ImagePath']) . '/th_' . basename($image['ImagePath']);
				
				$json = json_encode($image);
				
				echo $json;
				break;
			
			case 0:
				echo json_encode( array("errors" => "InvalidFileType") );
				break;
				
			case -1:
				echo json_encode( array("errors" => "AlbumNotExists") );
				break;
		}
		
		@mysql_close($connect);
		exit;
	}
	
	// Add Video
	if( $_GET['add_video'] && $_POST['video_url'] )
	{
		$album_id = $_GET['add_video'];
		$video_url = $_POST['video_url'];
		
		$output = array();
		
		if( albumExists($album_id) )
		{
			$output['errors'] = false;
			
			addVideo($album_id, $video_url);
			
			$video = getVideo(mysql_insert_id());
			
			$video['Params'] = $video['Params'];
			
			$output['video_info'] = $video;
		}
		else
		{
			$output['errors'] = true;
			$output['error_msg'] = 'Album doesnt exists!';
		}
		
		echo json_encode($output);
		exit;
	}
	
	// Change Order of Images
	if( $_GET['images_new_order'] && $_GET['order_string'] )
	{
		$album_id = $_GET['images_new_order'];
		$order_string = $_GET['order_string'];
		
		$ids = explode(",", $order_string);
		
		foreach($ids as $id_str)
		{
			$order_row = explode("=", $id_str);
			
			$order_id = $order_row[0];
			$image_id = $order_row[1];
			
			mysql_query("UPDATE `".dbprefix()."images` SET `OrderID` = '$order_id' WHERE `ImageID` = '$image_id' AND `AlbumID` = '$album_id'");
		}
		
		@mysql_close($connect);
		exit;
	}
	
	// Change Order of Albums
	if( $_GET['albums_new_order'] && $_GET['order_string'] )
	{
		$order_string = $_GET['order_string'];
		
		$ids = explode(",", $order_string);
		
		foreach($ids as $i => $album_id)
		{
			mysql_query("UPDATE `".dbprefix()."albums` SET `OrderID` = '$i' WHERE `AlbumID` = '$album_id'");
		}
		
		@mysql_close($connect);
		exit;
	}
	
	// Delete Image from Album
	if( $image_id = $_GET['deleteimageid'] )
	{
		deleteImage($image_id);
		
		@mysql_close($connect);
		exit;
	}

	// Change Image Details
	if( isset($_POST['change_img_name']) )
	{
		$image_id = $_POST['image_id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		if( $image_id )
		{
			setImageName($image_id, $name);
			setImageDescription($image_id, $description);
			
			$item = getImage($image_id);
			
			if( $item['Type'] == "video"  )
			{
				$video_url = $_POST['video_url'];
				
				setVideoURL($image_id, $video_url);
				define("_SUCCESS_", "Video details have been changed!");
			}
			else
			{
				define("_SUCCESS_", "Image details have been changed!");
			}
		}
	}
	
	// Edit Album Info
	if( isset($_POST['edit_album']) )
	{
		$album_id = $_GET['id'];
		$name = $_POST['album_name'];
		$description = $_POST['description'];
		
		$thumbnail1 = $_POST['thumbnail1'];
		$thumbnail1fit = $_POST['thumbnail1fit'];
		$size1W = $_POST['size1W'];
		$size1H = $_POST['size1H'];
		$size1 = $size1W . "x" . $size1H;
		$size1 = $thumbnail1 ? $size1 : null;
		
		if( $size1 )
			$size1 = $size1 . "x" . $thumbnail1fit;
		
		
		$thumbnail2 = $_POST['thumbnail2'];
		$thumbnail2fit = $_POST['thumbnail2fit'];
		$size2W = $_POST['size2W'];
		$size2H = $_POST['size2H'];
		$size2 = $size2W . "x" . $size2H;
		$size2 = $thumbnail2 ? $size2 : null;
		
		if( $size2 )
			$size2 = $size2 . "x" . $thumbnail2fit;
		
		
		$thumbnail3 = $_POST['thumbnail3'];
		$thumbnail3fit = $_POST['thumbnail3fit'];
		$size3W = $_POST['size3W'];
		$size3H = $_POST['size3H'];
		$size3 = $size3W . "x" . $size3H;
		$size3 = $thumbnail3 ? $size3 : null;
		
		if( $size3 )
			$size3 = $size3 . "x" . $thumbnail3fit;
		
		editAlbum($album_id, $name, $description, $size1, $size2, $size3);
		
		### v2.1 ###
		$category = $_POST['category'];
		setAlbumCategory($album_id, $category);
		
		define("_SUCCESS_", "Album edited successfully");
	}
	
	// Save Config File
	if( isset($_POST['settings_save_changes']) )
	{
		$admin_username = $_POST['admin_username'];
		$admin_password = $_POST['admin_password'];
		
		$gallery_title = $_POST['gallery_title'];
		$naming = $_POST['naming'];
		
		$thumbnail1sizeW = strtolower($_POST['thumbnail1sizeW']);
		$thumbnail1sizeH = strtolower($_POST['thumbnail1sizeH']);
		$thumbnail1fit = $_POST['thumbnail1fit'];
		
		$thumbnail2sizeW = strtolower($_POST['thumbnail2sizeW']);
		$thumbnail2sizeH = strtolower($_POST['thumbnail2sizeH']);
		$thumbnail2fit = $_POST['thumbnail2fit'];
		
		$thumbnail3sizeW = strtolower($_POST['thumbnail3sizeW']);
		$thumbnail3sizeH = strtolower($_POST['thumbnail3sizeH']);
		$thumbnail3fit = $_POST['thumbnail3fit'];
		
		if( !$thumbnail1sizeW && !$thumbnail1sizeH )
		{
			$thumbnail1size = "x";
		}
		else
		{
			$thumbnail1size = $thumbnail1sizeW . "x" . $thumbnail1sizeH . "x" . $thumbnail1fit;
		}
		
		if( !$thumbnail2sizeW && !$thumbnail2sizeH )
		{
			$thumbnail2size = "x";
		}
		else
		{
			$thumbnail2size = $thumbnail2sizeW . "x" . $thumbnail2sizeH . "x" . $thumbnail2fit;
		}
		
		if( !$thumbnail3sizeW && !$thumbnail3sizeH )
		{
			$thumbnail3size = "x";
		}
		else
		{
			$thumbnail3size = $thumbnail3sizeW . "x" . $thumbnail3sizeH . "x" . $thumbnail2fit;
		}
		
		// Start Updating Config
		if( $admin_username )
		{
			update_option("admin_username", $admin_username);
		}
		
		if( $admin_password )
		{
			update_option("admin_password", $admin_password);
		}
		
		if( $gallery_title )
		{
			update_option("title", $gallery_title);
		}
		
		update_option("naming", $naming);
		
		update_option("thumbnail_1_size", $thumbnail1size);
		update_option("thumbnail_2_size", $thumbnail2size);		
		update_option("thumbnail_3_size", $thumbnail3size);
		
		### v2.1 ###
		$category_select_type = $_POST['category_select_type'] == 'single' ? 'single' : 'multi';
		update_option("category_select_type", $category_select_type);
		
		
		// Get New Values
		$mbg_options = new MBG_Options();
		
		define("_SUCCESS_", "Settings have been saved.");
	}
	
	// Delete group of images
	if( $_POST['take_group_action'] && $_POST['group_action_type'] == "delete" )
	{
		$selected_images = $_POST['selected_images'];
		
		if( count($selected_images) )
		{
			$deleted_images = 0;
			
			foreach($selected_images as $image)
			{
				if( imageExists($image) )
				{
					deleteImage($image);
					$deleted_images++;
				}
			}
			
			if( $deleted_images > 0 )
			{
				define("_SUCCESS_", "$deleted_images image".($deleted_images > 1 ? 's' : '')." have been deleted successfully.");
			}
			else
			{
				define("_ERROR_", "Images have been already deleted.");
			}
		}
	}
	
	// Rename group of images
	if( $_POST['take_group_action'] && $_POST['group_action_type'] == "rename" )
	{
		$selected_images = $_POST['selected_images'];
		
		if( count($selected_images) )
		{
			define("DO_RENAME", true);
		}
	}
	
	// Rename group of images (ACTION)
	if( $_POST['take_group_action'] && $_POST['group_action_type'] == "do_rename" )
	{
		$selected_images = $_POST['selected_images'];
		
		if( count($selected_images) )
		{
			foreach($selected_images as $image)
			{
				if( imageExists($image) && isset($_POST['rename_'.$image]) )
				{
					$new_name = $_POST['rename_' . $image];
					setImageName($image, $new_name);
				}
			}
			
			define("_SUCCESS_", "Image names have been changed");
			define("IMAGE_NAMES_CHANGED", true);
		}
	}	
	
	// Move group of images
	if( $_POST['take_group_action'] && $_POST['group_action_type'] == "move" )
	{
		$album_id = $_POST['album_id'];
		
		if( albumExists($album_id) )
		{
			$images_moved = 0;
			$selected_images = $_POST['selected_images'];
			
			if( count($selected_images) )
			{
				foreach($selected_images as $image)
				{
					moveImage($image, $album_id);
					$images_moved++;
				}
			}
			
			define("_SUCCESS_", "Selected images ($images_moved) have been moved to the selected album. <a href=?action=album&id=$album_id>Go to album</a> &raquo;");
		}
		else
		{
			define("_ERROR_", "Cannot move images because the selected album currently doesn't exists!");
		}
	}
	
	// Crop Image
	if( isset($_POST['save_crop']) )
	{
		$crop_w = $_POST['crop_w'];
		$crop_h = $_POST['crop_h'];
		$crop_x1 = $_POST['crop_x1'];
		$crop_y1 = $_POST['crop_y1'];
		$crop_x2 = $_POST['crop_x2'];
		$crop_y2 = $_POST['crop_y2'];
		
		if( $crop_w && $crop_h && $crop_x1 < $crop_x2 && $crop_y1 < $crop_y2 )
		{
			$id = $_GET['id'];
			$image = getImage($id);
			
			$image_dir = dirname($image['ImagePath']) . "/";
			$image_name = basename($image['ImagePath']);
			
			if( cropImage($id, $crop_x1, $crop_y1, $crop_w, $crop_h) )
			{
				define("_SUCCESS_", "Image cropped successfully.");
			}
			else
			{
				define("_ERROR_", "Image doesn't exists!");
			}
			
			/*
			$edit_image = new ImageTools($image['ImagePath']);
			$edit_image->cropImage($crop_x1, $crop_y1, $crop_w, $crop_h);
			$edit_image->save($image_dir, $image_name, 85, true);
			$edit_image->destroy();*/
		}
	}
	
	// Set album cover image
	if( $_GET['album_id'] && $_GET['set_cover'] && $_GET['cover_type'] )
	{
		echo setAlbumCover($_GET['album_id'], $_GET['set_cover'], ($_GET['cover_type'] == "set" ? true : false)) ? 1 : 0;
		exit;
	}
	
	// Configure Theme Parameters
	if( isset($_POST['fe_save_settings']) )
	{
		$fe_url = $_POST['fe_url'];
		$fe_path = $_POST['fe_path'];
		$gallery_url = $_POST['gallery_url'];
		$gallery_path = $_POST['gallery_path'];
		
		if( file_exists($fe_path) && is_dir($fe_path) )
		{
			$last_feurl_char = substr($fe_url, -1);
			$last_fepath_char = substr($fe_path, -1);
			
			if( $last_feurl_char != "/" )
				$fe_url .= "/";
				
			if( $last_fepath_char != DIRECTORY_SEPARATOR )
				$fe_path .= DIRECTORY_SEPARATOR;
				
			
			$fe_config_file = $fe_path . "config.php";
			
			if( !file_exists($fe_config_file) )
			{
				define("_ERROR_", "The typed path $fe_path doesn't match with the gallery front-end framework!");
			}
			else
			{
				// Analyze the file
				include($fe_config_file);
				
				
				if( isset($mini_backend_gallery_url) && isset($mini_backend_gallery_path) )
				{
					// Write config file for Front-end framework
					$fp = fopen($fe_config_file, "r+");
					$new_file_string = "";
					
					while($line = fgets($fp))
					{
						if( preg_match('/\$mini_backend_gallery_url/', $line) )
						{
							$new_file_string .= "\t" . '$mini_backend_gallery_url = "'.addslashes($gallery_url).'";' . "\n";
						}
						else
						if( preg_match('/\$mini_backend_gallery_path/', $line) )
						{
							$new_file_string .= "\t" . '$mini_backend_gallery_path = "'.addslashes($gallery_path).'";' . "\n";
						}
						else
						{
							$new_file_string .= $line;
						}
					}
					
					fclose($fp);
					
					$fp = fopen($fe_config_file, "w");
					fwrite($fp, $new_file_string);
					fclose($fp);
					
					// Save settings for Front-end framework						
					update_option("mini_backend_gallery_url", $gallery_url);
					update_option("mini_backend_gallery_path", $gallery_path);						
					
					// Save settings for Back-end framework
					update_option("fe_installed", true);
					update_option("fe_url", $fe_url);
					update_option("fe_path", $fe_path);
					
					// Update Values
					$mbg_options = new MBG_Options();
				
					// Show Success Message
					define("_SUCCESS_", "Theme parameters have been saved.");
					define("_THEME_PARAMS_SAVED_", 1);
				}
				else
				{
					define("_ERROR_", "The typed path $fe_path doesn't match with the gallery front-end framework!");
				}
			}
		}
		else
			define("_ERROR_", "Front-end themes framework path doesn't exists!");
	}
	
	// Set theme
	if( isset($_POST['set_theme']) && $_POST['theme_name'] )
	{
		$theme_name = $_POST['theme_name'];
		
		$theme_path = $fe_path . "themes/$theme_name/";
		$fe_config = $fe_path . "config.php";
		
		if( file_exists($theme_path. "index.php") )
		{
			$constraints_file = $theme_path . "constraints.php";
			
			if( file_exists($constraints_file) )
			{
				include($constraints_file);
			}
			
			if( file_exists($fe_config) )
			{
				// Do the work for Front-end framework
				update_option("current_theme", $theme_name);
				
				// MBG change settings
				update_option("fe_theme_selected", true);
				update_option("current_theme", $theme_name);
				
				$fe_theme_selected = true;
			
				// Update Thumbnail Sizes (for theme)
				if( $THEME_THUMBNAIL1_SIZE != null )
				{
					update_option("thumbnail_1_size", $THEME_THUMBNAIL1_SIZE);
					
					// Save for already-created albums
					changeThumbailSize(1, $THEME_THUMBNAIL1_SIZE);
				}
				
				if( $THEME_THUMBNAIL2_SIZE != null )
				{
					update_option("thumbnail_2_size", $THEME_THUMBNAIL2_SIZE);
					
					// Save for already-created albums
					changeThumbailSize(2, $THEME_THUMBNAIL2_SIZE);
				}
				
				if( $THEME_THUMBNAIL3_SIZE != null )
				{
					update_option("thumbnail_3_size", $THEME_THUMBNAIL3_SIZE);
					
					// Save for already-created albums
					changeThumbailSize(3, $THEME_THUMBNAIL3_SIZE);
				}
				
				// Update Values
				$mbg_options = new MBG_Options();
				
				define("_SUCCESS_", "Theme installed successfully!");
			}
			else
			{
				define("_ERROR_", "Front-end themes framework config file is not writable!");
			}
		}
		else
		{
			define("_ERROR_", "Cannot install theme! It must have index.php file included.");
		}
	}
	
	// Disable/Unistall Theme
	if( $_GET['disable_theme'] && $fe_theme_selected == true )
	{
		$fe_config = $fe_path . "config.php";
		
		if( file_exists($fe_config) )
		{
			include($fe_config);
			
			// Do the work for Front-end framework
			update_option("current_theme", "");
			
			// MBG change settings
			update_option("fe_theme_selected", false);
			$fe_theme_selected = false;	
			
			// Update Values
			$mbg_options = new MBG_Options();
			
			define("_SUCCESS_", "Theme \"$current_theme\" has been disabled!");
		}
		else
		{
			define("_ERROR_", "Front-end config file doesn't exists!");
		}
	}
	
	// Logout
	if( isset($_GET['logout']) )
	{
		session_destroy();
		setcookie("token", "-");
		header("Location: index.php");
	}
	
	### V2.1 ###
	if( isset($_POST['create_category']) ) 
	{
		$name = $_POST['category_name'];
		$description = $_POST['category_description'];
		
		if( !empty($name) )
		{
			addCategory($name, $description);
			
			define("_SUCCESS_", "Category \"$name\" has been created.");
		}
	}
	
	if( isset($_POST['edit_category']) ) 
	{
		$id = $_POST['category_id'];
		$name = $_POST['category_name'];
		$description = $_POST['category_description'];
		
		$selected_albums = $_POST['selected_albums'];
		
		if( ($category = getCategory($id)) && !empty($name) )
		{
			editCategory($id, $name, $description);
			
			if( !is_array($selected_albums) )
			{
				$selected_albums = array();
			}
			
			$all_albums = getAlbumsForCategory($id);
			
			foreach($all_albums as $album)
			{
				$album_id = $album['AlbumID'];
				
				if( in_array($album_id, $selected_albums) )
				{
					assignAlbumToCategory($album_id, $id);
				}
				else
				{
					removeCategory($album_id, $id);
				}
			}
			
			define("_SUCCESS_", "Category \"$category[Name]\" has been modified.");
		}
	}
	
	if( $_GET['category_ordering'] )
	{
		$new_ordering = $_POST['new_ordering'];
		
		if( is_array($new_ordering) )
		{
			foreach($new_ordering as $i => $id)
			{
				$order_id = $i + 1;
				
				mysql_query(
					sprintf(
						"UPDATE `".dbprefix()."categories` SET `OrderID` = '%d' WHERE `CategoryID` = '%d'",
						$order_id,
						$id
					)
				);
			}
			
			exit;
		}
	}

	if( $cid = $_GET['delete_category'] )
	{
		if( is_numeric($cid) )
		{
			if( deleteCategory($cid) )
			{			
				define("_SUCCESS_", "Category has been deleted.");
			}
		}
	}	
?>