<?php
	$q = mysql_query("SELECT *, (SELECT COUNT(*) FROM `".dbprefix()."images` WHERE `AlbumID` = t1.AlbumID) total_images FROM `".dbprefix()."albums` t1 ORDER BY `OrderID` ASC");
?>
<a href="?action=new_album" class="button right_button">Create New Album</a>
<h1>Manage Albums</h1></p>
<script type="text/javascript" src="js/albums.js"></script>
<table class="data_table" width="100%" border="0" cellspacing="0" cellpadding="0">
 <thead>
  <tr>
    <th width="38" align="left">Cover</th>
    <th width="240" align="left">Album Name</th>
    <th width="140" align="left">Category</th>
    <th width="140" align="left">Date Created</th>
    <th width="80" align="left">Images</th>
    <th align="left">Options</th>
  </tr>
 </thead>
 
 <tbody>
 <?php
 while($r = mysql_fetch_array($q))
 {
	 $id = $r['AlbumID'];
	 $name = $r['AlbumName'];
	 $description = $r['Description'];
	 
	 $date_c = date("M d, Y", $r['DateCreated']);
	 $total_images = $r['total_images'];
	 
	 $categories = getAlbumCategories($id);
	 
	 $album_cover_img = "css/images/album_cover.png";
	 if( $r['AlbumCover'] )
	 {
	 	$album_cover = getImage($r['AlbumCover']);
	 	$album_cover_img = $album_cover['DefaultThumbnail'];
	 }
	 
	 if( $description )
	 {
	 	$name .= '<p class="description">' .$description . '</p>';
	 }
 ?>
  <tr class="album_entry" data-albumid="<?php echo $id; ?>">
  	<td width="38">
		<a href="?action=album&amp;id=<?php echo $id; ?>" class="image_cover radius" title="Go to this album" style="padding:2px">
			<img src="<?php echo $album_cover_img; ?>" alt="album_cover" width="30" />
		</a>
  	</td>
    <td width="240"><a href="?action=album&amp;id=<?php echo $id; ?>"><?php echo $name; ?></a></td>
    <td width="140" class="gray">
    <?php 
    	if( !count($categories) )
    	{
    		echo "/";
    	}
    	
    	foreach($categories as $i => $category)
    	{
    		if( $i > 0 )
    			echo "<br />";
    		
    		echo $category['Name'];
    	}
    ?>
    </td>
    <td width="140" class="gray"><?php echo $date_c; ?></td>
    <td width="80"><?php echo $total_images; ?></td>
    <td>
	    <a href="?action=album&amp;id=<?php echo $id; ?>" class="manage">Manage Album</a> 
	    <a href="?deletealbum=<?php echo $id; ?>" class="delete">Delete Album</a> 
	    <!-- <a href="?moveup=<?php echo $id; ?>" class="up">Up</a> 
	    <a href="?movedown=<?php echo $id; ?>" class="down">Down</a> -->
    </td>
  </tr>
 <?php
 }
 
 if( !mysql_num_rows($q) )
 {
	 ?>
     <tr>
      <td colspan="6">
      	<div class="gray padding_out">
      		There is no any album created. <a href="admin.php?action=new_album">Click here to create new album &raquo;</a>
      	</div>
      </td>
     </tr>
     <?php
 }
 ?>
 </tbody>
</table>

<div class="loader">Loading...</div>