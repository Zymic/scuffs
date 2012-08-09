<script type="text/javascript">
	$(function()
	{
		$("label").disableSelection();
		sizeCheck(1);
		sizeCheck(2);
		sizeCheck(3);
		
		$("#create_new_album").submit(function()
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
<h1>Create New Album</h1>
<form action="?" method="post" name="create_new_album" id="create_new_album" class="edit_gallery">
  <table border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td>
      <label for="album_name">Album Name:</label>
      <input type="text" name="album_name" class="input" id="album_name"></td>
    </tr>
    <tr>
      <td height="90">
      <label for="description">Description:</label>
      <textarea name="description" id="album_description" class="input" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>
      <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="thumbnail1" type="checkbox" id="thumbnail1" <?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_1_size) ? 'checked' : ''; ?> value="1">
            </td>
          <td><label for="thumbnail1" title="Thumbnail prefix: th1_">Create thumbnail 1</label></td>
          </tr>
      </table>
        <table class="size1" style="display:<?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_1_size) ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="right_padding"><label for="size1">Set thumbnail size:</label><br></td>
            <td>
            	<?php $thumbnail_1_size = explode("x", $thumbnail_1_size); ?>
            	<input type="text" name="size1W" id="size1W" value="<?php echo $thumbnail_1_size[0]; ?>" class="input number" size="6" placeholder="width">
            	X
            	<input type="text" name="size1H" id="size1H" value="<?php echo $thumbnail_1_size[1]; ?>" class="input number" size="6" placeholder="height">
            	
	            <span class="highlighted" id="thumbnail1fit_chb" style="<?php echo $thumbnail_1_size[2] || ($thumbnail_1_size[0] && $thumbnail_1_size[1]) ? 'display:inline-block' : ''; ?>">
		            	<input type="checkbox" name="thumbnail1fit" id="thumbnail1fit" <?php echo $thumbnail_1_size[2] ? 'checked="checked"' : ''; ?> value="1" />
		            	Fit to canvas (<a href="#" class="tooltip" title="If checked, image will automatically be streched proportionally to the defined frame size.">?</a>)
	            </span>
            </td>
          </tr>
      </table></td>
    </tr>
    <tr><td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="thumbnail2" type="checkbox" id="thumbnail2" <?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_2_size) ? 'checked' : ''; ?> value="1">
            </td>
          <td><label for="thumbnail2" title="Thumbnail prefix: th2_">Create thumbnail 2</label></td>
          </tr>
      </table>
        <table class="size2" style="display:<?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_2_size) ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="right_padding"><label for="size2">Set thumbnail size:</label><br></td>
            <td>
            	<?php $thumbnail_2_size = explode("x", $thumbnail_2_size); ?>
            	<input type="text" name="size2W" id="size2W" value="<?php echo $thumbnail_2_size[0]; ?>" class="input number" size="6" placeholder="width">
            	X
            	<input type="text" name="size2H" id="size2H" value="<?php echo $thumbnail_2_size[1]; ?>" class="input number" size="6" placeholder="height">
            	
	            <span class="highlighted" id="thumbnail2fit_chb" style="<?php echo $thumbnail_2_size[2] || ($thumbnail_2_size[0] && $thumbnail_2_size[1]) ? 'display:inline-block' : ''; ?>">
	            	<input type="checkbox" name="thumbnail2fit" id="thumbnail2fit" <?php echo $thumbnail_2_size[2] ? 'checked="checked"' : ''; ?> value="1" />
	            	Fit to canvas
	            </span>
            </td>
          </tr>
      </table></td>
    </tr>
    <tr><td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="thumbnail3" type="checkbox" id="thumbnail3" <?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_3_size) ? 'checked' : ''; ?> value="1">
            </td>
          <td><label for="thumbnail3" title="Thumbnail prefix: th3_">Create thumbnail 3</label></td>
          </tr>
      </table>
        <table class="size3" style="display:<?php echo preg_match("/^([0-9]+x[0-9]+|[0-9]+x|x[0-9]+)(x[0-9]?)?$/i", $thumbnail_3_size) ? 'block' : 'none'; ?>" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="right_padding"><label for="size3">Set thumbnail size:</label><br></td>
            <td>
	            <?php $thumbnail_3_size = explode("x", $thumbnail_3_size); ?>
	            <input type="text" name="size3W" id="size3W" value="<?php echo $thumbnail_3_size[0]; ?>" class="input number" size="6" placeholder="width">
	            X
	            <input type="text" name="size3H" id="size3H" value="<?php echo $thumbnail_3_size[1]; ?>" class="input number" size="6" placeholder="height">            
	            
	            <span class="highlighted" id="thumbnail3fit_chb" style="<?php echo $thumbnail_3_size[2] || ($thumbnail_3_size[0] && $thumbnail_3_size[1]) ? 'display:inline-block' : ''; ?>">
		            	<input type="checkbox" name="thumbnail3fit" id="thumbnail3fit" <?php echo $thumbnail_3_size[2] ? 'checked="checked"' : ''; ?> value="1" />
		            	Fit to canvas
	            </span>
            </td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><input type="submit" name="create_album" class="button" id="create_album" value="Create Album"></td>
    </tr>
  </table>
</form>

<a href="?action=main" class="go_back">&laquo; Go Back</a>
<div class="separator"></div>