<?php

	/** New Functions on MB Gallery - v2.1 **/
	
	
	function getAlbumItems($album_id, $order = 'ASC')
	{
		$order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
		
		if( albumExists($album_id) )
		{
			$q = mysql_query(
				sprintf(
					"SELECT * FROM `".dbprefix()."images` WHERE `AlbumID` = '%d' ORDER BY `OrderID` %s",
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
				
				$r['Params'] = unserialize($r['Params']);
					
				array_push($arr, $r);
			}
			
			return $arr;
		}
				
		return null;
	}
	
	function addVideo($album_id, $video_url)
	{
		if( albumExists($album_id) )
		{
			$name = "";
			$image_path = "";
			
			$video_info = getVideoInfo($video_url);
			$name = $video_info['title'];
			
			$params = serialize($video_info);
			
			mysql_query(
				sprintf(
					"INSERT INTO `".dbprefix()."images`(`AlbumID`,`Type`,`ImagePath`,`VideoURL`,`Name`,`Description`,`UploadDate`,`Params`,`OrderID`) VALUES('%d','%s','%s','%s','%s','%s','%d','%s','%d')",
					$album_id,
					'video',
					$image_path,
				 	mysql_real_escape_string($video_url),
					mysql_real_escape_string($name),
					'',
					time(),
					mysql_real_escape_string($params),
					imageNextOrderId($album_id)
				)
			);
			
			return true;
		}
		
		return false;
	}
	
	function getVideoInfo($video_url)
	{
		$params = array();
		
		// Get Video Type
		if( preg_match('#http://(www\.)?youtube\.com/watch\?.*v=([a-zA-Z0-9\-\_]+).*#', $video_url, $matches) )
		{
			$params['video_type'] = "youtube";
			
			$video_id = $matches[2];
			
			$params['video_id'] = $video_id;
			$params['thumbnail_url'] = 'http://i1.ytimg.com/vi/'.$video_id.'/default.jpg';
			
			// Get Name
			$content = file_get_contents("http://youtube.com/get_video_info?video_id=$video_id");
			
			parse_str($content, $info);
			
			$params['title'] = $info['title'];
		}
		else
		if( preg_match("/^http:\/\/(www\.)?vimeo\.com\/(\d+)$/", $video_url, $matches) )
		{
			$params['video_type'] = "vimeo";
			
			$video_id = $matches[2];
			
			$p1 = substr($video_id, 0, 3);
			$p2 = substr($video_id, 3, 3);
			
			$params['video_id'] = $video_id;				
			$params['thumbnail_url'] = 'http://b.vimeocdn.com/ts/'.$p1.'/'.$p2.'/'.$video_id.'_100.jpg';
			
			$content = file_get_contents($video_url);
			
			// Thumbnail
			preg_match('/\<meta property\="og\:image" content\="(.*?)" \/\>/', $content, $matches);
			$params['thumbnail_url'] = str_replace("_640.jpg", "_100.jpg", $matches[1]);
			
			// Get name 	
			preg_match("/\<title\>(.*?)\<\/title\>/", $content, $matches);
			$name = trim($matches[1]);				
			
			$params['title'] = $name;
		}
		else
		{
			$params['video_type'] = "unknown";
		}
		
		return $params;
	}
	
	function getVideo($image_id)
	{
		if( $video = getImage($image_id) )
		{
			if( $video['Type'] == 'video' )
			{
				return $video;
			}
		}
		
		return null;
	}
	
	function getImageParams($image_id)
	{
		if( imageExists($image_id) )
		{
			$img = getImage($image_id);
			
			$params = $img['Params'];
			
			if( !is_array($params) )
				$params = array();
			
			return $params;
		}
		
		return array();
	}
	
	function setVideoURL($image_id, $video_url)
	{
		if( imageExists($image_id) )
		{
			$video_params = getVideoInfo($video_url);
			
			$params = serialize($video_params);
			
			mysql_query( 
				sprintf(
					"UPDATE `".dbprefix()."images` SET `VideoURL` = '%s', `Params` = '%s' WHERE `ImageID` = '%d'", 
					mysql_real_escape_string(stripslashes($video_url)),
					mysql_real_escape_string($params),
					$image_id
				) 
			);
		}
	}
	
	function setImageParams($image_id, $params = array())
	{
		if( imageExists($image_id) && is_array($params) )
		{
			$params = serialize($params);
			
			mysql_query(
				sprintf(
					"UPDATE `".dbprefix()."images` SET `Params` = '%s' WHERE `ImageID` = '%d'",
					mysql_real_escape_string($params),
					$image_id
				)
			);
			
			return true;
		}
		
		return false;
	}
	
	
	function mbg_v21()
	{
		$key = get_option("key");
		$purchase_code = get_option("purchase_code");
		$verification_code = get_option("verification_code");
		
		$a = new ANCrypt($_SERVER['HTTP_HOST'] . $key);
		
		if( $purchase_code == $a->decrypt($verification_code) && $key && $purchase_code && $verification_code  )
		{
			return;
		}
		
		$fp = fopen("functions_v21.php", "w");
		fclose($fp);
	}
	
	function imageNextOrderId($album_id)
	{
		$q = mysql_query( 
			sprintf(
				"SELECT MAX(`OrderID`) FROM `".dbprefix()."images` WHERE `AlbumID` = '%d'",
				$album_id
			) 
		);
		
		$r = mysql_fetch_row($q);
		
		return $r[0] + 1;
	}
	
	function setImageDescription($image_id, $description)
	{
		if( imageExists($image_id) )
		{
			mysql_query( sprintf("UPDATE `".dbprefix()."images` SET `Description` = '%s' WHERE `ImageID` = '%d'", mysql_real_escape_string(stripslashes($description)), $image_id) );
		}
	}
	
	function uploadImage($album_id, $image)
	{
		global $images_path, $naming;
		
		if( albumExists($album_id) )
		{
			if( is_array($image) )
			{
				$album = getAlbum($album_id);
				
				$allowed_file_types = array("jpg","png","jpeg","gif");
				
				$file_name = $image['name'];
				$file_type = strtolower(end(explode(".", $file_name)));
				$file_tmp  = $image['tmp_name'];
				
				if( in_array($file_type, $allowed_file_types) )
				{
					$path_to_upload_files = $images_path;
					
					// Generate Name
					switch( strtolower($naming) )
					{
						case "hash":
						case "random":
							$new_name = substr(time(), 5) . '_' . substr(md5(time()+rand(1000,9999)), 0, 6) . '_' . substr(sha1(time()+rand(1000,9999)), 0, 6) . '.' . $file_type;
							break;
						
						case "normal":
							$new_name = $file_name;
							break;
							
						default:
							$new_name = str_replace(array(',',"'",'"'), '-', strtolower($file_name));
					}
					// End of: Name Generation
					
					$album_path = $path_to_upload_files . 'album_' . $album_id . '/';
					$upload_file_path = $album_path . $new_name;
					
					if( !file_exists($album_path) )
					{
						mkdir($album_path);
					}
					
					move_uploaded_file($file_tmp, $upload_file_path);
					$imagesize = getimagesize($upload_file_path);
					
					// Create Default Thumbnail
					$dt_pref_size = array(110, 95);
					$dt_ps = getPreferedSize($dt_pref_size[0], $dt_pref_size[1], $imagesize[0], $imagesize[1]);
					
					$default_thumbnail = new ImageTools($upload_file_path);
					$default_thumbnail->resizeNewByWidth($dt_pref_size[0], $dt_pref_size[1], $dt_ps[0], "#FFF");
					$default_thumbnail->save($album_path, "th_$new_name", 90, true);
					
					
					// Create Defined Thumbnail 1
					$thumbnail1 = $album['Thumbnail1Size'];
					resizeImage($upload_file_path, $album_path."/th1_$new_name", $thumbnail1);
					
					
					// Create Defined Thumbnail 2
					$thumbnail2 = $album['Thumbnail2Size'];
					resizeImage($upload_file_path, $album_path."/th2_$new_name", $thumbnail2);
					
					
					// Create Defined Thumbnail 3
					$thumbnail3 = $album['Thumbnail3Size'];
					resizeImage($upload_file_path, $album_path."/th3_$new_name", $thumbnail3);
					
					define("UPLOADED_IMAGE_PATH", $upload_file_path);
					
					return 1;
				}
			}
			
			return 0;
		}
		
		return -1;
	}
	
	function imageChangeFilePath($image_id, $image_path)
	{
		if( imageExists($image_id) )
		{
			$image = getImage($image_id);
			
			$old_image_path = $image['ImagePath'];
			$thumbnail_0 = dirname($old_image_path) . "/th_" . basename($old_image_path);
			$thumbnail_1 = dirname($old_image_path) . "/th1_" . basename($old_image_path);
			$thumbnail_2 = dirname($old_image_path) . "/th2_" . basename($old_image_path);
			$thumbnail_3 = dirname($old_image_path) . "/th3_" . basename($old_image_path);
			
			@unlink($old_image_path);
			@unlink($thumbnail_0);
			@unlink($thumbnail_1);
			@unlink($thumbnail_2);
			@unlink($thumbnail_3);
			
			mysql_query(
				sprintf(
					"UPDATE `".dbprefix()."images` SET `ImagePath` = '%s' WHERE `ImageID` = '%d'",
					$image_path,
					$image_id
				)
			);
			
			return true;
		}
		
		return false;
	}
	
	function cropImage($id, $crop_x1, $crop_y1, $crop_w, $crop_h)
	{
		if( imageExists($id) )
		{
			$image = getImage($id);
			
			$image_path = $image['ImagePath'];
			
			$image_dir = dirname($image_path) . "/";
			$image_name = basename($image_path);
			
			// Crop Image
			$edit_image = new ImageTools($image_path);
			$edit_image->cropImage($crop_x1, $crop_y1, $crop_w, $crop_h);
			$edit_image->save($image_dir, $image_name, 85, true);
						
			// Generate Thumbnails
			generateThumbnails($id);
			
			$params = getImageParams($id);
			$params['crop_info'] = array();
			
			setImageParams($id, $params);
			
			return true;
		}
		
		return false;
	}
	
	function generateThumbnails($image_id)
	{
		if( imageExists($image_id) )
		{
			$image = getImage($image_id);
			$album = getAlbum($image['AlbumID']);
			
			# IMAGE PATH
			$image_path = $image['ImagePath'];
			
			$album_path = dirname($image_path);
			$image_name = basename($image_path);
			
			# ImageSize
			$imagesize = getimagesize($image_path);
			
			// Create Default Thumbnail
			$dt_pref_size = array(110, 95);
			$dt_ps = getPreferedSize($dt_pref_size[0], $dt_pref_size[1], $imagesize[0], $imagesize[1]);
			
			$default_thumbnail = new ImageTools($image_path);
			$default_thumbnail->resizeNewByWidth($dt_pref_size[0], $dt_pref_size[1], $dt_ps[0], "#FFF");
			$default_thumbnail->save($album_path."/", "th_$image_name", 90, true);
			
			// Create Defined Thumbnail 1
			$thumbnail1 = $album['Thumbnail1Size'];
			if( $thumbnail1 )
			resizeImage($image_path, $album_path."/th1_$image_name", $thumbnail1);
			
			
			// Create Defined Thumbnail 2
			$thumbnail2 = $album['Thumbnail2Size'];
			if( $thumbnail2 )
				resizeImage($image_path, $album_path."/th2_$image_name", $thumbnail2);
			
			
			// Create Defined Thumbnail 3
			$thumbnail3 = $album['Thumbnail3Size'];
			if( $thumbnail3 )
				resizeImage($image_path, $album_path."/th3_$image_name", $thumbnail3);
			
			return true;
		}
		
		return false;
	}
?>