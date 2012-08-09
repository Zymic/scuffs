<?php

	// Check for editing
	if( $cid = $_GET['edit'] )
	{
		$category_edit = getCategory($cid);
	}
	
	if( !$category_edit )
		$categories = getCategories('ASC');

	if( isset($_GET['add_category']) )
	{
		include_once("category_add.php");
	}
	else
	if( $category_edit )
	{
		include_once("category_add.php");
	}
	else
	{
	?>
	
<a href="?action=<?php echo $_GET['action']; ?>&add_category" class="button right_button">Add New Category</a>

<h1>Categories</h1></p>
<script type="text/javascript" src="js/categories.js"></script>

<table class="data_table" id="categories_table" width="100%" border="0" cellspacing="0" cellpadding="0">
 <thead>
  <tr>
    <th width="38" align="left">ID</th>
    <th width="500" align="left">Category Name</th>
    <th width="150" align="left">Albums Count</th>
    <th align="left">Category Options</th>
  </tr>
 </thead>
 
 <tbody>
 <?php
 if( count($categories) )
 {
 	foreach($categories as $category)
 	{
 		$id = $category['CategoryID'];
 		$name = $category['Name'];
 		$description = $category['Description'];
 		$albums_count = $category['Albums'];
 		
 		$description = $description ? $description : "No Description";
 	?>
 	<tr class="category_item" id="<?php echo $id; ?>">
 		<td width="38"><?php echo $id; ?></td>
 		<td width="500">
 			<?php echo $name; ?>
 			<p class="description"><?php echo $description; ?></p>
 		</td>
 		<td width="150"><?php echo $albums_count; ?></td>
 		<td>
 			<a href="?action=<?php echo $_GET['action']; ?>&edit=<?php echo $id; ?>" class="edit">Edit</a>
 			<a href="?action=<?php echo $_GET['action']; ?>&delete_category=<?php echo $id; ?>" class="delete confirm_category">Delete</a>
 		</td>
 	</tr>
 	<?php
 	}
 }
 else
 {
 	?>
     <tr>
      <td colspan="3">
      	<div class="gray padding_out">
	      	There is no any category. <a href="admin.php?action=categories&add_category">Click here to create new category &raquo;</a>
      	</div>
      </td>
     </tr>
     <?php
 }
 ?>
 </tbody>
</table>

<div class="loader">Loading…</div>
<?php 
	}
?>