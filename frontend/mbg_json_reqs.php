<?php

	
	/* Accept JSON requests from gallery theme */
	
	// Get Album Info and Images
	if( $album_id = $_GET['get_album'] )
	{
		$album_info = array();
		$album_info['album_exists'] = false;
		
		if( albumExists($album_id) )
		{
			$album_info['album_exists'] = true;
			
			$album = getAlbum($album_id);
			
			$album_info['AlbumID'] = $album['AlbumID'];
			$album_info['AlbumName'] = $album['AlbumName'];
			$album_info['AlbumDescription'] = $album['Description'];
			$album_info['DateCreated'] = date("d F Y / H:i", $album['DateCreated']);
			
			$album_info['hasImages'] = false;
			
			$images = getAlbumItems($album_id);
			
			$default_video_width = get_option('default_video_width');
			$default_video_height = get_option('default_video_height');
			
			if( ($total_images = count($images)) > 0 )
			{
				$album_info['hasImages'] = true;
				$album_info['imagesCount'] = $total_images;
				$album_info['UploadsURL'] = $mini_backend_gallery_url;
				
				
				$images_arr = array();
				
				foreach($images as $image)
				{
					$image_entry = array("ImageID" => $image['ImageID'], "ImageName" => $image['Name'], "UploadDate" => $image['UploadDate']);
					
					$is_video = $image['Type'] == 'video';
					$has_image = $image['ImagePath'] ? true : false;
					
					$image_path  = $image['ImagePath'];
					$default_thumbnail = $image['DefaultThumbnail'];
					$thumbnail_1 = $image['Thumbnail1'];
					$thumbnail_2 = $image['Thumbnail2'];
					$thumbnail_3 = $image['Thumbnail3'];
					
					$image_entry['ImageURL'] = $image_path;
					$image_entry['ThumbnailURL'] = $default_thumbnail;
					$image_entry['Thumbnail1'] = $thumbnail_1;
					$image_entry['Thumbnail2'] = $thumbnail_2;
					$image_entry['Thumbnail3'] = $thumbnail_3;
					
					$image_entry['isVideo'] = $image['Type'] == 'video' ? 1 : 0;
					
					if( $is_video )
					{
						$image_entry['VideoID'] = $image['Params']['video_id'];
						$image_entry['VideoURL'] = $image['VideoURL'];
						$image_entry['VideoType'] = $image['Params']['video_type'];
						
						if( !$has_image )
						{
							$image_entry['ThumbnailURL'] = $image['Params']['thumbnail_url'];
							$image_entry['ImageURL'] = $image['Params']['thumbnail_url'];
						}
						
						switch( $image['Params']['video_type'] )
						{
							case "vimeo":
								$image_entry['VideoWidth'] = $default_video_width;
								$image_entry['VideoHeight'] = $default_video_height;
								break;
								
							case "youtube":
								$image_entry['VideoWidth'] = $default_video_width;
								$image_entry['VideoHeight'] = $default_video_height;
								break;
						}
					}
					
					$image_entry['Params'] = $image['Params'];
					
					
					array_push($images_arr, $image_entry);
				}
				
				$album_info['Images'] = $images_arr;
			}
		}
		
		
		echo json_encode($album_info);
		
		include($mini_backend_gallery_path . "mysql.close.php");
		exit;
	}
?>