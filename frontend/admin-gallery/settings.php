<h1>General Settings</h1>
<script type="text/javascript">
	$(document).ready(function()
	{
		$("#thumbnail1sizeW, #thumbnail1sizeH").focus(function(){ $("#thumbnail1").attr("checked", true); });
		$("#thumbnail2sizeW, #thumbnail2sizeH").focus(function(){ $("#thumbnail2").attr("checked", true); });
		$("#thumbnail3sizeW, #thumbnail3sizeH").focus(function(){ $("#thumbnail3").attr("checked", true); });
		
		$("#thumbnail1, #thumbnail2, #thumbnail3").click(function()
		{
			var num = $(this).attr("id").match(/[0-9]/);
			var num = num[0];
			
			if( $(this).attr("checked") == false )
			{
				$("#thumbnail"+num+"sizeW, #thumbnail"+num+"sizeH").val("");
			}
		});
		
		$(".number").focusout(function()
		{
			var $this = $(this);
			
			if( !$this.val().match(/^[0-9]+$/) && $this.val() != "" )
				$this.focus();
		});
		
		$("#thumbnail1sizeW, #thumbnail1sizeH").keyup(function()
		{
			var $this = $(this);
			
			if( $("#thumbnail1sizeW").val().length > 0 && $("#thumbnail1sizeH").val().length )
			{
				$("#thumbnail1fit_chb").show();
			}
			else
			{
				$("#thumbnail1fit_chb").hide();
			}
		});
		
		
		$("#thumbnail2sizeW, #thumbnail2sizeH").keyup(function()
		{
			var $this = $(this);
			
			if( $("#thumbnail2sizeW").val().length > 0 && $("#thumbnail2sizeH").val().length )
			{
				$("#thumbnail2fit_chb").show();
			}
			else
			{
				$("#thumbnail2fit_chb").hide();
			}
		});
		
		
		$("#thumbnail3sizeW, #thumbnail3sizeH").keyup(function()
		{
			var $this = $(this);
			
			if( $("#thumbnail3sizeW").val().length > 0 && $("#thumbnail3sizeH").val().length )
			{
				$("#thumbnail3fit_chb").show();
			}
			else
			{
				$("#thumbnail3fit_chb").hide();
			}
		});
		
	});
</script>
<form name="form1" method="post" action="admin.php">
  <table border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="2">
      <strong>Admin User</strong>
      <div class="separator"></div>
      </td>
    </tr>
    <tr>
      <td width="120"><label for="admin_username">Username:</label></td>
      <td>
      <input type="text" class="input" name="admin_username" id="admin_username" value="<?php echo $admin_username; ?>"></td>
    </tr>
    <tr>
      <td><label for="admin_password">Password:</label></td>
      <td>
      <input type="password" class="input" name="admin_password" id="admin_password"> 
      <span class="highlighted">Only if you want to change</span></td>
    </tr>
    <tr>
      <td height="50" colspan="2" valign="bottom"><strong>Gallery Settings</strong>
        <div class="separator"></div></td>
    </tr>
    <tr>
      <td><label for="gallery_title">Title:</label></td>
      <td>
      <input type="text" name="gallery_title" value="<?php echo htmlspecialchars($title); ?>" id="gallery_title" class="input"></td>
    </tr>
    <tr>
      <td><label for="naming">Image naming:</label></td>
      <td>
        <select name="naming" id="naming" class="input" style="width:170px">
          <option value="normal">Normal</option>
          <option value="hash"<?php echo $naming == 'hash' || $naming == 'random' ? ' selected' : ''; ?>>Hash (random)</option>
          <option value="nospaces"<?php echo $naming == 'nospaces' ? ' selected' : ''; ?>>No spaces</option>
      </select></td>
    </tr>
    <tr>
      <td height="30" colspan="2" valign="bottom"><strong>Thumbnail sizes by default</strong> - <em>i.e. 125x94, or just set preferred width/height</em></td>
    </tr>
    <tr>
      <td><label for="thumbnail1size" title="Thumbnail prefix: th1_">Create thumbnail 1:</label></td>
      <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="thumbnail1" type="checkbox" id="thumbnail1" <?php echo $thumbnail_1_size[0] > 0 || $thumbnail_1_size[1] > 0 ? 'checked' : ''; ?> value="1">
            </td>
          <td>
          	<?php $thumbnail_1_size = explode("x", $thumbnail_1_size); ?>
            <input name="thumbnail1sizeW" type="text" class="input number" id="thumbnail1sizeW" value="<?php echo $thumbnail_1_size[0]; ?>" size="6" placeholder="width">
            X
            <input name="thumbnail1sizeH" type="text" class="input number" id="thumbnail1sizeH" value="<?php echo $thumbnail_1_size[1]; ?>" size="6" placeholder="height"> 
            
            <span class="highlighted" id="thumbnail1fit_chb" style="<?php echo $thumbnail_1_size[2] || ($thumbnail_1_size[0] && $thumbnail_1_size[1]) ? 'display:inline-block' : ''; ?>">
	            	<input type="checkbox" name="thumbnail1fit" id="thumbnail1fit" <?php echo $thumbnail_1_size[2] ? 'checked="checked"' : ''; ?> value="1" />
	            	Fit to canvas (<a href="#" class="tooltip" title="If checked, image will automatically be streched proportionally to the defined frame size.">?</a>)
            </span>
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><label for="thumbnail2size" title="Thumbnail prefix: th2_">Create thumbnail 2:</label></td>
      <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="thumbnail2" type="checkbox" id="thumbnail2" <?php echo $thumbnail_2_size[0] > 0 || $thumbnail_2_size[1] > 0 ? 'checked' : ''; ?> value="1">
            </td>
          <td>
          	<?php $thumbnail_2_size = explode("x", $thumbnail_2_size); ?>
            <input name="thumbnail2sizeW" type="text" class="input number" id="thumbnail2sizeW" value="<?php echo $thumbnail_2_size[0]; ?>" size="6" placeholder="width">
            X
            <input name="thumbnail2sizeH" type="text" class="input number" id="thumbnail2sizeH" value="<?php echo $thumbnail_2_size[1]; ?>" size="6" placeholder="height">
            
            <span class="highlighted" id="thumbnail2fit_chb" style="<?php echo $thumbnail_2_size[2] || ($thumbnail_2_size[0] && $thumbnail_2_size[1]) ? 'display:inline-block' : ''; ?>">
	            	<input type="checkbox" name="thumbnail2fit" id="thumbnail2fit" <?php echo $thumbnail_2_size[2] ? 'checked="checked"' : ''; ?> value="1" />
	            	Fit to canvas
            </span>
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><label for="thumbnail3size" title="Thumbnail prefix: th3_">Create thumbnail 3:</label></td>
      <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          	<input name="thumbnail3" type="checkbox" id="thumbnail3" <?php echo $thumbnail_3_size[0] > 0 || $thumbnail_3_size[1] > 0 ? 'checked' : ''; ?> value="1">
            </td>
          <td>
          	<?php $thumbnail_3_size = explode("x", $thumbnail_3_size); ?>
            <input name="thumbnail3sizeW" type="text" class="input number" id="thumbnail3sizeW" value="<?php echo $thumbnail_3_size[0]; ?>" size="6" placeholder="width">
            X
            <input name="thumbnail3sizeH" type="text" class="input number" id="thumbnail3sizeH" value="<?php echo $thumbnail_3_size[1]; ?>" size="6" placeholder="height">
            
            <span class="highlighted" id="thumbnail3fit_chb" style="<?php echo $thumbnail_3_size[2] || ($thumbnail_3_size[0] && $thumbnail_3_size[1]) ? 'display:inline-block' : ''; ?>">
	            	<input type="checkbox" name="thumbnail3fit" id="thumbnail3fit" <?php echo $thumbnail_3_size[2] ? 'checked="checked"' : ''; ?> value="1" />
	            	Fit to canvas
            </span>
          </td>
        </tr>
      </table></td>
    </tr>
    
    <tr>
		<td colspan="2" height="50" valign="bottom">
			<strong>Categories</strong>
			<div class="separator"></div>
		</td>
    </tr>
	
	<tr>
		<td>
			<label for="category_select_type">Select Type:</label>
		</td>
		<td>
			<select name="category_select_type" id="category_select_type">
				<option value="multi">Multiple</option>
				<option value="single"<?php echo $category_select_type == 'single' ? ' selected' : ''; ?>>Single</option>
			</select>
			
			<span class="highlighted">When you select categories</span>
		</td>
	</tr>
	
    <tr>
      <td colspan="2" align="left"><input type="submit" name="settings_save_changes" class="save_changes button" id="settings_save_changes" value="Save Changes"></td>
    </tr>
  </table>
</form>
<div class="separator"></div>