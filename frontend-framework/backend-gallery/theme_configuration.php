<?php
	$current_theme = get_option('current_theme');
	
	$themes_dir = $fe_path . "themes/*";
	$browse_themes = glob($themes_dir);
	
	$themes_arr = array();
	
	# Current Theme to Edit
	$current_theme_arr = array();
	
	foreach($browse_themes as $theme_path)
	{
		$theme_name = basename($theme_path);
		$theme_screenshot = null;
		
		if( file_exists($theme_path . "/screenshot.png") )
		{
			$theme_screenshot = $fe_url . "themes/" . $theme_name . "/screenshot.png";
		}
		
		$theme_entry = array("name" => $theme_name, "screenshot" => $theme_screenshot, "path" => $theme_path . "/");
		
		if( $fe_theme_selected && $theme_name == $current_theme )
		{
			$current_theme_arr = $theme_entry;
		}
	}
	
	
	$config_file = $current_theme_arr['path'] . "config.php";
	
?><h1>Theme Configuration</h1>

<?php

	if( !$fe_installed || !$current_theme_arr )
	{
	?>
	<h3>There is no active theme!</h3>
	<?php
	}
	else
	{
	?>
	
	<h3><span style="font-weight:normal;">Theme:</span> <?php echo $current_theme_arr['name']; ?></h3>
	
	<?php
	if( !file_exists($config_file) )
	{
		?>
		<div class="error">
			This theme doesn't support configuration!
		</div>
		<?php
	}
	?>
	
	<?php
		if( file_exists($config_file) )
		{
		?>
		<form action="?action=<?php echo $_GET['action']; ?>" enctype="multipart/form-data" method="post" class="edit_gallery radius">
			<?php include_once($config_file); ?>
		</form>
		<?php
		}
	?>
		
	<?php
	}
	
?>

<div class="clear"></div>

<a href="?action=front-end" class="go_back">&laquo; Go Back</a>
<div class="separator"></div>
