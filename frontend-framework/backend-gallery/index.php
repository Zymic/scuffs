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
	 
	error_reporting(E_ERROR);
	
	session_start();
	
	
	include_once("config.php");
	include_once("mysql.open.php");
	
	include_once("functions.php");
	
	if( isset($_POST['login']) )
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if( $username == $admin_username && md5($password) == md5($admin_password) )
		{
			loginUser($username, $password);
		}
		else
		{
			define("_ERROR_", "Invalid username/password combination");
		}
	}
	
	if( isLoggedIn($admin_username, $admin_password) )
	{
		header("Location: admin.php");
	}
	
	$test_connection = @mysql_connect($db_host, $db_user, $db_pass);
	if( file_exists("install.php") === true && !$test_connection )
	{
		header("Location: install.php");
		exit;
	}
	else
	{
		$select_db = @mysql_select_db($db_name);
		
		if( !$select_db )
			header("Location: install.php");
			
		@mysql_close($test_connection);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body class="login">
<?php
	if( defined("_SUCCESS_") )
	{
?>
<div class="success">
	<?php echo _SUCCESS_; ?>
</div>
<?php
	}
?>

<div class="logo">
	<img src="css/images/mbg-logo.png" alt="mbg-logo" width="453" height="31" />
</div>
	
<div class="login_wrapper">

	<div class="wrp">
		<?php
			if( defined("_ERROR_") )
			{
		?>
		<div class="error">
			<?php echo _ERROR_; ?>
		</div>
		<?php
			}
		?>
		<h2>Please Login</h2>
		<form id="form1" name="form1" method="post" action="">
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td><label for="username">Username:</label>
		      <input name="username" type="text" class="input" id="username" value="<?php echo $username; ?>" size="40" /></td>
		    </tr>
		    <tr>
		      <td><label for="password">Password:</label>
		      <input name="password" type="password" class="input" id="password" <?php if( $username ){ ?>autofocus<?php } ?> size="40" /></td>
		    </tr>
		    <tr>
		      <td>
		      	<br />
		      	<input type="submit" class="button" name="login" id="login" value="Administration" />
		      </td>
		    </tr>
		  </table>
		</form>
	</div>
</div>

<div class="copyrights" align="center">
&copy; Mini Back-end Gallery <strong>v2.1</strong> created by <a href="mailto:arlindd@gmail.com">Arlind Nushi</a>
</div>
</body>
</html>