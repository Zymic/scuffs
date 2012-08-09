<?php

	/* Category Functions implemented in MBG v2.1 */
	
	function categoryExists($category_id)
	{
		$prefix = dbprefix();
		
		$q = mysql_query(
			sprintf(
				"SELECT * FROM `{$prefix}categories` WHERE `CategoryID` = '%d'",
				$category_id
			)
		);
		
		return mysql_num_rows($q) ? true : false;
	}
	
	function getCategories($order = 'ASC')
	{
		$prefix = dbprefix();
		
		$q = mysql_query(
			sprintf(
				"SELECT t1.*, COUNT(t2.`AlbumID`) `Albums` FROM `{$prefix}categories` t1 LEFT JOIN `{$prefix}categories_albums` t2 ON t1.`CategoryID` = t2.`CategoryID` GROUP BY t1.`CategoryID` ORDER BY t1.`OrderID` %s",
				$order
			)
		) or die(mysql_error());
		
		$arr = array();
		
		while($r = mysql_fetch_array($q))
			array_push($arr, $r);
		
		return $arr;
	}
	
	function getAlbumsForCategory($category_id)
	{
		$prefix = dbprefix();
		
		$q = mysql_query(
			sprintf(
				"SELECT t1.*, t2.`CategoryID` FROM `{$prefix}albums` t1 LEFT JOIN `{$prefix}categories_albums` t2 ON t1.`AlbumID` = t2.`AlbumID` GROUP BY t1.`AlbumID` ORDER BY t1.`OrderID` ASC",
				$category_id
			)
		) or die(mysql_error());
		
		return iterate($q);
	}
	
	function getAlbumCategories($album_id)
	{
		$prefix = dbprefix();
		
		$q = mysql_query(
			sprintf(
				"SELECT t1.*, COUNT(t2.`AlbumID`) `Albums` FROM `{$prefix}categories` t1 LEFT JOIN `{$prefix}categories_albums` t2 ON t1.`CategoryID` = t2.`CategoryID` WHERE t2.`AlbumID` = '%d' GROUP BY t1.`CategoryID` ORDER BY t1.`OrderID` ASC",
				$album_id
			)
		) or die(mysql_error());
		
		$arr = array();
		
		while($r = mysql_fetch_array($q))
			array_push($arr, $r);
		
		return $arr;
	}
	
	
	function getCategory($category_id)
	{
		$prefix = dbprefix();
		
		$q = mysql_query(
			sprintf(
				"SELECT t1.*, COUNT(t2.`AlbumID`) `Albums` FROM `{$prefix}categories` t1 LEFT JOIN `{$prefix}categories_albums` t2 ON t1.`CategoryID` = t2.`CategoryID` WHERE t1.`CategoryID` = '%d' GROUP BY t1.`CategoryID`",
				$category_id
			)
		) or die(mysql_error());
		
		while($r = mysql_fetch_array($q))
			return $r;
		
		return null;
	}
	
	
	function addCategory($name, $description = '')
	{
		$prefix = dbprefix();
		
		$order_id = categoryNextOrderId();
				
		mysql_query(
			sprintf(
				"INSERT INTO `{$prefix}categories`(`Name`,`Description`,`OrderID`) VALUES('%s','%s','%d')",
				mysql_real_escape_string($name),
				mysql_real_escape_string($description),
				$order_id
			)
		);
	}
	
	
	function editCategory($id, $name, $description = '')
	{
		$prefix = dbprefix();
		
		if( categoryExists($id) )
		{
		
			mysql_query(
				sprintf(
					"UPDATE `{$prefix}categories` SET `Name` = '%s', `Description` = '%s' WHERE `CategoryID` = '%d'",
					mysql_real_escape_string($name),
					mysql_real_escape_string($description),
					$id
				)
			);
		
			return true;
		}
		
		return false;
	}
	
	function deleteCategory($category_id)
	{
		$prefix = dbprefix();
		
		if( getCategory($category_id) )
		{
			$q = mysql_query(
				sprintf(
					"DELETE FROM `{$prefix}categories_albums` WHERE `CategoryID` = '%d'",
					$category_id
				)
			) or die(mysql_error());
			
			$q = mysql_query(
				sprintf(
					"DELETE FROM `{$prefix}categories` WHERE `CategoryID` = '%d'",
					$category_id
				)
			) or die(mysql_error());
			
			return true;
		}
		
		return false;
	}
	
	
	function removeCategories($album_id)
	{
		$prefix = dbprefix();
		
		if( albumExists($album_id) )
		{
			$q = mysql_query(
				sprintf(
					"DELETE FROM `{$prefix}categories_albums` WHERE `AlbumID` = '%d'",
					$album_id
				)
			) or die(mysql_error());
			
			return true;
		}
		
		return false;
	}
	
	function removeCategory($album_id, $category_id)
	{
		$prefix = dbprefix();
		
		if( albumExists($album_id) && getCategory($category_id) )
		{
			$q = mysql_query(
				sprintf(
					"DELETE FROM `{$prefix}categories_albums` WHERE `AlbumID` = '%d' AND `CategoryID` = '%d'",
					$album_id,
					$category_id
				)
			) or die(mysql_error());
			
			return true;
		}
		
		return false;
	}
	
	function categoryNextOrderId()
	{
		$q = mysql_query( 
			sprintf(
				"SELECT MAX(`OrderID`) FROM `".dbprefix()."categories`",
				$album_id
			) 
		);
		
		$r = mysql_fetch_row($q);
		
		return $r[0] + 1;
	}
	
	function assignAlbumToCategory($album_id, $category_id)
	{
		$prefix = dbprefix();
		
		if( albumExists($album_id) && categoryExists($category_id) )
		{
			mysql_query(
				sprintf(
					"INSERT INTO `{$prefix}categories_albums` (`AlbumID`,`CategoryID`) VALUES('%d','%d')",
					$album_id,
					$category_id
				)
			);
		}
	}
	
	function setAlbumCategory($album_id, $categories)
	{
		$prefix = dbprefix();
		
		if( albumExists($album_id) )
		{
			if( is_array($categories) )
			{
				mysql_query(
					sprintf(
						"DELETE FROM `{$prefix}categories_albums` WHERE `AlbumID` = '%d'",
						$album_id
					)
				) or die(mysql_error());
				
				foreach($categories as $category)
				{
					assignAlbumToCategory($album_id, $category);
				}
			}
			else
			{
				assignAlbumToCategory($album_id, $category);
			}
		}
	}
?>