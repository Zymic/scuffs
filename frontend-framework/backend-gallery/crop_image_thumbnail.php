<?php

	//$crop_image_thumbnail
	
	# Thumbnail URL
	$thumbnail_id = "thumbnail_$thumbnail_type";
	$thumbnaul_url = $$thumbnail_id;
	
	$o_size = getimagesize($image_path);
	$th_size = getimagesize($thumbnaul_url);
	
	// Generate Image Thumbnail Center
	$pct_w = $th_size[0] / $o_size[0];
	$pct_h = $th_size[1] / $o_size[1];
	
	$th_current_width = $o_size[0] - intval($pct_w * $o_size[0]);
	$th_current_height = $o_size[1] - intval($pct_h * $o_size[1]);
	
	$th_x = 0;
	$th_y = 0;
	
	// Image Params
	$params = getImageParams($image_id);
	
	$crop_info = $params['crop_info']['th_id_' . $thumbnail_type];
	
	
	if( is_array($crop_info) )
	{	
		$th_x = $crop_info['x'];
		$th_y = $crop_info['y'];
		
		
		$th_current_width = $th_x + $crop_info['width'];
		$th_current_height = $th_y + $crop_info['height'];
	}
	
?>
<script type="text/javascript" src="js/jcrop/jquery.Jcrop.js"></script>
<link type="text/css" rel="stylesheet" href="css/jquery.Jcrop.css" />
<script type="text/javascript">
	var o_img, o_img_url;
	var th_img;
	
	var o_img_w = <?php echo $o_size[0]; ?>, o_img_h = <?php echo $o_size[1]; ?>;
	var th_img_w = <?php echo $th_size[0]; ?>, th_img_h = <?php echo $th_size[1]; ?>;
	
	var w, h, x, y;
	
	$(document).ready(function()
	{
		o_img = $("#original_image img");
		o_img_url = o_img.attr("src");
		
		th_img = $("#thumbnail_image img");
		
		o_img.Jcrop(
		{
			aspectRatio: th_img_w / th_img_h,
			minSize: [th_img_w, th_img_h],
			onChange: showPreview,
			onSelect: showPreview,
			setSelect: [<?php echo $th_x; ?>, <?php echo $th_y; ?>, <?php echo $th_current_width; ?>, <?php echo $th_current_height; ?>]
		});
		
		
		$("#original_image .button").click(function(ev)
		{
			ev.preventDefault();
			var $this = $(this);
			
			$this.fadeTo(250, 0.2);
			
			if( w > 0 && h > 0 )
			{
				showLoader();
				$(".loader").css({position: 'fixed', marginTop: 50});
				
				$.post("?crop_thumbnail=<?php echo base64_encode($thumbnaul_url); ?>",
				{
					image_id: <?php echo $image_id; ?>,
					x: x,
					y: y,
					w: w,
					h: h,
					img_w: th_img_w,
					th_id: <?php echo $thumbnail_type; ?>
				}, function(data)
				{
					hideLoader();
					$this.fadeTo(250, 1);
				});
			}
		});
	});
	
	function showPreview(coords)
	{
		w = coords.w;
		h = coords.h;
	
		x = coords.x;
		y = coords.y;
		
		var pct = th_img_w / o_img_w;
		var pct_2 = o_img_w / w;
		
		th_img.attr("src", o_img_url);
		
		th_img.css({position: "relative", marginLeft: -x*pct*pct_2, marginTop: -y*pct*pct_2});
		
		th_img.width(o_img_w * pct * pct_2);
	};
</script>

<h3>Thumbnail (Preview)</h3>

<div class="thumbnail_env radius" id="thumbnail_image">
	<div style="width:<?php echo $th_size[0]; ?>px; height: <?php echo $th_size[1]; ?>px;">
		<img src="<?php echo $thumbnaul_url; ?>?<?php echo mt_rand(10000,99990); ?>" />
	</div>
</div>

<div class="clear"></div>
<br />
<br />

<h3>Original Image (Crop Area)</h3>

<div class="thumbnail_env radius" id="original_image">
	<a href="#" class="button">Crop This Area</a>
	<img src="<?php echo $image_path; ?>?<?php echo mt_rand(10000,99990); ?>" />
</div>

<div class="loader">Saving Cropped Image...</div>