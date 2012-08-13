
	$(document).ready(function()
	{
		$('.data_table .delete').click(function()
		{
			if( !confirm('Are you sure you want to delete this album?') )
				return false;
		});
		
		$(".tooltip").tipsy({fade: true, gravity: "w", offset: 100}).click(function(ev)
		{
			ev.preventDefault();
		});
	});
	
	
	function showLoader(message)
	{
		$(".loader").show().html(message);
	}
	
	function hideLoader()
	{
		$(".loader").hide();
	}