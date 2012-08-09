
	$(document).ready(function()
	{
		$(".confirm_category").unbind().click(function(ev)
		{
			if( !confirm('Confirm Category Deletion') )
			{
				ev.preventDefault();
			}
		});
		
		$("#categories_table").sortable({
			items: '.category_item',
			axis: 'y',
			update: function()
			{
				var toArray = $(this).sortable("toArray");
				
				
				showLoader("Saving current order…");
				
				$.post("?category_ordering=1", {new_ordering: toArray}, function(data)
				{
					hideLoader();
				});
			}
		});
		
		$("#add_category_form").submit(function(ev)
		{
			var form = $(this);
			
			var name = form.find('#category_name');
			
			if( name.val().length == 0 )
			{
				ev.preventDefault();
				
				alert("Please enter at least category name!");
				name.focus();
				
				return false;
			}
		});
	});