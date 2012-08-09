<h1>Gallery Front-end Themes Framework</h1>
<?php

	if( (!$fe_installed || isset($_GET['configure'])) && !defined('_THEME_PARAMS_SAVED_') )
	{
		$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$gallery_url = preg_replace("/[^\/]+$/i", "", $url);
		
		### v2.1 ###
		if( !$fe_installed )
		{
			// Try Finding Theme Framework Automatically
			$forward_paths = glob("*/", GLOB_ONLYDIR);
			$backward_paths = glob("../*/", GLOB_ONLYDIR);
			
			$fe_path_finded = false;
			
			foreach($forward_paths as $dir)
			{
				// Index File
				$index_file = $dir . "config.php";
				
				if( !$fe_path_finded && isFrontendFile($index_file) )
				{
					$fe_path = $dir;					
					$fe_path_finded = true;
					
					$fe_url = $gallery_url . $dir;
				}
			}
			
			foreach($backward_paths as $dir)
			{
				// Index File
				$index_file = $dir . "config.php";
				
				if( !$fe_path_finded && isFrontendFile($index_file) )
				{
					$fe_path = $dir;					
					$fe_path_finded = true;
					
					$fe_url = dirname($gallery_url) . substr($dir, 2);
				}
				
				// Check SubFolders
				$sub_folders = glob("$dir*/", GLOB_ONLYDIR);
				
				foreach($sub_folders as $sub_dir)
				{
					// Index File
					$index_file = $sub_dir . "config.php";
					
					if( !$fe_path_finded && isFrontendFile($index_file) )
					{
						$fe_path = $sub_dir;					
						$fe_path_finded = true;
						
						$fe_url = dirname($gallery_url) . substr($sub_dir, 2);
					}
				}
			}
		}
		### END: v2.1 ###
		?>
		<h3>Please configure theme framework path</h3>
		
		<form method="post" name="theme-settings">
			<input type="hidden" name="gallery_url" value="<?php echo $gallery_url; ?>" />
			<input type="hidden" name="gallery_path" value="<?php echo dirname(__FILE__); ?>/" />
			
			<table cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td>
						<label for="fe_url">Front-end themes framework URL:</label>
						<input type="text" name="fe_url" id="fe_url" class="input" size="65" value="<?php echo $fe_url ? $fe_url : "http://"; ?>" />
					</td>
					<td rowspan="3" style="padding-left:20px;" valign="top">
						<div class="warning">
							<span>Warning:</span> 
							<br />
							By installing front-end themes some settings will be automatically rewritten such as:
							<br />
							<ul style="margin:0px;">
								<li>Thumbnail sizes</li>
							</ul>
							You are not recommended to change these default settings for each theme in order to get framework work properly!
						</div>
					</td>
				</tr>
				<tr>
					<td class="toppadd">
						<label for="fe_path">Front-end themes framework PATH:</label>
						<input type="text" name="fe_path" id="fe_path" class="input" size="65" value="<?php echo $fe_path; ?>" />
					</td>
				</tr>
				<tr>
					<td class="toppadd">
						<input type="submit" name="fe_save_settings" id="fe_save_settings" class="button" value="Save Settings" />
					</td>
				</tr>
			</table>
			
			<br />
			<a href="http://arlindnushi.dervina.com/mbg-v2/gallery_path_setup.html" target="_blank">Click here if you need help!</a>
		</form>
		<?php
	}
	else
	{
		$themes_dir = $fe_path . "themes/*";
		$browse_themes = glob($themes_dir);
		
		$themes_arr = array();
		
		foreach($browse_themes as $theme_path)
		{
			$theme_name = basename($theme_path);
			$theme_screenshot = null;
			
			if( file_exists($theme_path . "/screenshot.png") )
			{
				$theme_screenshot = $fe_url . "themes/" . $theme_name . "/screenshot.png";
			}
			
			array_push($themes_arr, array("name" => $theme_name, "screenshot" => $theme_screenshot, "path" => $theme_path . "/"));
		}
		
		?>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".confirm_installation").click(function()
				{
					if( !confirm("Are you sure you want to install this theme?\n\nNote: All album thumbnails will be re-created and this may take some time during the process!") )
						return false;
				});
				
				$(".confirm_disabling").click(function()
				{
					if( !confirm("Are you sure you want to disable this theme?") )
						return false;
				});
			});
		</script>
		<h3>Set your preferred theme</h3>
		<?php
			if( count($themes_arr) )
			{
				$total_images = totalImages();
				$fe_config = $fe_path . "config.php";
				
				if( file_exists($fe_config) )
					include($fe_config);
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<?php
				foreach($themes_arr as $theme)
				{
					$theme_constraints = "No constraints defined!";
					
					if( file_exists($theme['path'] . "constraints.php") )
					{
						include($theme['path'] . "constraints.php");
						
						if( $THEME_THUMBNAIL1_SIZE || $THEME_THUMBNAIL2_SIZE || $THEME_THUMBNAIL3_SIZE )
						{
							$theme_constraints = "";
							
							if( $THEME_THUMBNAIL1_SIZE != null )
							{
								$thumbnail_size = explode("x", $THEME_THUMBNAIL1_SIZE);
								
								$thumb_width = $thumbnail_size[0];
								$thumb_height = $thumbnail_size[1];
								$thumb_fit_canvas = $thumbnail_size[2];
								
								if( $thumb_width && $thumb_height )
								{
									$size_1_details = "{$thumb_width}x{$thumb_height}";
									
									if( $thumb_fit_canvas )
										$size_1_details .= " fitted";
								}
								else
								if( $thumb_width )
								{
									$size_1_details = "{$thumb_width} pixels width";
								}
								else
								if( $thumb_height )
								{
									$size_1_details = "{$thumb_height} pixels height";
								}
								
								$theme_constraints .= "Thumbnail 1 ($size_1_details), ";
							}
								
							if( $THEME_THUMBNAIL2_SIZE != null )
							{
								$thumbnail_size = explode("x", $THEME_THUMBNAIL2_SIZE);
								
								$thumb_width = $thumbnail_size[0];
								$thumb_height = $thumbnail_size[1];
								$thumb_fit_canvas = $thumbnail_size[2];
								
								if( $thumb_width && $thumb_height )
								{
									$size_2_details = "{$thumb_width}x{$thumb_height}";
									
									if( $thumb_fit_canvas )
										$size_2_details .= " fitted";
								}
								else
								if( $thumb_width )
								{
									$size_2_details = "{$thumb_width} pixels width";
								}
								else
								if( $thumb_height )
								{
									$size_2_details = "{$thumb_height} pixels height";
								}
								
								$theme_constraints .= "Thumbnail 2 ($size_2_details), ";
							}
								
							if( $THEME_THUMBNAIL3_SIZE != null )
							{
								$thumbnail_size = explode("x", $THEME_THUMBNAIL3_SIZE);
								
								$thumb_width = $thumbnail_size[0];
								$thumb_height = $thumbnail_size[1];
								$thumb_fit_canvas = $thumbnail_size[2];
								
								if( $thumb_width && $thumb_height )
								{
									$size_3_details = "{$thumb_width}x{$thumb_height}";
									
									if( $thumb_fit_canvas )
										$size_3_details .= " fitted";
								}
								else
								if( $thumb_width )
								{
									$size_3_details = "{$thumb_width} pixels width";
								}
								else
								if( $thumb_height )
								{
									$size_3_details = "{$thumb_height} pixels height";
								}
								
								$theme_constraints .= "Thumbnail 3 ($size_3_details), ";
							}
							
							$theme_constraints = substr($theme_constraints, 0, -2);
						}
					}
					
					
					$current_theme = get_option('current_theme');
					$is_current_theme = $fe_theme_selected && $theme['name'] == $current_theme;
				
				
			?>
			<tr class="radius<?php echo $is_current_theme ? ' installed_theme' : ''; ?>">
				<?php if( $theme['screenshot'] ){ ?>	
				<td class="theme_install bottompad toppadd leftpadd" width="180">
					<img src="<?php echo $theme['screenshot']; ?>" alt="theme-screenshot" width="180" height="134" align="left" />
				</td>
				<?php } ?>
				
				<td class="theme_install bottompad toppadd" valign="top">
					<div>Theme name:</div>
					<h1><?php echo $theme['name']; ?></h1>
					
					<div>Theme constraints:</div>
					<div class="constraints"><?php echo $theme_constraints; ?></div>
					
					<br />
					
					<?php if( $is_current_theme ){ ?>
					<a href="<?php echo $fe_url; ?>" class="button" target="_blank">Preview</a>
					<?php } ?>
					
					<?php if( $is_current_theme && file_exists($theme['path'] . "config.php") ){ ?>
						<a href="?action=theme_configuration" class="button">Configuration</a>
					<?php } ?>
					
					<?php  if( $is_current_theme ) {  ?>
					<a href="admin.php?action=front-end&disable_theme=<?php echo $current_theme; ?>" class="button confirm_disabling">Disable</a>
					<?php } ?>
					
					<?php if( !$is_current_theme ){  ?>
					<form method="post" name="theme-settings-select-theme" action="admin.php?action=front-end">
						<input type="hidden" name="theme_name" value="<?php echo $theme['name']; ?>" />
						<input type="submit" name="set_theme" value="INSTALL" class="button install<?php echo $total_images > 10 ? ' confirm_installation' : ''; ?>" />
					</form>
					<?php } ?>
				</td>
			</tr>
		<?php
				}
		?>
		</table>
		
		<br />
		<a href="admin.php?action=front-end&configure" class="button setup_frontend_fp">Setup Front-end Framework Path</a>
		<?php
			}
			else
			{
				?>
				<div class="error">There are no themes available to install! Please insert themes in the themes/ directory on front-end framework.</div>
				<a href="admin.php?action=front-end&configure" class="button setup_frontend_fp">Setup Front-end Framework Path</a>
				<?php
			}
	}
?>
<div class="separator"></div>