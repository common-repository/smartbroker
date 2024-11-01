<?php
function pagination_links($total_rows, $start = 1, $length = 5) {
	$old_page = $_GET;
		
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$total_pages = $total_rows;
	
	/* Setup vars for query. */
	$limit = $length; 					//how many items to show per page
	$page = (($start - 1)/$length) + 1;
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$prev_link_array = $old_page;
	$prev_link_array['st'] = (empty($prev_link_array['st'])) ? '' : $prev_link_array['st'];	
	$prev_link_array['st'] = $prev_link_array['st'] - $length;
	if ($prev_link_array['st'] < 1) {
		$prev_link_array['st'] = 1;
		}
	$prev_link = http_build_query($prev_link_array).'#results';
	
	$next = $page + 1;							//next page is page + 1
	$next_link_array = $old_page;
	$next_link_array['st'] = (empty($next_link_array['st'])) ? '' : $next_link_array['st'];	
	if ($next_link_array['st'] == '') {
		$next_link_array['st'] = 1;
		}
	$next_link_array['st'] = $next_link_array['st'] + $length;
	$next_link = http_build_query($next_link_array).'#results';
	
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	/* 
		Now we apply our rules and draw the pagination object. 
	*/
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\"><p>&nbsp;";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"?$prev_link\">&laquo; previous</a>";
		else
			$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
		
		//next button
		if ($page < $lastpage) 
			$pagination.= " <a href=\"?$next_link\">next &raquo;</a>";
		else
			$pagination.= " <span class=\"disabled\">next &raquo;</span>";
		$pagination.= "</p></div>\n";		
		}
	return $pagination;
	}
	?>