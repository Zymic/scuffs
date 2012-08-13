<?php

	/**
	 * Mini Back-end Gallery
	 *
	 * Show Albums Example
	 *
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 */
	
	include('../backend-gallery/config.php');
	include('../backend-gallery/mysql.open.php');
	
	include('../backend-gallery/functions.php'); // Functions to operate with
	
	
	$options = array();
	$options["backend_url"] 	= "../backend-gallery/"; 	// Url to your installed copy of Mini Backend Gallery, usually starts with http://example.com/mbg/ - Default: null
	$options["link_id"]			= "?album_id=%";			// Album link ID to be replaced with % sign
	$options["no_cover_img"] 	= "nocover.png"; 			// When there's no cover image for album use this as default - Default: null
	$options["class"] 			= "class_of_ul_menu"; 		// Class of UL container - Default: albums_list
	$options["show_covers"] 	= "yes"; 					// "yes" or "no" to hide album covers - Default: yes
	$options["cover_image"]		= "DefaultThumbnail"; 		// Image sizes to show as cover available from MBG: DefaultThumbnail, Thumbnail1, Thumbnail2, Thumbnail3 - Default: DefaultThumbnail
	$options["order"]			= "DESC"; 					// Order albums ASC or DESC - Default: ASC
	
	showAlbums($options); // If there is no any album, nothing will be outputed
	
	include('../backend-gallery/mysql.close.php');

?>