<script type="text/javascript">
	$(function()
	{
		$("label").disableSelection();
		sizeCheck(1);
		sizeCheck(2);
		sizeCheck(3);
		
		$("#edit_album").submit(function()
		{
			var album_name = $("#album_name");
			
			if( !album_name.val().length )
			{
				album_name.focus();
				return false;
			}
		});
	
		
		$(".number").focusout(function()
		{
			var $this = $(this);
			
			if( !$this.val().match(/^[0-9]+$/) && $this.val() != "" )
				$this.focus();
		});
		
		$("#size1W, #size1H").keyup(function()
		{
			var $this = $(this);
			
			if( $("#size1W").val().length > 0 && $("#size1H").val().length )
			{
				$("#thumbnail1fit_chb").show();
			}
			else
			{
				$("#thumbnail1fit_chb").hide();
			}
		});
		
		
		$("#size2W, #size2H").keyup(function()
		{
			var $this = $(this);
			
			if( $("#size2W").val().length > 0 && $("#size2H").val().length )
			{
				$("#thumbnail2fit_chb").show();
			}
			else
			{
				$("#thumbnail2fit_chb").hide();
			}
		});
		
		
		$("#size3W, #size3H").keyup(function()
		{
			var $this = $(this);
			
			if( $("#size3W").val().length > 0 && $("#size3H").val().length )
			{
				$("#thumbnail3fit_chb").show();
			}
			else
			{
				$("#thumbnail3fit_chb").hide();
			}
		});
	});
	
	function sizeCheck(size)
	{
		var chckox = $("#thumbnail"+size);
		
		chckox.click(function()
		{
			if( $(this).attr('checked') )
			{
				$(".size"+size).show();
				$("#size"+size).select();
			}
			else
			{
				$(".size"+size).hide();
			}
		});
	}
</script>
<div class="edit_gallery radius">
<?php
	$thumbnail1 = explode("x", $album['Thumbnail1Size']);
	$thumbnail2 = explode("x", $album['Thumbnail2Size']);
	$thumbnail3 = explode("x", $album['Thumbnail3Size']);
?>
<form action="?action=album&id=<?php echo $album['AlbumID']; ?>" method="post" name="edit_album" id="edit_album">
	
	<!-- v2.1 -->
	<div class="category_list">
		<h3>Album Category</h3>
		<?php
		
			// Get Category List
			$categories = getCategories();
			
			// Get Selected Categories for album
			$album_categories = getAlbumCategories($album['AlbumID']);
			$selected_categories = array();
			
			foreach($album_categories as $album_category)
			{
				array_push($selected_categories, $album_category['CategoryID']);
			}
			
			// Get Selection Type for Categories
			$category_select_type = get_option("category_select_type");
			
			foreach($categories as $category)
			{
				$cid = $category['CategoryID'];
				$category_name = $category['Name'];
				
				?>
				<label for="category_<?php echo $cid; ?>">
					<input<?php echo in_array($cid, $selected_categories) ? ' checked="checked"' : ''; ?> type="<?php echo $category_select_type == 'single' ? 'radio' : 'checkbox'; ?>" name="category[]" id="category_<?php echo $cid; ?>" value="<?php echo $cid; ?>" />
					<?php echo $category_name; ?>
				</label>
				<?php
			}
			
			if( !count($categories) )
			{
			?>
			<span>There are no categories in the list!</span>
			<?php
			}
		?>
	</div>

	<h3 class="edit_album_head">Album Details</h3>
	
	<table border="0" cellspacing="2" cellpadding="2">
	<tr>
	  <td>
	  <label for="album_name">Album Name:</label>
	  <input type="text" name="album_name" class="input" id="album_name" value="<?php echo addslashes($album['AlbumName']); ?>"></td>
	</tr>
	<tr>
	  <td height="90">
	  <label for="description">Description:</label>
	  <textarea name="description" id="album_description" class="input" cols="45" rows="5"><?php echo stripslashes($album['Description']); ?></textarea></td>
	</tr>
	<tr>
	  <td><table border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td><input name="thumbnail1" type="checkbox" id="thumbnail1" <?php echo $thumbnail1[0] || $thumbnail1[1] ? 'checked' : ''; ?> value="1">
	        </td>
	      <td><label for="thumbnail1">Create Thumbnail [1]</label></td>
	      </tr>
	  </table>
	    <table class="size1" style="display:<?php echo $thumbnail1[0] || $thumbnail1[1] ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td class="right_padding"><label for="size1">Set thumbnail size:</label><br></td>
	        <td>
	        	<input type="text" name="size1W" id="size1W" value="<?php echo $thumbnail1[0]; ?>" class="input number" size="6" placeholder="width">
	        	X
	        	<input type="text" name="size1H" id="size1H" value="<?php echo $thumbnail1[1]; ?>" class="input number" size="6" placeholder="height">
	        	
	            <span class="highlighted" id="thumbnail1fit_chb" style="<?php echo $thumbnail1[2] || ($thumbnail1[0] && $thumbnail1[1]) ? 'display:inline-block' : ''; ?>">
		            	<input type="checkbox" name="thumbnail1fit" id="thumbnail1fit" <?php echo $thumbnail1[2] ? 'checked="checked"' : ''; ?> value="1" />
		            	Fit to canvas (<a href="#" class="tooltip" title="If checked, image will automatically be streched proportionally to the defined frame size.">?</a>)
	            </span>
	        </td>
	      </tr>
	  </table></td>
	</tr>
	<tr><td><table border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td><input name="thumbnail2" type="checkbox" id="thumbnail2" <?php echo $thumbnail2[0] || $thumbnail2[1] ? 'checked' : ''; ?> value="1">
	        </td>
	      <td><label for="thumbnail2">Create Thumbnail [2]</label></td>
	      </tr>
	  </table>
	    <table class="size2" style="display:<?php echo $thumbnail2[0] || $thumbnail2[1] ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td class="right_padding"><label for="size2">Set thumbnail size:</label><br></td>
	        <td>
	        	<input type="text" name="size2W" id="size2W" value="<?php echo $thumbnail2[0]; ?>" class="input number" size="6" placeholder="width">
	        	X
	        	<input type="text" name="size2H" id="size2H" value="<?php echo $thumbnail2[1]; ?>" class="input number" size="6" placeholder="height">
	        	
	            <span class="highlighted" id="thumbnail2fit_chb" style="<?php echo $thumbnail2[2] || ($thumbnail2[0] && $thumbnail2[1]) ? 'display:inline-block' : ''; ?>">
	            	<input type="checkbox" name="thumbnail2fit" id="thumbnail2fit" <?php echo $thumbnail2[2] ? 'checked="checked"' : ''; ?> value="1" />
	            	Fit to canvas
	            </span>
	        </td>
	      </tr>
	  </table></td>
	</tr>
	<tr><td><table border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td><input name="thumbnail3" type="checkbox" id="thumbnail3" <?php echo $thumbnail3[0] || $thumbnail3[1] ? 'checked' : ''; ?> value="1">
	        </td>
	      <td><label for="thumbnail3">Create Thumbnail [3]</label></td>
	      </tr>
	  </table>
	    <table class="size3" style="display:<?php echo $thumbnail3[0] || $thumbnail3[1] ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td class="right_padding"><label for="size3">Set thumbnail size:</label><br></td>
	        <td>
	        	<input type="text" name="size3W" id="size3W" value="<?php echo $thumbnail3[0]; ?>" class="input number" size="6" placeholder="width">
	        	X
	        	<input type="text" name="size3H" id="size3H" value="<?php echo $thumbnail3[1]; ?>" class="input number" size="6" placeholder="height">
	        	
	            <span class="highlighted" id="thumbnail3fit_chb" style="<?php echo $thumbnail3[2] || ($thumbnail3[0] && $thumbnail3[1]) ? 'display:inline-block' : ''; ?>">
		            	<input type="checkbox" name="thumbnail3fit" id="thumbnail3fit" <?php echo $thumbnail3[2] ? 'checked="checked"' : ''; ?> value="1" />
		            	Fit to canvas
	            </span>
	        </td>
	      </tr>
	  </table></td>
	</tr>
	<tr>
	  <td><input type="submit" name="edit_album" class="button" id="edit_album" value="Edit Album"></td>
	</tr>
	</table>
</form>
</div>