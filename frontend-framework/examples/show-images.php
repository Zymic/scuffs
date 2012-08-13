<?php

	/**
	 * Mini Back-end Gallery
	 *
	 * Show Images Example
	 *
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 */
	
	include('../backend-gallery/config.php');
	include('../backend-gallery/mysql.open.php');
	
	include('../backend-gallery/functions.php'); // Functions to operate with
	
	
	$options = array();
	$options["backend_url"] 	= "../backend-gallery/"; 	// Url to your installed copy of Mini Backend Gallery, usually starts with http://example.com/mbg/ - Default: null
	$options["link_id"]			= "?album_id=%&image_id=$";	// Image link ID to be replaced with $, album id will be replaced with % sign - Default null
	$options["class"] 			= "class_of_ul_menu"; 		// Class of UL container - Default: images_list
	$options["image_size"]		= "DefaultThumbnail"; 		// Image sizes to show as cover available from MBG: DefaultThumbnail, Thumbnail1, Thumbnail2, Thumbnail3 - Default: DefaultThumbnail
	$options["order"]			= "DESC"; 					// Order albums ASC or DESC - Default: ASC
	$options["album_id"]		= "";						// Filter images fron an album id - Default null
	$options["show_name"]		= "yes";					// Show image names (yes or no) - Default yes
	
	showImages($options); // If there is no any image, nothing will be outputed
	
	include('../backend-gallery/mysql.close.php');

?>