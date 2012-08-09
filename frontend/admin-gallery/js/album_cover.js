
	$(document).ready(function()
	{
		var image_cover = $(".image_cover");
		
		image_cover.click(function(ev)
		{
			ev.preventDefault();
			
			var album_id = $("#album_id").val();
			
			openFrame("admin.php?action=albumcover&album_id=" + album_id);
		});
	});