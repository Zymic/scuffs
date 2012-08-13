<?php
	
	if( $dn_show_categories )
	{
		?>
		<div class="categories selecterBtns">
		
			<ul class="category_list">
				<li>
					<a href="#" rel="all" class="active">All</a>
				</li>
			<?php
			
				foreach($categories as $category)
				{
					$category_id = $category['CategoryID'];
					$name = $category['Name'];
				?>
				<li>
					<a href="#" rel="category_<?php echo $category_id; ?>"><?php echo $name; ?></a></li>
				</li>
				<?php
				}
			
			?>
				<div class="clear"></div>
			</ul>
			
			<div class="clear"></div>
			
		</div>
		<?php
	}
?>