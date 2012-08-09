<?php

	if( defined('_INSTALLATION_COMPLETED_') )
	{
		?>
		<h1>Verification Succeed</h1>
		<h3>Thank you for purchasing Mini Back-end Gallery v2.1</h3>
		<p>If you have any question about this product please contact the author by clicking <a href="http://codecanyon.net/user/arl1nd/" target="_blank">here</a>.</p>
		
		<a href="admin.php" class="button">Gallery Admin Dashboard &raquo;</a>
		<?php
	}
	else
	{
?>
<h1>Purchase Verification</h1>
<p>Please verify that you've purchased this copy of <strong><em>Mini Back-end Gallery v2</em></strong> from <strong>CodeCanyon</strong>.</p>

<p><strong>Regular license</strong> - Single domain installation</p>
<p><strong>Extended license</strong> - Multiple domain installation</p>

<form action="" enctype="multipart/form-data" method="post" class="edit_gallery radius">
	<table border="0" cellpadding="2" cellspacing="2">
		<tr>
			<th align="left">Domain:</th>
		</tr>
		<tr>
			<td>
				<input type="text" name="domain" id="domain" value="<?php echo $_SERVER['HTTP_HOST']; ?>" class="input" size="50" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<th align="left" valign="bottom" height="20">Item Purchase Code:</th>
		</tr>
		<tr>
			<td>
				<input type="text" name="purchase_code" id="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" class="input" size="50" />
				<span class="description">To get <strong>Item Purchase Code</strong> login on your CodeCanyon account then go to Dashboard &raquo; Downloads &raquo Mini Back-end Gallery v2 - Powerful Image Gallery &raquo; <strong>Licence Certificate</strong></span>
			</td>
		</tr>
		<tr>
			<td valign="bottom" height="44">
				<input type="submit" name="" id="" class="button" value="Complete Installation" />
			</td>
		</tr>
	</table>
</form>
<?php
	}
?>
<div class="separator"></div>