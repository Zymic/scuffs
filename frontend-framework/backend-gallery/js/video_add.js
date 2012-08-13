
	var form_disabled = false;
	
	$(document).ready(function()
	{
		var add_video_button = $(".add_video_button");
		var video_error = $(".video_error");
		var video_load = $(".video_load");
		
		add_video_button.click(function(ev)
		{
			ev.preventDefault();
			
			addVideoDialog();
		});
		
		$("#add_video_dialog form").submit(function(ev)
		{
			if( form_disabled )
			{
				return false;
			}
			
			ev.preventDefault();
			
			var form = $(this);
			
			var pass = false;
			var type = null;
			
			var video_url = form.find('#video_url');
			
			if( video_url.val().match(/^http:\/\/(?:(www|[a-z0-9]{1,3})\.)?youtube.com\/watch\?(?=.*v=\w+)(?:\S+)?$/) )
			{
				video_error.slideUp("normal");
				pass = true;
				type = 'youtube';
			}
			else
			if( video_url.val().match(/^http:\/\/(www\.)?vimeo\.com\/(\d+)$/) )
			{
				video_error.slideUp("normal");
				pass = true;
				type = 'vimeo';
			}
			else
			{
				video_error.fadeIn("normal");
				video_url.focus();
			}
			
			if( pass )
			{
				form_disabled = true;
				
				video_load.fadeIn("normal");
				
				$.post("?add_video=" + $("#album_id").val(), {video_url: video_url.val()}, function(data)
				{
					var response;
					eval("response = " + data);
					
					if( response.errors )
					{
						alert("An error occured while adding this video!\n\nError: [" + response.error_msg+ "]" );
						return false;
					}
					
					// If there is no image element remove
					$(".noimages").remove();
					
					video_url.val("");
					video_load.hide();
					
					$("#add_video_dialog").dialog("close");
					
					var video_id = response.video_info.ImageID;
					var video_name = response.video_info.Name;
					var video_thumbnail = response.video_info.Params.thumbnail_url;
					var video_url_o = response.video_info.VideoURL;
					
					// Append HTML
					var video_item = "";
					
					video_item += '<li class="image video radius" data-imageid="'+video_id+'">';
					
					video_item += '<div class="video_icon"></div>';
					
					video_item += '<img src="'+video_thumbnail+'" width="110" height="95" alt="" />';
					video_item += '<div class="clear"></div>';
					
					
					/* Image Checkbox and Name */
                    video_item += '<div class="check_button">';
                    video_item += '<label class="image_name '+(video_name ? '' : 'noname')+'" for="image_id_'+video_id+'">'+(video_name ? video_name : '(No name)')+'</label>';
                    video_item += '<input type="checkbox" name="selected_images[]" class="image_checkbox" id="image_id_'+video_id+'" value="'+video_id+'" />';
                    video_item += '</div>';
                    /* Image Checkbox and Name */
					
					video_item += '<div class="image_options_dialog radius">';
					video_item += '<ul>';
					
					// Edit / Change Video
					video_item += '<li>';
					video_item += '<a href="admin.php?action=editimage&id='+video_id+'" class="edit_video">Edit / Change</a>';
					video_item += '</li>';
					
					// Play Video
					video_item += '<li>';
					video_item += '<a href="'+video_url_o+'" class="play_video" target="_blank">Play Video</a>';
					video_item += '</li>';
					
					// Delete
					video_item += '<li>';
					video_item += '<a href="#" class="delete_image">Delete</a>';
					video_item += '</li>';
					
					video_item += '</ul>';
					video_item += '</div>';
					
					video_item += '</li>';
					// EOF: Append HTML
					
									
					// Append Video HTML
					var video_element = $(video_item).appendTo(".album_images");
					
					video_element.click(image_check_function);
					video_element.hover(image_hover, image_hover_out).find('.image_options_dialog').hide();
					video_element.hide().show("puff");
					
					deleteImgEvent( video_element.find('.delete_image') );
					addSortableEvent();

					
					form_disabled = false;
				});
			}
		});
	});
	
	
	function addVideoDialog()
	{
		$("#add_video_dialog").dialog({
			width: 500,
			height: "auto",
			title: 'Add Video to Album',
			modal: true,
			show: 'scale',
			hide: 'scale'
		});
		
		$("#add_video_dialog input").focus();
		
		$(".ui-widget-overlay").css({background: "#111"});
	}
	