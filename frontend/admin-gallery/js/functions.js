
	function is_valid_video(url)
	{
		if( url.match(/^http:\/\/(?:(www|[a-z0-9]{1,3})\.)?youtube.com\/watch\?(?=.*v=\w+)(?:\S+)?$/) ) 
			return true;
		
		if( url.match(/^http:\/\/(www\.)?vimeo\.com\/(\d+)$/) )
			return true;
		
		return false;
	}