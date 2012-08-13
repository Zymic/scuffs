<title>Upgrade Mini Back-end Gallery to v2.1</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<div class="install_env">
<img src="css/images/mbg-logo.png" alt="mbg-logo" width="448" height="32" />
<br />
<h2>Upgrade to v2.1</h2>
<?php

	/**
	 * Mini Back-end Gallery - Powerful Image Gallery
	 *
	 * Version: 2.1
	 * Last Update: October, 2011
	 * 
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 * Author URL: http://codecanyon.net/user/arl1nd
	 *
	 */
	 
	## Detect v2.0 Version ##
	
	$fp = fopen("config.php", "r");
	$config = fread($fp, filesize("config.php"));
	fclose($fp);
	
	
	if( !strstr($config, "Version: 2.1") )
	{
		include_once( dirname(__FILE__) . "/config.php");
		
		
		$con = mysql_pconnect($db_host, $db_user, $db_pass);
		mysql_select_db($db_name) or die(mysql_error());
		
	
		$q_albums = mysql_query("SELECT * FROM `mbg_albums`") or die(mysql_error());
		$q_images = mysql_query("SELECT * FROM `mbg_images`") or die(mysql_error());
		
		$albums = array();
		$images = array();
		
		while($r = mysql_fetch_object($q_albums))
			array_push($albums, $r);
		
		while($r = mysql_fetch_object($q_images))
			array_push($images, $r);
		
		$db_pref = "mbg_v2_";
	
		$queries[] = "DROP TABLE `{$db_pref}images`;";
		$queries[] = "DROP TABLE `{$db_pref}categories_albums`;";
		$queries[] = "DROP TABLE `{$db_pref}albums`;";
		$queries[] = "DROP TABLE `{$db_pref}categories`;";
		$queries[] = "DROP TABLE `{$db_pref}options`;";
		
		$queries[] = "CREATE TABLE `{$db_pref}albums` (
		`AlbumID` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`AlbumName` varchar(255) NOT NULL,
		`Description` text NOT NULL,
		`DateCreated` int(20) NOT NULL,
		`Thumbnail1Size` varchar(10) DEFAULT NULL,
		`Thumbnail2Size` varchar(10) DEFAULT NULL,
		`Thumbnail3Size` varchar(10) DEFAULT NULL,
		`OrderID` int(11) NOT NULL DEFAULT '1',
		`AlbumCover` int(11) unsigned DEFAULT NULL,
		`Params` longtext NOT NULL,
		PRIMARY KEY (`AlbumID`),
		KEY `AlbumCover` (`AlbumCover`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		
		$queries[] = "CREATE TABLE `{$db_pref}categories` (
		`CategoryID` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`Name` varchar(255) NOT NULL,
		`Description` longtext NOT NULL,
		`OrderID` int(11) NOT NULL DEFAULT '1',
		PRIMARY KEY (`CategoryID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		
		$queries[] = "CREATE TABLE `{$db_pref}categories_albums` (
		`CategoryID` int(11) unsigned NOT NULL,
		`AlbumID` int(11) unsigned NOT NULL,
		PRIMARY KEY (`CategoryID`,`AlbumID`),
		KEY `AlbumID` (`AlbumID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
		$queries[] = "CREATE TABLE `{$db_pref}images` (
		`ImageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`AlbumID` int(11) unsigned NOT NULL,
		`Type` enum('image','video') NOT NULL DEFAULT 'image',
		`ImagePath` varchar(255) NOT NULL,
		`VideoURL` longtext NOT NULL,
		`Name` varchar(255) NOT NULL,
		`Description` longtext NOT NULL,
		`UploadDate` int(20) NOT NULL,
		`Params` longtext NOT NULL,
		`OrderID` int(20) NOT NULL,
		PRIMARY KEY (`ImageID`),
		KEY `AlbumID` (`AlbumID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		
		$queries[] = "CREATE TABLE `{$db_pref}options` (
		`option_name` varchar(100) NOT NULL,
		`option_value` longtext,
		PRIMARY KEY (`option_name`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		
		$queries[] = "ALTER TABLE `{$db_pref}categories_albums`
		ADD CONSTRAINT `{$db_pref}categories_albums_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `{$db_pref}categories` (`CategoryID`),
		ADD CONSTRAINT `{$db_pref}categories_albums_ibfk_2` FOREIGN KEY (`AlbumID`) REFERENCES `{$db_pref}albums` (`AlbumID`);";
		
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('title', 'Mini Back-end Gallery v2.1')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('admin_username', '".addslashes($admin_username)."')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('admin_password', '".addslashes($admin_password)."')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('images_path', 'uploads/')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('naming', 'hash')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('thumbnail_1_size', 'x')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('thumbnail_2_size', 'x')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('thumbnail_3_size', 'x')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('fe_installed', '0')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('fe_theme_selected', '0')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('fe_url', '')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('fe_path', '')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('current_theme', '')";
		$queries[] = "INSERT INTO `{$db_pref}options` VALUES('category_select_type', 'multi')";
		
		
		$queries[] = "DROP TABLE `mbg_images`;";
		$queries[] = "DROP TABLE `mbg_albums`;";
		
		foreach($queries as $query)
		{
			@mysql_query($query);
		}
		
		
		foreach($albums as $album)
		{
			mysql_query(
				sprintf(
					"INSERT INTO `{$db_pref}albums`(`AlbumID`,`AlbumName`,`Description`,`DateCreated`,`Thumbnail1Size`,`Thumbnail2Size`,`Thumbnail3Size`,`OrderID`,`AlbumCover`) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')",
					mysql_real_escape_string($album->AlbumID),
					mysql_real_escape_string($album->AlbumName),
					mysql_real_escape_string($album->Description),
					mysql_real_escape_string($album->DateCreated),
					mysql_real_escape_string($album->Thumbnail1Size),
					mysql_real_escape_string($album->Thumbnail1Size),
					mysql_real_escape_string($album->Thumbnail1Size),
					mysql_real_escape_string($album->OrderID),
					mysql_real_escape_string($album->AlbumCover)
				)
			) or die(mysql_error());
		}
		
		
		
		foreach($images as $image)
		{
			mysql_query(
				sprintf(
					"INSERT INTO `{$db_pref}images` (`ImageID`,`AlbumID`,`ImagePath`,`Name`,`UploadDate`,`OrderID`) VALUES('%s','%s','%s','%s','%s','%s')",
					mysql_real_escape_string($image->ImageID),
					mysql_real_escape_string($image->AlbumID),
					mysql_real_escape_string($image->ImagePath),
					mysql_real_escape_string($image->Name),
					mysql_real_escape_string($image->UploadDate),
					mysql_real_escape_string($image->OrderID)
				)
			);
		}
		
		
		$new_config = trim('
<?php

	/**
	 * Mini Back-end Gallery - Powerful Image Gallery
	 *
	 * Version: 2.1
	 * Last Update: October, 2011
	 * 
	 * Created by: Arlind Nushi
	 * Email: arlindd@gmail.com
	 * Author URL: http://codecanyon.net/user/arl1nd
	 *
	 */
	 
	$db_host = "'.$db_host.'";
	$db_user = "'.$db_user.'";
	$db_pass = "'.$db_pass.'";
	$db_name = "'.$db_name.'";
	$db_pref = "'.$db_pref.'";
?>
');
		
		
		$fp = fopen("config.php", "w");
		fwrite($fp, $new_config);
		fclose($fp);
		
		?>
		Mini Back-end Gallery has been updated to newest version (v2.1).
		You can now continue to <a href="admin.php">gallery admin panel &raquo;</a>
		<br />
		<br />
		<em>For security purposes, please delete this file!</em>
		<?php
		
		mysql_close($con);
	}
	else
	{
		?>
		Your MBG is up to date. <a href="admin.php">Click to continue &raquo;</a>
		<?php
	}
	
	function option_update($name, $val = "")
	{
		mysql_query( sprintf("INSERT INTO `mbg_options` (`option_name`,`option_value`) VALUES('%s','%s')", mysql_real_escape_string($name), mysql_real_escape_string($val)) );
	}
?>
</div>