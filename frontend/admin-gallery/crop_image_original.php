<script type="text/javascript" src="js/jcrop/jquery.Jcrop.js"></script>
<link type="text/css" rel="stylesheet" href="css/jquery.Jcrop.css" />
<script type="text/javascript">
	$(document).ready(function()
	{
		$("#crop_image").Jcrop({
			onSelect: function(coords)
			{
				$("#crop_w").val( coords.w );
				$("#crop_h").val( coords.h );
				
				$("#crop_x1").val( coords.x );
				$("#crop_y1").val( coords.y );
				
				$("#crop_x2").val( coords.x2 );
				$("#crop_y2").val( coords.y2 );
			}
		});
		
		$("#aspect_ratio").click(function()
		{
			var ar = $(this).val();
			
			if( $(this).attr("checked") )
			{
				$("#crop_image").Jcrop({aspectRatio: ar});
			}
			else
			{
				$("#crop_image").Jcrop({aspectRatio: 0});
			}
		});
		
		$("#crop_image_form").submit(function()
		{
			var form = $(this);
			
			var w  = parseInt(form.find('#crop_w').val());
			var h  = parseInt(form.find('#crop_h').val());
			var x1 = parseInt(form.find('#crop_x1').val());
			var y1 = parseInt(form.find('#crop_y1').val());
			var x2 = parseInt(form.find('#crop_x2').val());
			var y2 = parseInt(form.find('#crop_y2').val());
			
			if( !(w > 0 && h > 0 && x1 < x2 && y1 < y2) )
			{
				alert("Please select an area to crop!");
				return false;
			}
		});
		
	});
	
</script>
<?php
	$image_id = $_GET['id'];
	
	if( imageExists($image_id) )
	{
		$image = getImage($image_id);
		
		$name = $image['Name'];
		
		$image_path = $image['ImagePath'];
		$imagesize = getimagesize($image_path);
		
		?>
		<script type="text/javascript">
		
			$(window).load(function()
			{
				var body = $("body");
				window.resizeTo(<?php echo ($imagesize[0] < 310 ? 310 : $imagesize[0]) + 50; ?>, body.outerHeight() + 100);
				window.moveTo(200, 200);
			});
			
		</script>
		<form method="post" id="crop_image_form">
			<div class="crop_env">
					<input type="checkbox" name="aspect_ratio" id="aspect_ratio" value="<?php echo $imagesize[0] / $imagesize[1]; ?>" /> 
					<label for="aspect_ratio">Maintain aspect ratio
				</label>
			</div>
			
			<div class="crop_img_env radius">
				<img id="crop_image" src="<?php echo $image_path; ?>?<?php echo mt_rand(10000,99990); ?>" width="<?php echo $imagesize[0]; ?>" height="<?php echo $imagesize[1]; ?>" alt="" /> 
				
			</div>
			
			<br />
			<input type="submit" name="save_crop" id="save_crop" value="Crop Image" class="button">
			
			<input type="hidden" name="crop_w" id="crop_w" value="0" />
			<input type="hidden" name="crop_h" id="crop_h" value="0" />
			<input type="hidden" name="crop_x1" id="crop_x1" value="0" />
			<input type="hidden" name="crop_y1" id="crop_y1" value="0" />
			<input type="hidden" name="crop_x2" id="crop_x2" value="0" />
			<input type="hidden" name="crop_y2" id="crop_y2" value="0" />
		</form>
		<?php
	}
	else
	{
		?>
		<div class="error">
			Image doesn't exists!
		</div>
		<?php
	}
?>