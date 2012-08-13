<?php
	header('X-Frame-Options: GOFORIT');

	$albums = getAllAlbums();
	$albums_total = count($albums);
	
	$dn_show_categories = get_option('dn_show_categories');
	$dn_category_sorting_effect = get_option('dn_category_sorting_effect');
	
	$dn_pagination 	= get_option('dn_pagination');
	$dn_albums_per_page = get_option('dn_albums_per_page');
	
	$pages = ceil( $albums_total / $dn_albums_per_page );
	
	if( $dn_show_categories )
	{
		$categories = getCategories();
		$_album_categories = array();
		
		foreach($albums as $album)
		{
			$album_id = $album['AlbumID'];
			
			if( !is_array($_album_categories[$album_id]) )
			{
				$_album_categories[$album_id] = array();
			}
			
			$_album_categories[$album_id][] = getAlbumCategories($album_id);
		}
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>

<link type="text/css" rel="stylesheet" href="css/main.css" />
<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>

<link type="text/css" rel="stylesheet" href="themes/dark_night/css/theme-1.css" />
<script type="text/javascript" src="themes/dark_night/js/jquery.center.js"></script>
<script type="text/javascript" src="themes/dark_night/js/jquery.expander.js"></script>
<link type="text/css" rel="stylesheet" href="themes/dark_night/css/default.css" />
<?php if( $dn_show_categories ){ ?>
<script type="text/javascript" src="themes/dark_night/js/mobilyselect.js"></script>
<?php } ?>
<script type="text/javascript" src="themes/dark_night/js/mbg-frontend-theme1.js"></script>

</head>
<body>

<input type="hidden" name="dn_category_sorting_effect" value="<?php echo $dn_category_sorting_effect; ?>" />
<input type="hidden" name="dn_albums_per_page" value="<?php echo $dn_pagination ? $dn_albums_per_page : $albums_total; ?>" />

	<div class="wrapper">
		<!-- Your logo here -->
		<a href="./">
			<img src="images/mbg-logo.png" alt="mbg-logo" width="447" height="31" />
		</a>
		
		<div class="content">
		
			<?php
				
				include_once("categorizing.php");
			
				if( count($albums) )
				{
					?>
					<div class="albums_env selecterContent">
						<ul class="albums">
						<?php
							$page_index = 1;
							foreach($albums as $i => $album)
							{
								$album_id = $album['AlbumID'];
								$album_name = $album['AlbumName'];
								$album_cover = $album['AlbumCover'];
								
								$page_index_class = "page_" . ceil(($i+1)/$dn_albums_per_page);
								
								$album_cover_img = "themes/dark_night/images/album-cover.png";
								
								if( $album_cover )
								{
									$album_cover_img = $mini_backend_gallery_url . $album_cover['Thumbnail1'];
								}
								
								$images_count = countAlbumImages($album_id);
						?>
							<li class="<?php
								echo $page_index_class; 
								
								if( $dn_show_categories )
								{															
									foreach($_album_categories[$album_id] as $album_category)
									{								
										foreach($album_category as $i => $category)
										{
											$category_id = $category['CategoryID'];
											
											echo " category_{$category_id}";
										}
									}
								}
								
							?>" data-id="<?php echo $album_id; ?>">
								<div class="album_outer_container">
									<div class="album_container">
										<a href="#" class="album_cover">
											<img src="<?php echo $album_cover_img; ?>" width="215" height="145" alt="" />
										</a>
										
										<a href="#" class="album_name"><?php echo $album_name; ?></a>
										<span class="album_details"><?php echo $images_count; ?> photo<?php echo $images_count != 1 ? 's' : ''; ?></span>
										
										<div class="clear"></div>
									</div>
								</div>
							</li>
						<?php
							}
						?>
						</ul>
					</div>
					
					<div class="clear"></div>
					<?php
				}
				else
				{
					?>
					<h1>There is no any album!</h1>
					<?php
				}
			?>

		</div>
		
		<?php
		
		if( $dn_pagination && $albums_total > $dn_albums_per_page )
		{
			?>
			<div class="pagination selecterBtns">
				<ul>
				<?php
					for($i=1; $i<=$pages; $i++)
					{
						?>
						<li>
							<a href="#" rel="page_<?php echo $i; ?>"><?php echo $i; ?></a>
						</li>
						<?php
					}
				?>
					<div class="clear"></div>
				</ul>
				<div class="clear"></div>
			</div>
			<?php
		}
		
		?>
		
		<div class="loader"></div>
		<div class="copyright"> Mini Back-end Gallery v2.1 created by Arlind Nushi
		</div>
		
	</div>
</body>
</html>