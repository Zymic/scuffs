
	function openFrame(frame_url)
	{
		/** Version 1.1 Frame */		
		var page_disabler_opacity = 0.8;
		var page_disabler_delay = 500;
		
		var body = $("body");
			
		if( $(".page-disabler").length == 0 )
			var pd = $('<div class="page-disabler"></div>').appendTo( body );
		
		if( $(".frame-dialog").length == 0 )
			var frame = $('<iframe id="image_frame" width="910" frameborder="0" height="100" src="'+frame_url+'" class="frame-dialog"></iframe>').appendTo( $("body") );
			
		frame.fadeTo(0,0);
		frame.load(function()
		{
			frame.fadeTo(500, 1);
		});
		
		var win = $(window);
		var ww = win.width(),
			wh = win.height();
		
		pd.css({position:'fixed', top:0, left: 0, background: '#000', zIndex: 20000, display: 'block', opacity: 0});
		pd.fadeTo(page_disabler_delay, page_disabler_opacity, 'easeOutQuart');
		
		pd.width( ww ).height( wh );
		pd.unbind();
		pd.click(function()
		{
			var pd = $("body > .page-disabler");
			
			pd.stop(true, true).fadeOut(page_disabler_delay, function()
			{
				pd.remove();
			});
			
			frame.remove();
			body.unbind('keyup');
			win.unbind('resize');
			
		});
		
		
		var frame_top = parseInt(wh / 2 - frame.height() / 2);
		var frame_left = parseInt(ww / 2 - frame.width() / 2);
		frame_top = frame_top < 0 ? 0 : frame_top;
		frame_left = frame_left < 0 ? 0 : frame_left;
		
		frame.css({position:'absolute', top:frame_top, left: frame_left, zIndex: 20005, display: 'block'});
		
		// Events
		body.keyup(function(e)
		{
			if( e.keyCode == 27 )
			{
				pd.trigger('click');
			}
		});
		
		win.resize(function()
		{
			var ww = win.width(),
				wh = win.height();
			
			pd.width(ww).height(wh);
		});
		/** EOF Update */
	}