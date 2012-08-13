<div id="bodyWrapper" style="padding-bottom: 10px;font-size: 13px;">
	<h1>Gallery</h1>

    <?php
      // Parse all images in DESCENDING order
      $all_images = getAllImages('DESC');
      
      echo '<h2>All Images</h2>';

      echo '<div id="folio-wrapper">';
      echo '<table width="850" border="0" cellpadding="5" cellspacing="0" class="table-margin">';
      echo '<tr>';

      $i=0;
      foreach($all_images as $image)
      {
        $id         = $image['ImageID'];
        $album_id       = $image['AlbumID'];
        $image_path     = $gallery_url . $image['ImagePath'];
        $image_name     = $image['Name'];
        $upload_date    = date("d-m-Y H:i", $image['UploadDate']);
        $order_id       = $image['OrderID'];
        $default_thumbnail  = $gallery_url . $image['DefaultThumbnail'];
        $thumbnail_1    = $gallery_url . $image['Thumbnail1'];
        $thumbnail_2    = $gallery_url . $image['Thumbnail2'];
        $thumbnail_3    = $gallery_url . $image['Thumbnail3'];
        
        $i++;

        if ($i > 4) {
          echo '</tr>';
          echo '<tr>';
        }

        echo '<td width="215" align="center" valign="top">';                                     //open column
        echo '<a href="'.$image_path.'" rel="lightbox" title="'.$image_name.'">';    //image
        echo '<img src="'.$thumbnail_1.'"/></a>';
        echo '</td>';                                                                //close column

        if ($i > 4) {
          echo '</tr>';
        }
      }
      echo '</table>';
      echo '</div>';
      ?>
</div>