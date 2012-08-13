
	var win;
	var loader;
	var json_req;
	var images = new Array();
	var uploads_url;
	
	var zIndex_start = 1000000;
	
	var showAlbum = function(album_id, get_image_id)
	{
		var album_header, showing_image, thumbnails_bar;
		var thumbnails_hidden = false;
		
		var onEnablePage = function()
		{
			hideLoader();
			hideAlbum();
		};
		
		disablePage(onEnablePage);
		showLoader();
		
		var json_req = $.getJSON
		(
			"index.php?get_album=" + album_id, 
			function(data)
			{
				// Check If Album Exists
				if( data.album_exists == false )
				{
					alert("Album doesn't exists!");
					
					hideLoader();
					enablePage( $(".page-disabler") );
					
					window.location.hash = "";
					
					return false;
				}
				else
				if( data.hasImages == false )
				{
					alert("This album doesn't contain any image!");
					
					hideLoader();
					enablePage( $(".page-disabler") );
					
					window.location.hash = "";
					
					return false;
				}
				
				uploads_url = data.UploadsURL;
				
				// Open Album
				if( $("body > .album-header").length == 0 )
					$("body").append('<div class="album-header"></div>');
				
				album_header = $("body > .album-header");
				
				var images_in_album = data.imagesCount + " image" + (data.imagesCount == 1 ? '' : 's') + " in this album";
				var album_created_date = data.DateCreated;
				
				
				// Album Header - Album Info
				album_header.append(
					'<div class="ah_wrapper">' +
						'<a class="close_album" href="#">Close Album</a>' + 
						'<h1 class="album_title">' + data.AlbumName + '</h1>' +
						'<div class="album_details">'+images_in_album+' <span>Created on <strong>'+album_created_date+'</strong></span></div>' +
						'<p class="album_description">'+data.AlbumDescription+'</p>' + 
					'</div>'
				);
				
				album_header.find('.album_description').expander({
					expandText: "read more",
					userCollapseText: "[read less]",
					expandEffect: "fadeIn",
					beforeExpand: function()
					{
						showing_image.fadeTo(200, 0.1);
					},
					onCollapse: function()
					{
						showing_image.fadeTo(200, 1);
					}
				});
				
				// Image Info Header
				album_header.append(
					'<div class="iih_wrapper">' +
						'<h1>'+data.Images[0].ImageName+'</h1>' +
						'<div class="current_image_num"><span>1</span> of '+data.imagesCount+'</div>' + 
					'</div>'
				);
				
				album_header.css({
					position: "fixed",
					left: 0,
					top: 0,
					width: win.width(),
					zIndex: zIndex_start+10,
					background: "rgba(0,0,0,.1)"
				})
				.find('.ah_wrapper').css("width", parseInt(win.width() * 0.60));
				
				album_header.find('.iih_wrapper').css(
				{
					position: "fixed",
					width: parseInt(win.width() * 0.30),
					right: 0,
					top: 0,
					zIndex: zIndex_start+11,
					minHeight: $(".ah_wrapper").height()
				});
				
				// Close Album Event
				album_header.find(".close_album").click(function(ev)
				{
					ev.preventDefault();
					
					hideAlbum();
					hideLoader();
					enablePage( $(".page-disabler") );
				});
				
				// Load First Image
				if( data.Images.length > 0 )
				{
					if( get_image_id )
					{
						var img_id = get_image_id;
					}
					else
					{
						var img_id = data.Images[0].ImageID;
					}
					
					if( img_id )
						preloadImage(img_id);
				}
							
				$.each(
					data.Images, 
					function(key, val)
					{
						var thumbnail_url = val.ThumbnailURL;
						
						if( !val.ThumbnailURL.match(/^http/) )
						{
							thumbnail_url = uploads_url + thumbnail_url;
						}
						
						val.preload_thumbnail = new Image();
						val.preload_thumbnail.src = thumbnail_url;
						val.preload_thumbnail.onload = function()
						{
							thumbnailsPreloader(val.ImageID)
						}
						
						val.preload_mainimage = new Image();
						val.loaded = false;
						//val.preload_mainimage.src = val.Thumbnail2;
						
						images.push(val);
					}
				);
				
				createShowingImage();
				createThumbnailsBar();
			}
		).error(
			function()
			{
				alert("Sorry, an error occured while opening album!");
			}
		);
		
		var thumbnails_per_page = 0;
		var current_thumbnails_page = 0;
		var total_thumbnails = 0;
		
		var thumbnail_active;
		
		var unit_width, tb_width;
		
		// Create Showing Image Environment
		var createShowingImage = function()
		{
			if( $("body > .showing-image").length == 0 )
			{
				$("body").append('<div class="showing-image"></div>');
			}
			
			showing_image = $("body > .showing-image");
			
			showing_image.css({
				position: "fixed",
				top: album_header.height(),
				left: 0,
				zIndex: zIndex_start+8
			});
		}
		
		// Thumbnails Preloader
		var thumbnailsPreloader = function(image_id)
		{
			var loader_div = $('.thumbnails_list li[data-imageid="'+image_id+'"] .loading_thumbnail');
			loader_div.fadeTo(200, 0, 
									function()
									{
										loader_div.remove();
									}
			);
		}
		
		
		// Thumbnails Bar
		var createThumbnailsBar = function()
		{			
			// Create Thumbnails Bar
			if( $("body > .album-thumbnails-bar").length == 0 )
				$("body").append('<div class="album-thumbnails-bar"><a href="#" rel="0" class="show_hide_thumbnails"></a></div>');
			
			var album_thumbnails_bar = $("body > .album-thumbnails-bar");
			
			album_thumbnails_bar.css({
				position: "fixed",
				left: 0,
				bottom: 0,
				width: win.width(),
				zIndex: zIndex_start+11
			});
			
			// Show Hide Thumbnails
			album_thumbnails_bar.find('.show_hide_thumbnails').click(function(ev)
			{
				ev.preventDefault();
				
				var $this = $(this);
				
				if( $this.attr("rel") == "0" )
				{
					thumbnails_hidden = true;
					
					album_thumbnails_bar.animate({bottom: -96}, function()
					{
						win.trigger('resize');
					});
					
					$this.css('background-image', 'url(themes/dark_night/images/show-thumbnails.png)');
					$this.attr("rel", "1");
				}
				else
				{
					thumbnails_hidden = false;
					
					album_thumbnails_bar.animate({bottom: 0}, function()
					{
						win.trigger('resize');
					});
					
					$this.css('background-image', 'url(themes/dark_night/images/hide-thumbnails.png)');
					$this.attr("rel", "0");
				}
			});
			
			// Add Thumbnails Wrapper
			album_thumbnails_bar.append
			(
				'<div class="atb_wrapper">' +
				'</div>'
			);
			
			var atb_wrapper = album_thumbnails_bar.find(".atb_wrapper");
			
			
			// Create Thumbnails elements
			thumbnails_bar = $("<ul />", {"class": "thumbnails_list"});
			
			for(var i in images)
			{
				var thumbnail_url = images[i].ThumbnailURL;
				
				if( !images[i].ThumbnailURL.match(/^http/) )
				{
					thumbnail_url = uploads_url+thumbnail_url;
				}

				
				var li_el = $(
								'<li data-imageid="'+images[i].ImageID+'">' +
									'<a href="#">' +
										'<img src="'+thumbnail_url+'" width="80" height="69" alt="" />' +
									'</a>' +
									'<div class="loading_thumbnail"></div>' +
								'</li>'
							);
				
				if( get_image_id && get_image_id == images[i].ImageID )
				{
					var image_id = images[i].ImageID;
					
					li_el.addClass('active');

					//showImage(images[i].ImageID);
					setTimeout(function()
					{
						$('.thumbnails_list li[data-imageid="'+image_id+'"]').trigger('click');	
					}, 1);
				}
				else
				if( !get_image_id && i == 0 )
				{
					li_el.addClass('active');
					showImage(images[i].ImageID);
				}
				else
					li_el.fadeTo(0, 0.25);
				 
				li_el.click(function(ev)
				{
					ev.preventDefault();
					activeImage($(this).attr("data-imageid"));
				});
				
				thumbnails_bar.append(li_el);
			}			
			
			// Options for thumbnails navigation
			unit_width = 90;
			tb_width = win.width();
			
			thumbnails_per_page = parseInt(tb_width/unit_width) - 1;
			total_thumbnails = images.length;
			
			// Set width of Thumbnails navigation bar
			var atb_width = thumbnails_per_page * unit_width - 15;
			var atb_left = parseInt(win.width() / 2 - atb_width / 2) - 32;
						
			atb_wrapper.css({
				width: atb_width,
				left: atb_left
			})
			.append(thumbnails_bar).append( $('<div class="clear"></div>') );
			
			// Add Navigation Buttons
			var thumbs_prev = $('<a href="#" class="atb_nav prev">Previous</a>');
			var thumbs_next = $('<a href="#" class="atb_nav next">Next</a>');
			
			thumbs_prev.click(function(ev)
			{
				ev.preventDefault();
				setThumbnailsBarPage(current_thumbnails_page-1);
			});
			
			thumbs_next.click(function(ev)
			{
				ev.preventDefault();
				setThumbnailsBarPage(current_thumbnails_page+1);
			});
			
			// Assign Prev Next Buttons
			if( thumbnails_per_page < total_thumbnails )
			{
				atb_wrapper.parent().append(thumbs_prev).append(thumbs_next);
			}
			else
			{
				atb_wrapper.css({
					left: 0, 
					margin: 0
				});
			}
			
			// Add Hover Effect for Thumbnails
			thumbnails_bar.find('> li').hover(function()
			{
				var $this = $(this);
				
				if( !$this.hasClass('active') )
					$(this).stop().fadeTo(255, 1);
			},
			function()
			{
				var $this = $(this);
				
				if( !$this.hasClass('active') )
					$(this).stop().fadeTo(255, 0.25);
			});
			
			// Add keyboard arrows for navigation
			win.keyup(function(ev)
			{
				if( ev.keyCode == 37 ) // Prev
				{
					prevImage();
				}
				else
				if( ev.keyCode == 39 ) // Next
				{
					nextImage();
				}
				else
				if( ev.keyCode == 27 )
				{
					hideAlbum();
					hideLoader();
					enablePage( $(".page-disabler") );
				}
			});
		}
		// End of Thumbnails bar
		
		// Update Thumbnails Bar Location
		var setThumbnailsBarPage = function(page)
		{
			var max_pages = Math.ceil( total_thumbnails / thumbnails_per_page );
			
			if( page < 0 )
				current_thumbnails_page = max_pages - 1;
			else
			if( page >= max_pages )
				current_thumbnails_page = 0;
			else
				current_thumbnails_page = page;
			
			var to_the_left = current_thumbnails_page * thumbnails_per_page * 90;
			
			if( to_the_left > 0 )
				to_the_left += 3;
				
			thumbnails_bar.stop().animate({left: -to_the_left}, 1000, 'easeInOutQuart');
		}
		
		// Set Active Image Function
		var activeImage = function(image_id)
		{
			var thumbnails_list = $(".thumbnails_list li");
			
			thumbnail_active = thumbnails_list.filter('[data-imageid="'+image_id+'"]');
			
			if( thumbnail_active.length == 0 )
			{
				alert("Image doesn't exists!");
				return false;
			}
						
			thumbnails_list.removeClass("active");
			thumbnail_active.addClass('active');
			
			// v2.1
			var next_image_id = thumbnail_active.next().attr("data-imageid");
			// end: v2.1
			
			thumbnails_list.filter(':not(.active)').fadeTo(250, 0.25);
			thumbnail_active.fadeTo(250, 1);
			
			var image = getImageById(image_id);
			
			$(".iih_wrapper h1").text(image.ImageName);
			$(".iih_wrapper div.current_image_num span").text(image.RowCount);
			
			// Show Image or video
			var image = getImageById(image_id);
			
			if( image.isVideo )
			{
				showVideo(image_id);
			}
			else
			{
				showImage(image_id);
			}
			
			setThumbnailsBarPage( parseInt( (image.RowCount-1) / thumbnails_per_page) );
			window.location.hash = "image-" + album_id + "-" + image_id;
		}
		
		// Show Video
		var showVideo = function(video_id)
		{
			var video = getImageById(video_id);
					
			if( !video )
			{
				alert("Video doesn't exists!");
				return false;
			}
			
			if( video.isVideo )
			{
				var video_url = video.VideoURL;
				var _video_id = video.VideoID;
				
				switch( video.VideoType )
				{
					case "youtube":
						var video_frame = $('<object data-videoid="'+video_id+'" width="100%" height="100%"><param name="movie" value="http://www.youtube.com/v/'+_video_id+'?version=3"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'+_video_id+'?version=3" type="application/x-shockwave-flash" width="100%" height="100%" allowscriptaccess="always" allowfullscreen="true"></embed></object>');
						video_frame.css({zIndex: zIndex_start+20});
						
						showing_image.width(video.VideoWidth).height(video.VideoHeight).html(video_frame);
						showing_image.center();
						break;
					
					case "vimeo":
						var vimeo_video_url = "http://vimeo.com/moogaloop.swf?clip_id="+_video_id;
						
						var video_frame = $('<object class="vimeo_video" data-videoid="'+video_id+'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'+vimeo_video_url+'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=ff9933&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" /><embed src="'+vimeo_video_url+'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=ff9933&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always"width="100%" height="100%"></embed></object>');
						video_frame.css({zIndex: zIndex_start+20});
						
						showing_image.width(video.VideoWidth).height(video.VideoHeight).html(video_frame);
						showing_image.center();
						break;
				}
				
				win.trigger('resize');
			}
		}
		
		// Show Image
		var showImage = function(image_id)
		{
			var image = getImageById(image_id);
						
			if( !image )
			{
				alert("Image doesn't exists!");
				return false;
			}
			
			if( image.isVideo )
			{
				showVideo(image_id);
				return false;
			}
			
			showing_image.width("auto").height("auto");
			
			var onImageShow = function()
			{
				hideLoader();
				
				var toolbars_height = album_header.height() + (thumbnails_hidden ? 0 : 96);
				
				var image_width = image.width;
				var image_height = image.height;
				var minus_top = 0;
				
				if( image_width > win.width() )
				{
					var p = win.width() / image_width;
					image_width = parseInt(p * image_width);
					image_height = parseInt(p * image_height);
					
					image_width *= 0.93;
				}
				
				if( image_height > win.height() - toolbars_height )
				{
					var p = (win.height() - toolbars_height - 25) / image_height;
					image_width = parseInt(p * image_width);
					image_height = parseInt(p * image_height);
					
					image_width *= 0.93;
				} 
				
				image_width = parseInt(image_width);
				
					
				var image_el = $('<img class="showing_image_el" data-imageid="'+image.ImageID+'" src="'+uploads_url+image.Thumbnail2+'" width="'+image_width+'" />');
				showing_image.html(image_el);
				
				image_el.css({position:"relative", top: -minus_top, zIndex: zIndex_start+9});				 
			
				if( !thumbnails_hidden )
				{
					minus_top = 0.035 * image_height;
					image_el.attr("width", image_width).css({top: -minus_top, marginTop: 0});
					showing_image.center();
				}
				else
				{
					image_el.attr("width", image_width).css({top: 0, marginTop: toolbars_height});
					showing_image.center();
				}
				
				image_el.click(function(ev)
				{
					ev.preventDefault();
					nextImage();
				})
			}
			
			if( image.loaded == false )
			{
				// Show Loader
				showLoader();
				
				// Load image then show it
				preloadImage(image_id, onImageShow);
			}
			else
			{
				// Hide Loader
				hideLoader();
				
				// Show Image
				onImageShow();
			}
		}
		
		// Proload Showing Image
		var preloadImage = function(image_id, callback)
		{
			for(var i in images)
			{
				i = Number(i);
					
				if( images[i].ImageID == image_id )
				{
					images[i].preload_mainimage.src = uploads_url + images[i].Thumbnail2;
					images[i].preload_mainimage.onload = function()
					{
						var width = this.width;
						var height = this.height;
						
						images[i].loaded = true;
						images[i].width = width;
						images[i].height = height;
								
						var img;
						
						if( img = images[i+1] )
						{
							if( img.Thumbnail2 )
							{
								var next_image_load = new Image();
								next_image_load.src = uploads_url + img.Thumbnail2;
								
								next_image_load.onload = function()
								{
									preloadImage(i+2, callback);
								}
							}
						}
							
						if( callback )
							callback();
					}
					
					return true;
				}
			}
		}
		
		// Recenter Eveything after window resize
		win.resize( function()
		{
			// Showing Image Update
			if( !showing_image )
				return false;
				
			var image_el = showing_image.find('img');
			image_id = image_el.attr("data-imageid");
			
			var is_video = false;	
			
			if( image_id )
			{
				var image = getImageById(image_id);
				var image_width = image.width;
				var image_height = image.height;
			}
			else
			{
				var video_el = showing_image.find('object');
				var video = getImageById(video_el.attr("data-videoid"));
				
				var image_width = video.VideoWidth;
				var image_height = video.VideoHeight;
				
				image_width *= 0.9;
				image_height *= 0.9;
				
				is_video = true;
			}
				
			var toolbars_height = album_header.height() + (thumbnails_hidden ? 0 : 96);
			var minus_top = 0;
			
			if( image_width > win.width() )
			{
				var p = win.width() / image_width;
				image_width = parseInt(p * image_width);
				image_height = parseInt(p * image_height);
				
				image_width *= 0.93;
			}
			
			if( image_height > win.height() - toolbars_height )
			{
				var p = (win.height() - toolbars_height - 25) / image_height;
				image_width = parseInt(p * image_width);
				image_height = parseInt(p * image_height);
				
				image_width *= 0.93;
			}
			
			if( is_video && image_width > video.VideoWidth )
			{
				image_width = video.VideoWidth;
			}
			
			if( is_video && image_height > video.VideoHeight )
			{
				image_height = video.VideoHeight;
			}
			
			if( !thumbnails_hidden )
			{
				minus_top = 0.035 * image_height;
				
				if( image_el.length )
				{
					image_el.attr("width", image_width).css({top: -minus_top, marginTop: 0});
				}
				
				
				showing_image.center();
				
				if( is_video )
				{
					var si_width = showing_image.get(0).offsetWidth;
					var si_height = showing_image.get(0).offsetHeight;
					
					showing_image.width(image_width).height(image_height).css({
						top: 0,
						left: 0,
						margin: 0,
						left: "50%",
						top: "50%",
						marginLeft: -parseInt(si_width/2 ,10),
						marginTop: -parseInt(si_height/2 + Math.log(toolbars_height), 10)
					});
					
				}
				
			}
			else
			{
				if( image_el.length )
				{
					image_el.attr("width", image_width).css({top: 0, marginTop: toolbars_height});
				}
				
				if( is_video )
				{
					showing_image.width(image_width).height(image_height).css({top: 0, marginTop: toolbars_height});
				}
				
				showing_image.center();
			}
			
			// Thumbnails Bar Update
			tb_width = win.width();
			thumbnails_per_page = parseInt(tb_width/unit_width) - 1;
			
			var atb_width = thumbnails_per_page * unit_width - 15;
			var atb_left = parseInt(win.width() / 2 - atb_width / 2) - 32;
						
			$(".atb_wrapper").css({
				width: atb_width,
				left: atb_left
			})
			
			$(".album-thumbnails-bar").css({width: win.width()});
		});
		
		// Go to next image
		var nextImage = function()
		{
			if( !thumbnail_active )
				thumbnail_active = $(".thumbnails_list li:first-child");
					
			if( thumbnail_active.next().length == 0 )
			{
				thumbnail_active = thumbnails_bar.find('li:first-child');
			}
			else
			{
				thumbnail_active = thumbnail_active.next();
			}
			
			activeImage(thumbnail_active.attr("data-imageid"));
		}
		
		// Go to previous image
		var prevImage = function()
		{
			if( !thumbnail_active )
				thumbnail_active = $(".thumbnails_list li:first-child");					
			
			if( thumbnail_active.prev().length == 0 )
			{
				thumbnail_active = thumbnails_bar.find('li:last-child');
			}
			else
			{
				thumbnail_active = thumbnail_active.prev();
			}
			
			activeImage(thumbnail_active.attr("data-imageid"));
		}
	}
	
	// Get Image Details
	var getImageById = function(image_id)
	{
		if( !images )
			return false;
			
		for(var i in images)
		{
			images[i].RowCount = Number(i) + 1;
			
			if( images[i].ImageID == image_id )
			{
				return images[i];
			}	
		}
	}
	
	var hideAlbum = function()
	{
		if( images.length )
		{
			// Cancel Image Preloadings
			for(var i in images)
			{
				images[i].preload_mainimage.src = null;
				images[i].preload_thumbnail.src = null;
				
				images[i].preload_mainimage = null;
				images[i].preload_thumbnail = null;
			}
			
			// Clear Array
			images = null;
			images = new Array();
			
			// Unbind Window Settings
			win.unbind();
			
			// Remove Panels
			$("body > .album-header, body > .showing-image, body > .album-thumbnails-bar").fadeOut("normal", "easeOutSine", function()
			{
				$(this).remove();
			});
		}
		
		window.location.hash = "hide";
	}
	
	var disablePage = function(on_enable_page_cb)
	{
		if( $("body > .page-disabler").length == 0 )
			$("body").append('<div class="page-disabler"></div>');
		
		var page_disabler = $("body > .page-disabler");
		page_disabler.unbind();
		
		if( !win )
			win = $(window);
			
		var win_width = win.width();
		var win_height = win.height();
		
		page_disabler.css({
			background: "#000",
			position: "fixed",
			left: 0,
			top: 0,
			width: win_width,
			height: win_height,
			display: "none"
		});
		
		page_disabler.fadeTo(500, 0.9, 'easeOutQuart');
		win.unbind();
		
		win.resize(function()
		{			
			var win_width = win.width();
			var win_height = win.height();
			
			page_disabler.width( win_width );
			page_disabler.height( win_height );
		});
		
		page_disabler.click(function()
		{
			enablePage(page_disabler, on_enable_page_cb);
		});
	}
	
	var enablePage = function(page_disabler, on_enable_page_cb)
	{
		if( page_disabler )
		{
			if( on_enable_page_cb )
				on_enable_page_cb();
				
			page_disabler.stop(true, true).fadeTo(500, 0, 'easeInQuart', function()
			{
				page_disabler.remove();
				win.unbind();
			});
		}
	}
	
	var showLoader = function(text)
	{
		loader.fadeIn("normal", "easeInSine");
	}
	
	var hideLoader = function(text)
	{
		loader.stop(true, true).fadeOut("normal", "easeOutSine");
	}
	
	var animation_delay = 500;
	var visible_albums;
	
	var current_album = 0;
	
	$(document).ready(function()
	{
		// Init core vars
		loader = $(".loader");
		
		$(".albums .album_cover, .albums .album_name").click(function(ev)
		{
			ev.preventDefault();
			
			var id = $(this).parent().parent().parent().attr("data-id");
			showAlbum(id);
			
			window.location.hash = "album-" + id;
		});
		
		// Check Hash URLs
		var get_image_from_url = window.location.hash.toString();
		var values;
		
		if( values = get_image_from_url.match(/^#image\-([0-9]+)\-([0-9]+)$/) )
		{
			album_id = values[1];
			image_id = values[2];
			
			showAlbum(album_id, image_id);
		}
		else
		if( values = get_image_from_url.match(/^#album\-([0-9]+)$/) )
		{
			album_id = values[1];
			
			showAlbum(album_id);
		}
		
		paginationSetup();
		
		// Categorizing
		if( $(".categories").length )
		{
			var sorting_type = $('input[name="dn_category_sorting_effect"]').val();
			
			if( sorting_type == 'absolute' )
			{
				$(".selecterContent li").each(function(i)
				{
					var $this = $(this);
					
					$this.width( 235 ).height( 194+40 );
				});
			}
			
			if( !sorting_type )
				sorting_type = 'fade';
			
			var content = $(".content");
			var browse_category = 'all';
			
			// Get category
			var category_id = window.location.hash.toString().match(/^#category\-([0-9]+)/);			
			
			if( category_id && category_id[1] && $(".category_"+category_id[1]).length )
			{
				browse_category = "category_" + category_id[1];
				current_album = category_id[1];
				 
				setTimeout(function()
				{
					content.height( "auto" );
				}, 1);
			}
			
			content.mobilyselect({
				collection: browse_category,
				animation: sorting_type, // absolute,plain,fade
				duration: animation_delay,
				listClass: 'albums_env',
				btnsClass: 'category_list',
				btnActiveClass: 'active',
				elements: 'li',
				onChange: function(){
					var rel = $(".category_list .active").attr("rel");
					
					current_album = rel.replace('category_', '');
					window.location.hash = "category-" + current_album;
					
					$(".pagination li").hide();
					$(".pagination li a").removeClass('active');
					
					content.height( content.get(0).offsetHeight );
					
				},
				onComplete: function(){
					
					var c_height = content.get(0).offsetHeight;
					var n_height = content.height( "auto" ).get(0).offsetHeight;
					content.height( c_height );
					
					if( c_height < n_height )
						content.height("auto");
					else
						content.stop().animate({height: n_height});
					
				}
			});
			
			paginationSetup();
		}
	});
	
	
	var first_setup = 0;
	var startup_page = 1;
	
	function paginationSetup()
	{
		first_setup++;
		
		var content = $(".content");
		
		if( !visible_albums )
			visible_albums = $(".albums li:visible");
		
		var albums_total = visible_albums.length;
				
		albums_per_page = $('input[name="dn_albums_per_page"]').val();
		
		var pages = Math.ceil( albums_total / albums_per_page );
		
		var pagination = $(".pagination li");
		pagination.hide().find('a').removeClass('active');
		
		visible_albums.find('.album_outer_container').css({left: 0, top: 0});
		
		is_busy = false;
		
		if( pages > 1 )
		{
			var i = 0;			
			
			for(i = 1; i <= pages; i++ )
			{
				var current = pagination.filter(':nth-child('+i+')');
				var current_a = current.find('a');
				
				current.stop(true, true).fadeIn(animation_delay);
				
				current_a.click(function(ev)
				{
					ev.preventDefault();
					
					var pagination_link = $(this);
					
					pagination.find('a').removeClass('active');
					pagination_link.addClass('active');
					
					var page_id = new Number( pagination_link.attr('rel').replace('page_', '') );
					
					activePage(page_id, albums_per_page)
				});
				
				var category_page = window.location.hash.toString().match(/^#category-([0-9]+)-([0-9]+)$/)
				
				if( category_page )
				{
					startup_page = category_page[2];
				}
						
				if( i == startup_page )
				{
					if( first_setup == 1 )
					{	
						activePage(startup_page, albums_per_page, true);
					}
					
					
					current.find('a').addClass('active');
				}
			}
		}
	}
	
	var current_page = 1;
	
	function activePage(number, items_per_page, no_fading)
	{
		items_per_page = new Number(items_per_page);
		current_page = number;
		
		var page_a = items_per_page * (number - 1);
		var page_b = items_per_page * number;
		
		if( no_fading )
			visible_albums.hide();
		else
			visible_albums.fadeOut(animation_delay);
		
		if( first_setup != 1 )
			window.location.hash = "category-"+current_album+"-" + number;
		
		setTimeout(function()
		{
			visible_albums.each(function(i)
			{
				var $this = $(this);
				var item_id = i;
				
				if( item_id >= page_a && item_id < page_b )
				{
					if( no_fading )
					{
						$this.show();
					}
					else
					{
						$this.fadeIn(animation_delay);
					}
				}
			});
		}, no_fading ? 0 : animation_delay);
	}