<?php
	$album_id = $_GET['album_id'];
?>
<script type="text/javascript">
	$(window).load(function()
    {
    	framecenter();
    	$("body").css("background", "transparent");
    });
    
    function framecenter()
    {    	
    	var pww = $(parent.window).width();
    	var pwh = $(parent.window).height();
    	   	
    	var body_height = $(document).height();
    	var frame = $(parent.document).find('#image_frame');
    	
    	frame.height(body_height);
    	
    	var frame_width = frame.width();
    	var frame_height = frame.height();
    	    	
    	var frame_top = parseInt(pwh/2 - frame_height/2);
    	var frame_left = parseInt(pww/2 - frame_width/2);
    	
    	frame_top = frame_top < 0 ? 0 : frame_top;
    	frame_left = frame_left < 0 ? 0 : frame_left;
    	
    	frame.css({top: frame_top, left: frame_left});
    }
    
</script>
<?php
	if( albumExists($album_id) )
	{
		$album = getAlbum($album_id);
		$album_images = getAlbumItems($album_id);
		
		?>
		<div class="album_cover_div radius">
			<div class="loader_2">
				<img src="css/images/loading29.gif" alt="loading29" width="19" height="19" />
			</div>
			
			<h2>Choose album cover image</h2>
			
			<?php
			
				if( count($album_images) )
				{
					?>
					<script type="text/javascript">
						$(document).ready(function()
						{
							$(".image_checkbox").click(function()
							{
								var id = $(this).val();
								var cover_type = "set";
								var thumbnail_url = $(this).parent().parent().find('img').attr("src");
																
								if( $(this).attr('checked') == false )
									cover_type = "unset";
								
								$(".image_checkbox:not(#image_id_"+id+")").attr("checked", false);
								$(".loader_2").fadeIn("normal");
								
								$.ajax(
								{
									url: "admin.php?album_id=<?php echo $album_id; ?>&set_cover=" + id + "&cover_type=" + cover_type,
									success: function(data)
									{
										$(".loader_2").fadeOut("normal");
										
										var img_cover_el = $(parent.document).find('.image_cover img');
										
										if( cover_type == "unset" )
											img_cover_el.attr("src", "css/images/album_cover.png");
										else
											img_cover_el.attr("src", thumbnail_url);
									} 
								});
							});
						});
					</script>
					<ul class="album_images">
					<?php
					
					foreach($album_images as $image)
					{
						$image_id = $image['ImageID'];
						$image_path = $image['ImagePath'];
						$name = $image['Name'];
						
						$has_image = file_exists($image_path) ? true : false;
						
						$thumbnail = dirname($image_path) . '/th_' . basename($image_path);
						
						if( !$has_image )
						{
							$thumbnail = "css/images/no-image.png";
						}
						
						?>
		                <li class="image radius" data-imageid="<?php echo $image['ImageID']; ?>">
			                <label for="image_id_<?php echo $image_id; ?>">
								<img src="<?php echo $thumbnail; ?>" width="110" height="95" alt="<?php echo addslashes($name); ?>" title="<?php echo addslashes($name); ?>" />
			                    <div class="clear"></div>
			                    <div class="radio_button">
			                    	<input<?php echo !$has_image ? ' disabled="disabled"' : ''; ?> type="checkbox"<?php echo $album['AlbumCoverID'] == $image_id ? ' checked="checked"' : ''; ?> name="album_cover[]" class="image_checkbox" id="image_id_<?php echo $image_id; ?>" value="<?php echo $image_id; ?>" />
			                    	<div class="clear"></div>
			                    </div>
			                </label>
		                </li>
		                <?php
					}
					
					?>
					</ul>
					<div class="clear"></div>
					<?php
				}
				else
				{
					?>
					<span class="noimage">There are no images in this album</span>
					<?php
				}
			?>
		</div>
		<?php
	}
	else
	{
	?>
		<div class="error">
			Album doesn't exists!
		</div>
	<?php
	}
?>