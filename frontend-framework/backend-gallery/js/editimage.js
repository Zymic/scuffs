
	$(document).ready(function()
	{
		var image_types = $(".image_types li a");
		
		image_types.each(function()
		{
			var $this = $(this);
			
			$this.click(function(ev)
			{
				ev.preventDefault();
				
				$(".image_types li").removeClass("active");
				$this.parent().addClass("active");
				
				var vi = $(".view_image img");
				vi.fadeTo(250, .5).attr("src", "css/images/blank.png");

				var image_source = $this.attr("data-imagesrc");
				var image_id = $this.attr("href").replace('#', '');

				var load_image = new Image();
				load_image.src = image_source;
				load_image.onload = function()
				{
					var w = this.width;
					var h = this.height;
					
					vi.animate({width: w, height: h}, 500, function()
					{
						vi.fadeTo(250, 1);
						vi.attr("src", image_source);
					});
					
					var pww = $(parent.window).width();
			    	var pwh = $(parent.window).height();
			    	   	
			    	var body_height = $("body").height() + 45;
			    	var frame = $(parent.document).find('#image_frame');
			    	
			    	frame.height(body_height + 20);
			    	
			    	var fw = w + 100;
			    	fw = fw < 450 ? 450: fw;
			    	frame.width(fw);
			    	
			    	var frame_width = frame.width();
			    	var frame_height = frame.height();
			    	    	
			    	var frame_top = parseInt(pwh/2 - frame_height/2) + $(parent.window).scrollTop();
			    	var frame_left = parseInt(pww/2 - frame_width/2);
			    	
			    	frame_top = frame_top < 0 ? 0 : frame_top;
			    	frame_left = frame_left < 0 ? 0 : frame_left;
			    	
			    	frame.css({top: frame_top, left: frame_left});
				}
				
				window.location.hash = image_id;
			});
		});
		
		// Retrieve Image Id
		var matches = window.location.hash.toString();
		if( matches.match(/^#[0-3]$/) )
		{
			$(".image_types li a[href="+matches+"]").trigger("click");
		}
		
		// Image Options		
		$(".image_options_form").submit(function(ev)
		{
			var form = $(this);
			var video_url = form.find('#video_url');
			
			if( video_url.length > 0 )
			{					
				if( !is_valid_video(video_url.val()) )
				{
					video_url.css("border", "1px solid red");
					video_url.focus();
					
					return false;
				}
				else
				{
					video_url.css("border", "1px solid #CCC");
				}
			}
		});
	});