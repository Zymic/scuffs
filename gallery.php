<?
	//include our settings, connect to database etc.

	//include dirname($_SERVER['DOCUMENT_ROOT']).'/cfg/settings.php';

	//getting required data
	$pagetitle = "Gallery";

	$page = "./includes/gallery.inc.php";

	//GALLERY ADMIN STUFF
	   // Wherever you are, you need to specify URL to your gallery Back-end
	  $gallery_url = 'frontend-framework/backend-gallery/';
	  
	  // We need to create MySQL so to that we include file called mysql.open.php on the gallery back-end directory, and also include config file
	  // Make sure your back-end gallery path is correctly entered in order to make gallery front-end work properly
	  include('frontend-framework/backend-gallery/config.php');
	  include('frontend-framework/backend-gallery/mysql.open.php');
	  
	  // Include API functions of Gallery from gallery back-end directory
	  include('frontend-framework/backend-gallery/functions.php');

	include "template.php";


?>