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
	
	error_reporting(E_ERROR);
	
	session_start();
	
	define("ADMIN_AREA", 1);	
	
	include("config.php");
	
	$action = $_GET['action'];
	$action = strtolower($action);
	
	include_once("mysql.open.php");
		
	
	// Import Main Functions
	include_once("functions.php");
	include_once("functions.php");
	include_once("inc/sn.php");	
	
	if( !isLoggedIn($admin_username, $admin_password) )
	{
		if( $upload_image = $_FILES['upload_image'] )
		{
			include("misc.php");
		}
		else
			header("Location: index.php");
			
		exit;
	}
	
	include("misc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<script type="text/javascript" src="js/jquery-ui/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.uploadify-v2.1.4/swfobject.js"></script>
<script type="text/javascript" src="js/jquery.uploadify-v2.1.4/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript" src="js/tipsy-0.1.7/javascripts/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/misc.js"></script>
<link href="css/dark-hive/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="js/tipsy-0.1.7/stylesheets/tipsy.css" rel="stylesheet" type="text/css" />
<link href="js/jquery.uploadify-v2.1.4/uploadify.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
	if( file_exists("install.php") && $_GET['action'] != 'verify_purchase' )
	{
		define("_ERROR_", "Please delete install.php file!");
	}
    	
    	$hide_settings_arr = array("albumcover");
    	
    	if( !in_array($_GET['action'], $hide_settings_arr) )
    	{
    ?>
    <div class="mbg_header">
    	<a href="admin.php">
    		<img src="css/images/mbg-logo.png" alt="mbg-logo" width="448" height="32" />
    	</a>
    </div>
    
    
    <div class="main_content">
	    <?php
			if( defined("_SUCCESS_") )
			{
		?>
	    <div class="success">
	    	<?php echo _SUCCESS_; ?>
	    </div>
	    <?php
			}
		?>
	    
		<?php
			if( defined("_ERROR_") )
			{
		?>
	    <div class="error">
	    	<?php echo _ERROR_; ?>
	    </div>
	    <?php
			}
		?>
	
    <div class="main_menu_div">
		<a href="?action=new_album" class="button">Create New Album</a>
		<div class="clear"></div>
		<a href="?" class="button">Manage Albums</a>
		<div class="clear"></div>
		<a href="?action=categories" class="button">Categories</a>
		<div class="clear"></div>
		<a href="?action=front-end" class="button">Front-end Framework</a>
		<div class="clear"></div>
		<a href="?action=settings" class="button">General Settings</a>
		<div class="clear"></div>
		<a href="?logout" class="button">Logout</a>
    </div>
    
    <?php
    	}
    	else
    	{
    	
			if( defined("_SUCCESS_") )
			{
			?>
		    <div class="success">
		    	<?php echo _SUCCESS_; ?>
		    </div>
		    <?php
			}
			
			if( defined("_ERROR_") )
			{
			?>
		    <div class="error">
		    	<?php echo _ERROR_; ?>
		    </div>
		    <?php
			}
			
    	}
		
		switch( $action )
		{
			case "new_album":
				include("create_new_album.php");
				break;
			
			case "album":
				include("album_manage.php");
				break;
			
			case "editimage":
				include("edit_image.php");
				break;
			
			case "cropimage":
				include("crop_image_v2.php");
				break;
			
			case "settings":
				include("settings.php");
				break;
			
			case "albumcover":
				include("album_cover.php");
				break;
			
			case "front-end":
				include("front-end.php");
				break;
			
			### v2.1 ###
			case "categories":
				include("categories.php");
				break;
			
			case "theme_configuration":
				include("theme_configuration.php");
				break;
			
			case "verify_purchase":
				include("verify_purchase.php");
				break;
			
			default:
				include("albums.php");
		}
	?>
    <?php
    
    	if( !in_array($_GET['action'], $hide_settings_arr) )
    	{
    ?>
    <div class="copyrights">
    &copy; Mini Back-end Gallery <strong>v2.1</strong> created by <a href="mailto:arlindd@gmail.com">Arlind Nushi</a> - <a href="api.php">API</a>
    </div>
    <?php
    	}
    ?>
    </div>
    
</body>
</html>
<?php
	@mysql_close($connect);
?>