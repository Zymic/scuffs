<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mini Back-end Gallery v2.1</title>
<link href="css/api.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Mini Back-end Gallery v2.1 - API</h1>
<h5>Gallery module created by  Arlind Nushi</h5>
<h6>Last update: October 12, 2011</h6>
<p>Defined functions:</p>

	<div class="functions_env">
    	Album Functions<br />
        <ul>
        	<li><a href="#getAllAlbums">getAllAlbums</a>($order)</li>
        	<li><a href="#getAlbumItems" class="new_function">getAlbumItems</a>($album_id, $order)</li>
        	<li><a href="#albumExists">albumExists</a>($album_id)</li>
        	<li><a href="#getAlbum">getAlbum</a>($album_id)</li>
        	<li><a href="#totalAlbums">totalAlbums</a>()</li>
        	<li><a href="#moveAlbumUP">moveAlbumUP</a>($album_id)</li>
        	<li><a href="#moveAlbumDOWN">moveAlbumDOWN</a>($album_id)</li>
        	<li><a href="#deleteAlbum">deleteAlbum</a>($album_id)</li>
        	<li><a href="#getAlbumImages">getAlbumImages</a>($album_id, $order)</li>
        	<li><a href="#editAlbum">editAlbum</a>($aid, $aname, $desc, $th1, $th2, $th3)</li>
        	<li><a href="#showAlbums">showAlbums</a>($options=array())</li>
        	<li><a href="#countAlbumImages">countAlbumImages</a>($album_id)</li>
        	<li><a href="#setAlbumCover">setAlbumCover</a>($album_id, $image_id, $set_unset = true)</li>
        </ul>
        
        <br />
        Image Functions
        <ul>
        	<li><a href="#imageExists">imageExists</a>($image_id)</li>
        	<li><a href="#getImage">getImage</a>($image_id)</li>
        	<li><a href="#deleteImage">deleteImage</a>($image_id)</li>
        	<li><a href="#setImageName">setImageName</a>($image_id, $name)</li>
            <li><a href="#getAllImages">getAllImages</a>($order)</li>
            <li><a href="#moveImage">moveImage</a>($image_id, $album_id)</li>
        	<li><a href="#showImages">showImages</a>($options=array())</li>
        	<li><a href="#totalImages">totalImages</a>()</li>
        	<li><a href="#generateThumbnails" class="new_function">generateThumbnails</a>($image_id)</li>
        	<li><a href="#cropImage" class="new_function">cropImage</a>($image_id, $crop_x1, $crop_y1, $crop_w, $crop_h)</li>
        	<li><a href="#imageChangeFilePath" class="new_function">imageChangeFilePath</a>($image_id, $image_path)</li>
        	<li><a href="#uploadImage" class="new_function">uploadImage</a>($album_id, $image)</li>
        	<li><a href="#setImageDescription" class="new_function">setImageDescription</a>($image_id, $description)</li>
        	<li><a href="#setImageParams" class="new_function">setImageParams</a>($image_id, $params)</li>
        	<li><a href="#getImageParams" class="new_function">getImageParams</a>($image_id)</li>
        </ul>
        
        <br />
        Video Functions
        <ul>
        	<li><a href="#getVideo" class="new_function">getVideo</a>($video_id)</li>
        	<li><a href="#addVideo" class="new_function">addVideo</a>($album_id, $video_url)</li>
        </ul>
        
        <br />
        Categories Functions
        <ul>
        	<li><a href="#categoryExists" class="new_function">categoryExists</a>($category_id)</li>
        	<li><a href="#getCategory" class="new_function">getCategory</a>($category_id)</li>
        	<li><a href="#getCategories" class="new_function">getCategories</a>($order)</li>
        	<li><a href="#getAlbumCategories" class="new_function">getAlbumCategories</a>($album_id)</li>
        	<li><a href="#getAlbumsForCategory" class="new_function">getAlbumsForCategory</a>($category_id)</li>
            <li><a href="#addCategory" class="new_function">addCategory</a>($name, $description)</li>
            <li><a href="#editCategory" class="new_function">editCategory</a>($category_id, $name, $description)</li>
            <li><a href="#deleteCategory" class="new_function">deleteCategory</a>($category_id)</li>
        	<li><a href="#removeCategories" class="new_function">removeCategories</a>($album_id)</li>
        	<li><a href="#removeCategory" class="new_function">removeCategory</a>($album_id, $category_id)</li>
        	<li><a href="#assignAlbumToCategory" class="new_function">assignAlbumToCategory</a>($album_id, $category_id)</li>
        	<li><a href="#setAlbumCategory" class="new_function">setAlbumCategory</a>($album_id, $categories)</li>
        </ul>
        
        <br />
        Other Functions
        <ul>
        	<li><a href="#get_option" class="new_function">get_option</a>($option_name)</li>
        	<li><a href="#update_option" class="new_function">update_option</a>($option_name, $option_value)</li>
        	<li><a href="#getVideoInfo" class="new_function">getVideoInfo</a>($video_url)</li>
        </ul>
	</div>
    
    
    <div class="function" id="getAllAlbums">
    	<h1>function : getAllAlbums($order = 'ASC') - <a href="#top">top</a></h1>
        <p>Get all images on the database.</p>
        
        Function parameters
        <div class="param"><span>$order</span> - Set the order of fetched albums. Accepted values: ASC or DESC.</div>
        
        Return type
        <div class="param"><span>Array</span></div>
    </div>
    
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getAlbumItems">
    	<h1>function : getAlbumItems($album_id, $order = 'ASC') <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get all album items (images and videos) in sequential order.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        <div class="param"><span>$order</span> - Set the order of fetched albums. Accepted values: ASC or DESC.</div>
        
        Return type
        <div class="param"><span>Array</span> - <strong>getImage</strong> type array</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <div class="separator"></div>
    
    <div class="function" id="albumExists">
    	<h1>function : albumExists($album_id) - <a href="#top">top</a></h1>
        <p>Checks whether album exists or not.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="getAlbum">
    	<h1>function : getAlbum($album_id) - <a href="#top">top</a></h1>
        <p>Get album information as an array or null value if album id doesn't exists.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Array</span> (AlbumID, AlbumName, Description, DateCreated, Thumbnail1Size, Thumbnail2Size, Thumbnail3Size, OrderID, AlbumCover)</div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="totalAlbums">
    	<h1>function : totalAlbums() - <a href="#top">top</a></h1>
        <p>Get total number of albums created.</p>
        
        Function parameters
        <div class="param">No parameters</div>
        
        Return type
        <div class="param"><span>Integer</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="moveAlbumUP">
    	<h1>function : moveAlbumUP($album_id) - <a href="#top">top</a></h1>
        <p>Change the order of albums. Move $album_id upper from the nearest album.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="moveAlbumDOWN">
    	<h1>function : moveAlbumDOWN($album_id) - <a href="#top">top</a></h1>
        <p>Change the order of albums. Move $album_id under the nearest album.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="deleteAlbum">
    	<h1>function : deleteAlbum($album_id) - <a href="#top">top</a></h1>
        <p>Delete an album and all of its images (if there is any on it). Returns <strong><em>true</em></strong> if the action is done successfully or <strong><em>false</em></strong> if album doesn't exists!</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="getAlbumImages">
    	<h1>function : getAlbumImages($album_id, $order = ASC) - <a href="#top">top</a></h1>
        <p>Get all album images from <em><strong>$album_id</strong></em> in the ASCENDING order by default.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        <div class="param"><span>$order</span> - Order of images you get from album. Accepted values: ASC or DESC</div>
        
        Return type
        <div class="param"><span>Array</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="editAlbum">
    	<h1>function : editAlbum($album_id, $album_name, $description = '', $thumbnail1 = null, $thumbnail2 = null, $thumbnail3 = null) - <a href="#top">top</a></h1>
        <p>This functions changes album properties such as name, description and thumbnail sizes.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        <div class="param"><span>$album_name</span> - New album name</div>
        <div class="param"><span>$description</span> - New album description</div>
        <div class="param"><span>$thumbnail1</span> - Size of first thumbnail that will be generated after upload (empty value will not create thumbnail)</div>
        <div class="param"><span>$thumbnail2</span> - Size of second thumbnail (accepted format <em><strong>number</strong></em>x<strong><em>number</em></strong> i.e. <strong><em>100</em></strong>x<strong><em>125</em></strong>)</div>
        <div class="param"><span>$thumbnail3</span> - Size of third thumbnail</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    
    
    <div class="separator"></div>
    
    <div class="function" id="showAlbums">
    	<h1>function : showAlbums($options = array()) <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Parse all albums into an UL element.</p>
        
        Function parameters
        <div class="param"><span>$options</span> - Filter options (backend_url, no_cover_img, order, class, show_covers, cover_image, link_id)</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="countAlbumImages">
    	<h1>function : countAlbumImages($album_id) <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Get number of images into an album.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of album</div>
        
        Return type
        <div class="param"><span>Integer</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="setAlbumCover">
    	<h1>function : setAlbumCover($album_id, $image_id, $set_unset = true) <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Set album cover image.</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of album</div>
        <div class="param"><span>$image_id</span> - The ID of image to be as cover of album</div>
        <div class="param"><span>$set_unset</span> - True if you want to set, or false if you want to remove cover</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    
    
    <div class="separator"></div>
    
    <div class="function" id="imageExists">
    	<h1>function : imageExists($image_id) - <a href="#top">top</a></h1>
        <p>Check whether given image id exists or not.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    <div class="separator"></div>
    
	<div class="function" id="getImage">
    	<h1>function : getImage($image_id) - <a href="#top">top</a></h1>
        <p>Get specific image (or even video) based on ID.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Array</span> (ImageID, AlbumID, Type, ImagePath, Name, UploadDate, OrderID, DefaultThumbnail, Thumbnail1, Thumbnail2, Thumbnail3)</div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="deleteImage">
    	<h1>function : deleteImage($image_id) - <a href="#top">top</a></h1>
        <p>Delete an image. True value will be returned if it has been deleted, otherwise false value will indicate that image doesn't exists.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Album ID</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="setImageName">
    	<h1>function : setImageName($image_id, $name) - <a href="#top">top</a></h1>
        <p>Set the name of an image.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Album ID</div>
        <div class="param"><span>$name</span> - New image name</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="getAllImages">
    	<h1>function : getAllImages($order = 'ASC') - <a href="#top">top</a></h1>
        <p>Get all images on the database.</p>
        
        Function parameters
        <div class="param"><span>$order</span> - Set the order of images. Accepted values: ASC or DESC.</div>
        
        Return type
        <div class="param"><span>Array</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="moveImage">
    	<h1>function : moveImage($image_id, $album_id) <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Get all images on the database.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - The ID of image to move.</div>
        <div class="param"><span>$album_id</span> - The ID of album to receive that image.</div>
        
        Return type
        <div class="param"><span>Boolean</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="showImages">
    	<h1>function : showImages($options = array()) <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Show images from one album or all albums into an UL element.</p>
        
        Function parameters
        <div class="param"><span>$options</span> - Images filter options (backend_url, order, class, image_size, album_id, link_id, show_name)</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    
    <div class="separator"></div>
    
    <div class="function" id="totalImages">
    	<h1>function : totalImages() <sup class="new">new</sup> - <a href="#top">top</a></h1>
        <p>Count all images in the database.</p>
        
        Function parameters
        <div class="param">No parameters</div>
        
        Return type
        <div class="param"><span>Integer</span></div>
    </div>
    
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="generateThumbnails">
    	<h1>function : generateThumbnails($image_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Re-generate image thumbnails using original image based on album thumbnail sizes</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Image ID to re-create thumbnails</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if image exists</div>
    </div>    
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="cropImage">
    	<h1>function : cropImage($image_id, $crop_x1, $crop_y1, $crop_w, $crop_h) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Crop an image by selecting X,Y area and A,B width</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Image ID to Crop</div>
        <div class="param"><span>$crop_x1</span> - Horizontal start location</div>
        <div class="param"><span>$crop_y1</span> - Vertical start location</div>
        <div class="param"><span>$crop_w</span> - Width from Horizontal Location</div>
        <div class="param"><span>$crop_h</span> - Height from Vertical Location</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if image exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="imageChangeFilePath">
    	<h1>function : imageChangeFilePath($image_id, $image_path) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Switch Image File Path</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Image ID</div>
        <div class="param"><span>$image_path</span> - New Image Path</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if image exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="uploadImage">
    	<h1>function : uploadImage($album_id, $image) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Switch Image File Path</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID where Image is going to be added</div>
        <div class="param"><span>$image</span> - $_FILES variable</div>
        
        Return type
        <div class="param"><span>Integer</span> - <strong>-1</strong> = Album Not Exists, 0 = Invalid Image Type, 1 = Image Uploaded to Album</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="setImageDescription">
    	<h1>function : setImageDescription($image_id, $description) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Set description of an image.</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Album ID</div>
        <div class="param"><span>$description</span> - Description of Image</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="setImageParams">
    	<h1>function : setImageParams($image_id, $params) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>New Image Feature! You can store additional information inside an image via <em>Params</em> array!</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Image ID</div>
        <div class="param"><span>$params</span> - Array</div>
        
        Return type
        <div class="param"><span>Boolean</span> - true if image exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getImageParams">
    	<h1>function : getImageParams($image_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get <em>Params</em> array with stored datas per image</p>
        
        Function parameters
        <div class="param"><span>$image_id</span> - Image ID</div>
        
        Return type
        <div class="param"><span>Array</span> (mixed data)</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
	<div class="function" id="getVideo">
    	<h1>function : getVideo($video_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>The same as getImage function, but this only get videos</p>
        
        Function parameters
        <div class="param"><span>$video_id</span> - Video (image) ID</div>
        
        Return type
        <div class="param"><span>Array</span> (ImageID, AlbumID, Type, ImagePath, VideoURL, Name, UploadDate, OrderID, DefaultThumbnail, Thumbnail1, Thumbnail2, Thumbnail3, Params)</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
	<div class="function" id="addVideo">
    	<h1>function : addVideo($album_id, $video_url) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Add video to Album (Youtube or Vimeo)</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - Album ID</div>
        <div class="param"><span>$video_url</span> - YouTube or Vimeo URL</div>
        
        Return type
        <div class="param"><span>Boolean</span> - true if album exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="categoryExists">
    	<h1>function : categoryExists($category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Check Existence of Category</p>
        
        Function parameters
        <div class="param"><span>$category_id</span> - The category ID</div>
        
        Return type
        <div class="param"><span>Boolean</span> - true if category found</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getCategory">
    	<h1>function : getCategory($category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get Category details by ID</p>
        
        Function parameters
        <div class="param"><span>$category_id</span> - The category ID</div>
        
        Return type
        <div class="param"><span>Array</span> (CategoryID, Name, Description, OrderID, Albums - [count])</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getCategories">
    	<h1>function : getCategories($order) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get the list of all created categories</p>
        
        Function parameters
        <div class="param"><span>$order</span> - Order Type</div>
        
        Return type
        <div class="param"><span>Array([],[],…,[])</span> (CategoryID, Name, Description, OrderID, Albums - [count])</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getAlbumsForCategory">
    	<h1>function : getAlbumsForCategory($category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get albums related to a category ID</p>
        
        Function parameters
        <div class="param"><span>$category_id</span> - Category ID</div>
        
        Return type
        <div class="param"><span>Array()</span> - <b>getAlbumItems</b> type array</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getAlbumCategories">
    	<h1>function : getAlbumCategories($album_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Get categories contained by a specific album</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The Album ID you want to get categories</div>
        
        Return type
        <div class="param"><span>Array</span> (CategoryID, Name, Description, OrderID, Albums - [count])</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="addCategory">
    	<h1>function : addCategory($name, $description = '') <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Create new Category</p>
        
        Function parameters
        <div class="param"><span>$name</span> - Category Name (required)</div>
        <div class="param"><span>$description</span> - Category Description (optional)</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="editCategory">
    	<h1>function : editCategory($category_id, $name, $description = '') <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Edit existing Category</p>
        
        Function parameters
        <div class="param"><span>$category_id</span> - The ID of Category to be edited</div>
        <div class="param"><span>$name</span> - Category Name</div>
        <div class="param"><span>$description</span> - Category Description</div>
        
        Return type
        <div class="param"><span>Boolean</span> - true if category has been edited</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="deleteCategory">
    	<h1>function : deleteCategory($category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Delete a category</p>
        
        Function parameters
        <div class="param"><span>$category_id</span> - The ID of Category to be deleted</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if category deleted</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="removeCategories">
    	<h1>function : removeCategories($album_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Remove all categories assigned to an album</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of Album</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if category exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="removeCategory">
    	<h1>function : removeCategory($album_id, $category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Remove specific category assigned to an album</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of Album</div>
        <div class="param"><span>category_id</span> - The ID of Category</div>
        
        Return type
        <div class="param"><span>Boolean</span> - True if category and album exists</div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="assignAlbumToCategory">
    	<h1>function : assignAlbumToCategory($album_id, $category_id) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Connect Category with Album</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of Album</div>
        <div class="param"><span>$category_id</span> - The ID of Category</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="setAlbumCategory">
    	<h1>function : setAlbumCategory($album_id, $categories) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Connect Category with Album, the same as <strong>assignAlbumToCategory</strong> but this function accepts an array of category ID's to assign at the current selected Album ID</p>
        
        Function parameters
        <div class="param"><span>$album_id</span> - The ID of Album</div>
        <div class="param"><span>$categories</span> - Array with Category ID's (can be also single ID - numeric value)</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="get_option">
    	<h1>function : get_option($option_name) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>This WordPress like function its the new way which MBG saves settings. It is faster, better and easier to use! Just get a value of an option in the MBG database instance</p>
        
        Function parameters
        <div class="param"><span>$option_name</span> - Option Name to Parse</div>
        
        Return type
        <div class="param"><span>String</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="update_option">
    	<h1>function : update_option($option_name, $option_value) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Create or update new variable on the database usable by <strong>get_option</strong> function</p>
        
        Function parameters
        <div class="param"><span>$option_name</span> - Option Name to Create/Modify</div>
        <div class="param"><span>$option_value</span> - Value to save inside the <strong>$option_name</strong> variable</div>
        
        Return type
        <div class="param"><span>Void</span></div>
    </div>
    <!-- End: v2.1 Function -->
    
    
    <!-- v2.1 Function -->
    <div class="separator"></div>
    
    <div class="function" id="getVideoInfo">
    	<h1>function : getVideoInfo($video_url) <sup class="new_v2_1">new</sup> - <a href="#top">top</a></h1>
        <p>Parse core information for video (YouTube and Vimeo currently)</p>
        
        Function parameters
        <div class="param"><span>$video_url</span> - Video URL (only: Vimeo or Youtube)</div>
        
        Return type
        <div class="param"><span>Array</span> (video_type, video_id, thumbnail_url, title) - video_type: youtube,vimeo</div>
    </div>
    <!-- End: v2.1 Function -->


	<div class="note">
    	An example how you can use some of these functions can be found on <a href="example.php">this file</a>.
    </div>
	
</body>
</html>