<?
	//include our settings, connect to database etc.

	//include dirname($_SERVER['DOCUMENT_ROOT']).'/cfg/settings.php';


	//AJAX request from the form

	if(isset($_POST['ajax'])) {

		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$errors = array();
		$result = true;
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$date = date('d/m/Y');
		$time = date('H:i:s');  
      

		if(empty($name)) {
			$result = false;
			$errors[] = "Please enter your name";
		}

		if(empty($email)) {
			$result = false;
			$errors[] = "Please enter your email address";
		} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$result = false;
			$errors[] = "Please enter a valid email address";
		}

		if(empty($message)) {
			$result = false;
			$errors[] = "Please enter a message";

		}

		if($result) {
			$headers = "From: test@ephex.net" . "\r\n";  
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$emailbody = "<p>You have received a new message from the enquiries form on your website.</p> 
				<p><strong>Name: </strong> {$name} </p> 
				<p><strong>Email Address: </strong> {$email} </p>  
				<p><strong>Message: </strong> {$message} </p> 
				<p>This message was sent from the IP Address: {$ipaddress} on {$date} at {$time}</p>";  

			mail("bogieman123@hotmail.co.uk","New Enquiry",$emailbody,$headers);  

		}
		echo json_encode(array("result"=>$result, "errors"=>$errors));
		die();
	}
	

	//getting required data
	$pagetitle = "Contact Us";

	$page = "./includes/contact.inc.php";

	include "template.php";
?>