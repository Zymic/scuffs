
	$(document).ready(function()
	{
		$(".image_options_dialog").fadeTo(0,0);
		
		var album_id = $("#album_id").val();
		
		$("#upload_image").uploadify(
		{
			auto			: true,
			uploader		: 'js/jquery.uploadify-v2.1.4/uploadify.swf',
			script			: 'admin.php?album_id='+album_id,
			
			displayData		: 'speed',
			queueID			: 'imagesQueue',
			
			buttonImg 		: 'css/images/upload-images.png',
			buttonText		: 'Upload Images',
			width			: 134,
			height			: 45,
			
    		cancelImg 		: 'js/jquery.uploadify-v2.1.4/cancel.png',
    		
			wmode			: 'transparent',
			multi			: true,
			
			fileDataName	: 'upload_image',
			
			onError 		: function(event,ID,fileObj,errorObj)
							{
								console.log( errorObj );
							},

			
			onComplete 		: function(event, ID, fileObj, response, data)
							{
								if( response.match(/Fatal error/) )
								{
									var element = $("#upload_image"+ID);
									element.addClass('uploadifyError');
									
									element.find('.percentage').after('<hr />' + response);
									
									return false;
								}
								
								eval("response = eval("+response+")");
								if( response.errors )
								{
									alert("An error occured and image cannot be uploaded!\n\nError Code: [" + response.errors+ "]" );
								}
								else
								{
									var new_image_element = '<li class="images radius" data-imageid="'+response.ImageID+'">';
									
									/* Thumbnail Image */
									new_image_element += '<img src="'+response.thumbnailUrl+'" width="110" height="95" alt="" />';
									/* Thumbnail Image */
									
									new_image_element += '<div class="clear"></div>';
									
									/* Image Checkbox and Name */
				                    new_image_element += '<div class="check_button">';
				                    new_image_element += '<label class="image_name noname" for="image_id_'+response.ImageID+'">(No name)</label>';
				                    new_image_element += '<input type="checkbox" name="selected_images[]" class="image_checkbox" id="image_id_'+response.ImageID+'" value="'+response.ImageID+'" />';
				                    new_image_element += '</div>';
				                    /* Image Checkbox and Name */
				                    
				                    /* Image Options (hover) */
				                    new_image_element += '<div class="image_options_dialog radius">';
				                    
					                    new_image_element += '<ul>';
					                    
						                    new_image_element 		+= '<li>';
						                    	new_image_element 	+= '<a href="admin.php?action=editimage&id='+response.ImageID+'" class="edit_image">Edit / Change</a>';
						                    new_image_element 		+= '</li>';
						                    
						                    new_image_element 		+= '<li>';
						                    	new_image_element 	+= '<a href="?action=cropimage&id='+response.ImageID+'" class="crop_image">Crop</a>';
						                    new_image_element 		+= '</li>';
						                    
						                    new_image_element 		+= '<li>';
						                    	new_image_element 	+= '<a href="#" class="delete_image">Delete</a>';
						                    new_image_element 		+= '</li>';
					                    
					                    new_image_element += '</ul>';
				                    
				                    new_image_element += '</div>';
				                     /* Image Options (hover) */
				                    
									new_image_element += '</li>';
									
									var image_element = $(new_image_element).appendTo(".album_images");
									
									image_element.click(image_check_function);
									image_element.hover(image_hover, image_hover_out).find('.image_options_dialog').hide();
									image_element.hide().show("puff");
									
									deleteImgEvent( image_element.find('.delete_image') );
									addSortableEvent();
									
									
									var number = Number( $(".images_uploaded_num").html() );
									
									$(".select_unselect_all_items").show();
									
									if( number + 1 == 1 )
										$(".plural").text("");
									else
										$(".plural").text("s");
									
									$(".images_uploaded_num").html( number+1 );
									
									$(".noimages").remove();
								}
							}
		});
		
		addSortableEvent();
		
		$(".delete_image").each(function()
		{
			var $this = $(this);
			deleteImgEvent($this);
		});
		
		$(".edit_img").each(function()
		{
			var $this = $(this);
			editImgEvent($this);
		});
		
		$(".crop_image").each(function()
		{
			var $this = $(this);
			cropImgEvent($this);
		});
	});
	
	function deleteImgEvent($this)
	{
		$this.click(function()
		{
			var $this = $(this);
			var parent = $this.parent();
			parent = parent.parent();
			parent = parent.parent();
			
			var li_element = parent.parent();
			
			//var li_element = $(this).parent().parent().parent().parent();
			var image_id = li_element.attr("data-imageid");
			
			if( confirm('Are you sure you want to delete this item?') )
			{
				li_element.find('.image_options_dialog').remove();
				
				$.ajax(
				{
					url: "admin.php?deleteimageid="+image_id,
					beforeSend: function()
					{
						showLoader("Deleting image...");
					},
					success: function(text)
					{
						li_element.hide("explode", function()
						{
							li_element.remove();
							updateImageNumber();
						});
						
						hideLoader();
					}
				});
			}
			
			return false;
		});
	}
	
	function editImgEvent($this)
	{
		$this.click(function(ev)
		{
			ev.preventDefault();
			
			var li_element = $(this).parent();
			var image_id = li_element.attr("data-imageid");
			
			var url = 'admin.php?action=editimage&id='+image_id;
			
			openFrame(url);
						
			return false;
		});
	}
	
	
	
	function cropImgEvent($this)
	{
		return false;
		
		$this.click(function(ev)
		{			
			var image_id = $(this).parent().parent().attr("data-imageid");
			
			var url = 'admin.php?action=cropimage&id='+image_id;
			window.open(url, "editImageName", "width=900,height=650,scrollbars=yes");
			
			return false;
		});
	}
	
	var element_dragged = false;
	
	function addSortableEvent()
	{
		$(".album_images").sortable(
		{
			update: function()
			{
				element_dragged = true;
				
				var new_order = "";
				var incrementor = 0;
				
				$(".album_images li").each(function()
				{
					if( incrementor > 0 )
						new_order += ",";
						
					new_order += incrementor + "=" + $(this).attr("data-imageid");
					
					incrementor++;
				});
				
				$.ajax(
				{
					url: "admin.php?images_new_order=" + $("#album_id").val() + "&order_string=" + new_order,
					beforeSend: function()
					{
						showLoader("Saving current images order...");
					},
					success: function( resp )
					{
						hideLoader();
					}
				});
			}
		});
		
		$(".album_images").disableSelection();
	}

	
	function updateImageNumber()
	{
		var total_images = $(".album_images .image").length;
		$(".images_uploaded_num").text(total_images);
		
		if( total_images == 0 )
			$(".select_unselect_all_items").hide();
		else
			$(".select_unselect_all_items").show();
		
		if( total_images == 1 )
			$(".plural").text("");
		else
			$(".plural").text("s");
	}
	
	// Version 1.1
	var image_check_function = function()
	{	
		elementsChecked();
	}
	
	function image_hover(ev)
	{
		var image_options = $(this).find(".image_options_dialog");
		
		image_options.stop().fadeTo(300, 1);
	}
	
	function image_hover_out(ev)
	{
		var image_options = $(this).find(".image_options_dialog");
		
		image_options.stop().fadeTo(300, 0);
	}
	
	$(document).ready(function()
	{
		if( $(".rename_image").length > 0 )
		{
			$(".check_button").css("paddingTop", "10px");
		}
		
		$(".album_images li").click(image_check_function);
		$(".album_images li").hover(image_hover, image_hover_out);
		
		$(".rename_image").click(function()
		{
			$(this).find('input').focus();
		});
		
		$("#rename_images").click(function()
		{
			$("#group_action_type").val("do_rename");
		});
		
		$(".confirm").click(function()
		{
			if( !confirm('[DELETE] Are you sure you want to take this action?') )
			{
				return false;
			}
		});
		
		// Group Action Events
		$("#delete_btn").click(function()
		{
			$("#group_action_type").val("delete");
		});
		
		$("#rename_btn").click(function()
		{
			$("#group_action_type").val("rename");
		});
		
		$("#change_album_btn").click(function()
		{
			$("#group_action_type").val("move");
			$(".move_to_another_album").fadeIn("slow");
			$(this).fadeOut();
		});
		
		// Select - unselect all
		$(".select_unselect_all_items a").click(function(ev)
		{
			ev.preventDefault();
			
			var rel = $(this).attr("rel");
			
			switch( rel )
			{
				case "unselect":
					$(".image_checkbox").attr("checked", false);
					break;
				
				default:
					$(".image_checkbox").attr("checked", true);
			}
			
			elementsChecked();
			
			return false;
		});
	});
	
	function elementsChecked()
	{
		var checked_elements = $(".image_checkbox:checked");
		
		if( checked_elements.length > 0 )
		{
			$(".selection_action").slideDown("normal");
		}
		else
		{
			$(".selection_action").stop(true, true).slideUp("normal");
		}
	}
	
	$(document).ready(function()
	{
		$(".image_checkbox").mouseup(function()
		{
			elementsChecked();
		});
		
		$(".crop_image").click(function(ev)
		{
			/*
			ev.preventDefault();
			
			var image_id = $(this).parent().parent().attr("data-imageid");
			
			var url = 'admin.php?action=cropimage&id='+image_id;
			window.open(url, "editImageName", "width=900,height=650");
			*/
		});
	})