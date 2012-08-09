<?php

	/**
	 * Front-end Theme
	 *
	 * Mini Back-end Gallery
	 *
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 */
	
	error_reporting(E_ERROR);
	
	define("FRONT_END_GALLERY_THEME", true);
	
	include("config.php");
	
	if( !file_exists($mini_backend_gallery_path) )
	{
		include('gallery_offline.php');
	}
	else
	{
		// Import Backend Gallery Settings and Functions
		include($mini_backend_gallery_path . "config.php");
		include($mini_backend_gallery_path . "mysql.open.php");
		include($mini_backend_gallery_path . "functions.php");
		
		// Theme to use
		$current_theme = get_option("current_theme");
		
		// JSON requests parser
		include("mbg_json_reqs.php");
		
		// Call theme file
		$themes_dir = dirname(__FILE__) . "/themes/*";
		$browse_themes = glob($themes_dir);
		
		$get_current_theme = "";
		
		foreach($browse_themes as $theme_path)
		{
			$theme_name = basename($theme_path);
			
			if( $theme_name == $current_theme )
				$get_current_theme = $theme_path;
		}
		
		if( $fe_theme_selected && file_exists($get_current_theme . "/index.php") )
		{
			include($get_current_theme . "/index.php");
		}
		else
		{
			include('gallery_offline.php');
		}
		
		// Close MySQL connection
		include($mini_backend_gallery_path . "mysql.close.php");
	}
?>