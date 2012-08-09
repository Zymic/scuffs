<?php

	/**
	 * Mini Back-end Gallery - Powerful Image Gallery
	 *
	 * Version: 2.1
	 * Last Update: October, 2011
	 * 
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 * Author URL: http://codecanyon.net/user/arl1nd
	 *
	 */

	// Super Admin
	$admin_username 	= get_option("admin_username");
	$admin_password 	= get_option("admin_password");
	
	// Gallery Settings
	$title 				= get_option("title");
	
	
	// Naming convention
	$naming 			= get_option("naming");
	
	
	// Default Thumbnails (set empty if you don't want to create thumbnail)
	$thumbnail_1_size 	= get_option("thumbnail_1_size");
	$thumbnail_2_size 	= get_option("thumbnail_2_size");
	$thumbnail_3_size 	= get_option("thumbnail_3_size");
	
	
	// Images upload path
	$images_path 		= get_option("images_path");
	
	
	// Front-end Themes Framework
	$fe_installed 		= get_option("fe_installed");
	$fe_theme_selected	= get_option("fe_theme_selected");
	$fe_url 			= get_option("fe_url");
	$fe_path 			= get_option("fe_path");
	
	### V2.1 ###
	
	// Select Type for Categories (multiple - single)
	$category_select_type = get_option("category_select_type");
	
?>