<?php
	$name = "";
	$desc = "";
	
	
	if( $category_edit )
	{
		$name = $category_edit['Name'];
		$desc = $category_edit['Description'];
	}
?>

<h1><?php echo $category_edit ? 'Edit' : 'Add'; ?> Category</h1></p>
<script type="text/javascript" src="js/categories.js"></script>

<div class="edit_gallery radius">
	
	<form action="?action=<?php echo $_GET['action']; ?>" enctype="multipart/form-data" method="post" id="add_category_form">
	
		<?php
		if( $category_edit )
		{
			?>
			<input type="hidden" name="category_id" value="<?php echo $_GET['edit']; ?>" />
			<?php
		}
		?>
		<table border="0" cellpadding="2" cellspacing="2">
			<tbody>
				<tr>
					<td>Category Name:</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="category_name" id="category_name" class="input" size="50" value="<?php echo $name; ?>" />
					</td>
				</tr>
				
				<tr>
					<td>Description:</td>
				</tr>
				<tr>
					<td>
						<textarea type="text" name="category_description" id="category_description" class="input" cols="65" rows="8"><?php echo $desc; ?></textarea>
					</td>
				</tr>
				
				<?php
				
				if( $category_edit )
				{
					$albums = getAlbumsForCategory($category_edit['CategoryID']);
				?>
				<tr>
					<td>Albums in this Category:</td>
				</tr>
				<tr>
					<td>
						<ul class="albums_list">
							<li>
							<?php
								foreach($albums as $album)
								{
									$album_id = $album['AlbumID'];
									$album_name = $album['AlbumName'];
									$album_cid = $album['CategoryID'];
									
								?>
								<label>
									<input type="checkbox" name="selected_albums[]" value="<?php echo $album_id; ?>" <?php echo $album_cid == $category_edit['CategoryID'] ? 'checked="checked"' : ''; ?> />
									<?php echo $album_name; ?>
								</label>
								<?php
								}
							?>
							</li>
						</ul>
					</td>
				</tr>
				<?php
				}
				
				?>
				
				<tr>
					<td style="padding-top:5px;">
						<input type="submit" name="<?php echo $category_edit ? 'edit_category' : 'create_category'; ?>" id="<?php echo $category_edit ? 'edit_category' : 'create_category'; ?>" class="button" value="<?php echo $category_edit ? 'Edit Category' : 'Create Category'; ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		
	</form>
	
</div>

<a href="?action=<?php echo $_GET['action']; ?>" class="go_back">&laquo; Go Back</a>

<div class="separator"></div>