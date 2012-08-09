<?php

	# Do not change this URL in order to setup correctly MBG v2.1
	# If you change this URL I would not guarantee anymore for this product!
	define("VERIFY_GATEWAY", "http://arlindnushi.dervina.com/mbg-v2/verify/purchase_verifier.php");
	
	if( !function_exists('mbg_v21') && $_GET['action'] != 'verify_purchase' )
	{
		header("Location: ?action=verify_purchase");
	}
	
	
	if( $_POST['purchase_code'] )
	{
		$arr = array("verify_purchase" => $_POST['purchase_code']);
		mbgMakeRequest($arr);
	}
	
	
	function mbgMakeRequest($params = array())
	{
		$req_url = VERIFY_GATEWAY;
		
		$req_url .= '?';
		
		if( $verify_purchase = $params['verify_purchase'] )
		{
			$req_url .= 'verify_purchase='.trim($verify_purchase).'&';
		
			$req_url .= 'host=' . str_replace("www.", "", $_SERVER['HTTP_HOST']) . "&";
			
			$script_url = !empty($_SERVER['HTTPS']) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$script_url .= '/';
			$req_url .= 'script_url=' . base64_encode(dirname($script_url));
					
			
			$contents = get_url($req_url);
			$response = json_decode( $contents );	
			
			if( $response->errors )
			{
				define("_ERROR_", $response->response . " (Response Code: ".$response->response_code.")");
			}
			else
			{				
				if( $response->response_code == 3 )
				{
					$key = $response->key;
					$host = str_replace("www.", "", $_SERVER['HTTP_HOST']);
					$skey = $host . $key;
					
					$a = new ANCrypt($skey);
					
					$fp = fopen("functions_v21.php", "w+");
					fwrite($fp, $a->decrypt($response->mbg_functions_v21));
					fclose($fp);
					
					
					if( $a->decrypt($response->verify_code) == $verify_purchase )
					{
						update_option("key", $key);
						update_option("purchase_code", $verify_purchase);
						update_option("verification_code", $response->verify_code);
						
						define("_INSTALLATION_COMPLETED_", true);
					}
					
				}
				
				define("_SUCCESS_", $response->response . " (Response Code: ".$response->response_code.")");
			}
		}
	}
?>