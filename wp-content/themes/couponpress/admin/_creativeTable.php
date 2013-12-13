<?php

class CreativeTable{
 
	var $sql_query;							// SQL query for all the data
	var $data;									// data to build the table (data gathering)
	var $search; 					 			// search selected (data gathering)
	var $multiple_search; 	 		// multi search selected (data gathering)
	var $items_per_page;				// items per page selected (data gathering)
	var $sort;						 			// selected column 1a_2d_t_t (data gathering)
	var $page;									// selected page (for the sql query)

	var $id;										// id of the table
	var $class;									// class of the table
	var $form_init;							// true, false show or not to show the form
	var $form_url;							// form url
	var $header;					 			// text for the header i.e. 'ID,Movie Title,Any Text,...'
	var $width;						 			// ''; '15,100,200,50'
	var $search_init; 			 		// false, true, ttftt
	var $search_html; 			 		// html with search configuration
	var $multiple_search_init;	// hide, true, false, ttftt, ttftt hide
	var $items_per_page_init;		// false; 10,20,50,100; ($i+1)*10
	var $items_per_page_all; 		// text for the show all option: All; false; #TOTAL_ITEMS#
	var $items_per_page_url; 		// index.php or javascript: myFunc();
	var $sort_init;							// true, false ttftt
	var $sort_order;						// 'adt';'ad';'da';'dat'; (ascending, descending, true)
	var $sort_url;							// index.php or javascript: myFunc();
	var $extra_cols;		 				// array containing the the information about extra columns array(array(col,header,width,html),array(...),...)
	var $odd_even;			 				// true, false
	var $no_results;						// false; html for the no results
	var $actions;								// array containing the value and the text of the select box array(array($value,$text),...)
	var $actions_url;						// text function when the select box of actions is changed
	var $pager;									// external html pager

	var $total_items;						// total items (got from sql query)
	var $sql_fields;				 		// sql fields (got from sql query)
	var $out;								 		// output of the table

	function table($params){
		global $tpl, $wpdb;

		// Default Values
		$this->sql_query			 			= isset($params['sql_query']) ? $params['sql_query'] : '';
		$this->data						 			= isset($params['data']) ? $params['data'] : '';
		$this->search			 					= isset($params['search']) ? $params['search'] : '';
		$this->multiple_search	 		= isset($params['multiple_search']) ? $params['multiple_search'] : '';
		$this->items_per_page				= isset($params['items_per_page']) ? $params['items_per_page'] : '';
		$this->sort									= isset($params['sort']) ? $params['sort'] : false;
		$this->page									= isset($params['page']) ? $params['page'] : 1;

		$this->id								 		= isset($params['id']) ? $params['id'] : 'ct';
		$this->class								= isset($params['class']) ? $params['class'] : '';
		$this->form_init						= isset($params['form_init']) ? $params['form_init'] : true;
		$this->form_url							= isset($params['form_url']) ? $params['form_url'] : '';
		$this->header				 				= isset($params['header']) ? $params['header'] : false;
		$this->width								= isset($params['width']) ? $params['width'] : '';
		$this->search_init			 		= isset($params['search_init']) ? $params['search_init'] : true;
		$this->search_html			 		= isset($params['search_html']) ? $params['search_html'] : '<span id="#ID#_search_value">Search...</span><a id="#ID#_advanced_search" href="javascript: ctShowAdvancedSearch(\'#ID#\');" title="Advanced Search"><img src="images/advanced_search.png" /></a><div id="#ID#_loader"></div>';
		$this->multiple_search_init	= isset($params['multiple_search_init']) ? $params['multiple_search_init'] : 'hide';
		$this->items_per_page_init	= isset($params['items_per_page_init']) ? $params['items_per_page_init'] : '10*$i';
		$this->items_per_page_all		= isset($params['items_per_page_all']) ? (($params['items_per_page_all']!='' or $params['items_per_page_all']===false) ? $params['items_per_page_all'] : '#TOTAL_ITEMS#') : '#TOTAL_ITEMS#';
		$this->items_per_page_url		= isset($params['items_per_page_url']) ? $params['items_per_page_url'] : 'ctItemsPerPage(\'#ID#\')';
		$this->sort_init						= isset($params['sort_init']) ? $params['sort_init'] : true;
		$this->sort_order						= isset($params['sort_order']) ? $params['sort_order'] : 'adt';
		$this->sort_url							= isset($params['sort_url']) ? $params['sort_url'] : 'ctSort(\'#ID#\',\'#COLUMN_ID#\')';
		$this->extra_cols						= isset($params['extra_cols']) ? $params['extra_cols'] : array();
		$this->odd_even					 		= isset($params['odd_even']) ? $params['odd_even'] : true;
		$this->no_results						= isset($params['no_results']) ? $params['no_results'] : 'No results found.';
		$this->actions							= isset($params['actions']) ? $params['actions'] : array();
		$this->actions_url					= isset($params['actions_url']) ? $params['actions_url'] : 'ctActions(\'#ID#\')';
		$this->pager								= isset($params['pager']) ? $params['pager'] : '';

		$this->total_items					= isset($params['total_items']) ? $params['total_items'] : 0;
		$this->sql_fields						= '';
		$this->out									= '';

		if($this->sql_query!='')
			$this->init_data();

	}

	// Gets the data from the database and makes somes necessary initializations
	function init_data(){
	
	global $wpdb;

		// default value of items per page
		if($this->items_per_page==''){
			// formula $i*10; pow(10,$i)
			if(strpos($this->items_per_page_init,'$')!==false){
				$i=1;
				eval('$items_per_page='.$this->items_per_page_init.';');
			}else{
				$items_per_page=explode(',',$this->items_per_page_init);
				$items_per_page=$items_per_page[0];
			}

			$this->items_per_page=$items_per_page;
		}

		// adds the new extra columns to the data
		for($i=0; $i<count($this->extra_cols); $i++)
 
			$this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'init');

		$bd_fields=substr($this->sql_query,7,stripos($this->sql_query,' FROM ')-7);
		$this->sql_fields=explode(',',$bd_fields);

		// gets the data from the DB
		$result = mysql_query($this->get_sql());

		if($result) {

			while ($row = mysql_fetch_array($result, MYSQL_NUM))
			    $this->data[]=$row;

			mysql_free_result($result);

		}
	 

		// total of items
		$result=mysql_query(str_replace($bd_fields,'count('.$this->sql_fields[0].')',$this->get_sql_select()).$this->get_sql_where(),$wpdb->dbh);
		 
		$this->total_items=mysql_fetch_row($result);
		$this->total_items=$this->total_items[0];

		mysql_free_result($result);

		// adds the new extra columns to the data
		for($i=0; $i<count($this->extra_cols); $i++)
			$this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'data');

	}

	// Adds a new row i.e. $ct->add_row(array(69,69,'ola',69),3);
	function add_row($arr_html,$row){

		array_splice($this->data, $row-1, 0, array($arr_html));

	}

	// Adds a new column i.e. $ct->add_col(1,'Check','<input type="checkbox" name="check" />','50');
	function add_col($col,$header,$width,$html,$op='init_data'){

		if($op=='init_data')
			$this->extra_cols[]=array($col,$header,$width,$html);

		if(strpos($op,'init')!==false){

			// adds the new header
			$arr_header=explode(',',$this->header);

			if($col>count($arr_header)+1)
				$col=count($arr_header)+1;

			array_splice($arr_header, $col-1, 0, $header);
			$this->header=implode(',',$arr_header);

			// adds the new column width
			$arr_width=explode(',',$this->width);
			array_splice($arr_width, $col-1, 0, $width);
			$this->width=implode(',',$arr_width);

			// rearrange the sort string
			if($this->sort_init===true){
				$this->sort_init=str_repeat('t',count($arr_header));
				$this->sort_init[$col-1]='f';
			}else if($this->sort_init!==true and $this->sort_init!==false){
				$this->sort_init=substr_replace($this->sort_init,'f',$col-1,0);
			}

			// rearrange the search_init string
			if($this->search_init===true){
				$this->search_init=str_repeat('t',count($arr_header));
				$this->search_init[$col-1]='f';
			}elseif($this->search_init!==true and $this->search_init!==false){
				$this->search_init=substr_replace($this->search_init,'f',$col-1,0);
			}

			// rearrange the multiple_search_init string
			if($this->multiple_search_init===true){
				$this->multiple_search_init=str_repeat('t',count($arr_header));
				$this->multiple_search_init[$col-1]='f';
			}else if($this->multiple_search_init=='hide'){
				$this->multiple_search_init=str_repeat('t',count($arr_header));
				$this->multiple_search_init[$col-1]='f';
				$this->multiple_search_init.='hide';
			}else if($this->multiple_search_init!==true and $this->multiple_search_init!==false){
				$this->multiple_search_init=substr_replace($this->multiple_search_init,'f',$col-1,0);
			}

		}

		if(strpos($op,'data')!==false){

			// add the new column in all rows
			if($this->total_items>0){
				for($i=0; $i<count($this->data); $i++){
					array_splice($this->data[$i], $col-1, 0, array($html));
				}
			}

		}

	}

	// Rearrange the sort string
	function init_sort(){
		$out='';

		if($this->sort===true or $this->sort==''){
			for($i=0; $i<count($this->data[0]); $i++){
				$out.=($out ? '_' : '').'t';
			}
			$this->sort=$out;
		}
	}

	// Gets final composed sql query
	function get_sql(){

		return $this->get_sql_select().$this->get_sql_where().$this->get_sql_order().$this->get_sql_limit();

	}

	// Gets the sql query corresponding to selecting fields and tables parameters
	function get_sql_select(){

		if(stripos($this->sql_query,' WHERE ')!==false)
			$select_str=substr($this->sql_query,0,stripos($this->sql_query,' WHERE '));
		else
			$select_str=$this->sql_query;

		return $select_str;

	}

	// Gets the sql query corresponding to conditions parameters
	function get_sql_where(){
		$where_str='';
		$multiple_search_str='';

		if(stripos($this->sql_query,' WHERE ')!==false){
			if(stripos($this->sql_query,' ORDER BY ')!==false)
				$where_str_ini='('.substr($this->sql_query,stripos($this->sql_query,' WHERE ')+7,stripos($this->sql_query,' ORDER BY ')-stripos($this->sql_query,' WHERE ')-7).')';
			elseif(stripos($this->sql_query,' LIMIT ')!==false)
				$where_str_ini='('.substr($this->sql_query,stripos($this->sql_query,' WHERE ')+7,stripos($this->sql_query,' LIMIT ')-stripos($this->sql_query,' WHERE ')-7).')';
			else
				$where_str_ini='('.substr($this->sql_query,stripos($this->sql_query,' WHERE ')+7).')';
		}else{
			$where_str_ini='';
		}

		// adds the extra columns in consideration
		$arr_sql_fields=$this->sql_fields;
		for($i=0; $i<count($this->extra_cols); $i++)
			array_splice($arr_sql_fields, $this->extra_cols[$i][0]-1, 0, '');

		for($i=0; $i<count($arr_sql_fields); $i++){

			if(empty($this->multiple_search[$i]))
				$this->multiple_search[$i]='';

			if($this->search!='' and $this->search_init[$i]!='f')
				$where_str.=(($i==0 and $where_str_ini) ? ' AND ' : '').($where_str ? ' OR ' : '(').$arr_sql_fields[$i]." LIKE '%".$this->search."%'";

			if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f')
				$multiple_search_str.=(($where_str_ini or $where_str or $multiple_search_str) ? ' AND ' : '').$arr_sql_fields[$i]." LIKE '%".$this->multiple_search[$i]."%'";

		}

		if($where_str!='')
			$where_str.=')';

		return  (($where_str_ini or $where_str or $multiple_search_str) ? ' WHERE ' : '').$where_str_ini.$where_str.$multiple_search_str;
	}

	// Gets the sql query corresponding to order parameters
	function get_sql_order(){

		if(stripos($this->sql_query,' ORDER BY ')!==false){
			if(stripos($this->sql_query,' LIMIT ')!==false)
				$order_str_ini=substr($this->sql_query,stripos($this->sql_query,' ORDER BY '),stripos($this->sql_query,' LIMIT ')-stripos($this->sql_query,' ORDER BY '));
			else
				$order_str_ini=substr($this->sql_query,stripos($this->sql_query,' ORDER BY '));
		}else{
			$order_str_ini='';
		}

		$order_str='';
		$arr_new_cols=array();

		// adds the extra columns in consideration
		$arr_sql_fields=$this->sql_fields;
		for($i=0; $i<count($this->extra_cols); $i++){
			array_splice($arr_sql_fields, $this->extra_cols[$i][0]-1, 0, '');
			$arr_new_cols[]=$this->extra_cols[$i][0];
		}

		$arr_sort=explode('_',$this->sort);
		asort($arr_sort);

		foreach($arr_sort as $key => $value){

			if(!in_array($key+1,$arr_new_cols)){

				if(substr($arr_sort[$key],-1)=='a')
					$order_str.=(($order_str_ini or $order_str) ? ', ' : ' ORDER BY ').$arr_sql_fields[$key].' ASC';

				if(substr($arr_sort[$key],-1)=='d')
					$order_str.=(($order_str_ini or $order_str) ? ', ' : ' ORDER BY ').$arr_sql_fields[$key].' DESC';

			}

		}

		return $order_str_ini.$order_str;
	}

	// Gets the sql query corresponding to limit parameters
	function get_sql_limit(){
		$limit_str='';

		if($this->items_per_page!='all' and $this->items_per_page!='')
			$limit_str=' LIMIT '.($this->page-1)*$this->items_per_page.','.$this->items_per_page;

		return $limit_str;
	}

	// Analises the url passed, if it has the tag #COLUMN_ID# it substitues for the true value of the page,
	// otherwise puts ?pag=1 or &pag=1 in the end of url
	function get_url($column){

		if(strpos($this->sort_url,'#COLUMN_ID#')!==false){

			return str_replace('#COLUMN_ID#',$column,$this->sort_url);

		}else{

			return $this->sort_url.(strpos($this->sort_url,'?')!==false ? '&' : '?').'sort='.$this->sort;
		}

	}

	// Change some specific tags to their corresponding value
	function change_tags($str){

			$str=str_replace('#ID#',$this->id,$str);
			$str=str_replace('#PAGE#',$this->page,$str);
			$str=str_replace('#ITEMS_PER_PAGE#',$this->items_per_page,$str);
			$str=str_replace('#TOTAL_ITEMS#',$this->total_items,$str);

			return $str;

	}

	// Change the column tags for their value #COL1#, #COL2#, ...
	function change_tag_col($str,$arr_cols){

		preg_match_all('/#COL(\d+)#/i', $str, $matches, PREG_SET_ORDER);

		for($i=0; $i<count($matches); $i++){
			$str=str_replace($matches[$i][0], addslashes($arr_cols[$matches[$i][1]-1]), $str);
		}

		return $str;

	}

	// Draw the form
	function draw_form(){
		$out='';

		if($this->form_init)
			$out='<form id="'.$this->id.'_form" name="'.$this->id.'_form" method="get" action="'.$this->form_url.'">
						<input type="hidden" id="'.$this->id.'_items_per_page" name="'.$this->id.'_items_per_page" value="'.$this->items_per_page.'" />
						<input type="hidden" id="'.$this->id.'_sort" name="'.$this->id.'_sort" value="'.$this->sort.'" />
						<input type="hidden" id="'.$this->id.'_page" name="'.$this->id.'_page" value="'.$this->page.'" />';

		return $out;


	}

	// Draw the search component
	function draw_search(){
		$out='';

		if($this->search_init)
			$out.='<input type="text" id="'.$this->id.'_search" name="'.$this->id.'_search" value="'.$this->search.'" onfocus="ctSearchFocus(\''.$this->id.'\');" onblur="ctSearchBlur(\''.$this->id.'\');" onkeypress="ctSearchKeypress(\''.$this->id.'\');" onkeyup="ctSearch(\''.$this->id.'\');" />'.$this->change_tags($this->search_html);

		return $out;
	}

	// Draw the items_per_page component
	function draw_items_per_page(){
		$out='';

		if($this->items_per_page_init!==false and $this->total_items>0){
			$out='<select id="'.$this->id.'_items_per_page_change" name="'.$this->id.'_items_per_page_change" onchange="'.$this->change_tags($this->items_per_page_url).'">';

			// formula $i*10; pow(10,$i)
			if(strpos($this->items_per_page_init,'$')!==false){

				$i=1;

				eval('$value='.$this->items_per_page_init.';');

				while ($value<$this->total_items) {

					$out.='<option value="'.$value.'"'.($value==$this->items_per_page ? ' selected="selected"' : '').'>'.$value.'</option>';

			    $i++;

					eval('$value='.$this->items_per_page_init.';');

				}

			}else{

				$i=0;

				$arr_items_per_page=explode(',',$this->items_per_page_init);

				while ($i<count($arr_items_per_page) and $arr_items_per_page[$i]<$this->total_items) {

					$out.='<option value="'.$arr_items_per_page[$i].'"'.($arr_items_per_page[$i]==$this->items_per_page ? ' selected="selected"' : '').'>'.$arr_items_per_page[$i].'</option>';

			    $i++;

				}

			}

			if($this->items_per_page_all!='')
				$out.='<option value="all"'.('all'==$this->items_per_page ? ' selected="selected"' : '').'>'.$this->change_tags($this->items_per_page_all).'</option>';

			$out.='</select>';
		}

		return $out;
	}

	// Draw the header of the table
	function draw_header(){
		$out_multiple_search='';

		$arr_width=explode(',',$this->width);
		$out='<thead><tr id="'.$this->id.'_sort">';

		$arr_sort=explode('_',$this->sort);
		$arr_header=explode(',',$this->header);

		$column=1;
		for($i=0; $i<count($arr_header);$i++){

			if($this->sort_init!==false and $this->sort_init[$i]!='f'){
					$out.='<th'.(($this->width!='' and $arr_width[$i]>0) ? ' width="'.$arr_width[$i].'"' : '').' onclick="'.$this->change_tags($this->get_url($i+1)).'"><span'.($arr_sort[$i]=='f' ? ' class="no_sort' : ' class="sort').(substr($arr_sort[$i],-1)=='a' ? '_asc' : (substr($arr_sort[$i],-1)=='d' ? '_desc' : '')).'"></span>'.$arr_header[$i].'</th>';
			}else{
				$out.='<th'.(($this->width!='' and $arr_width[$i]>0) ? ' width="'.$arr_width[$i].'"' : '').'><span></span>'.$arr_header[$i].'</th>';
			}

			if($this->multiple_search_init===true or $this->multiple_search_init=='hide' or (strpos($this->multiple_search_init,'hide')!==false and $this->multiple_search_init[$i]=='t') or $this->multiple_search_init[$i]=='t')
				$out_multiple_search.='<th><input type="text" id="'.$this->id.'_multiple_search'.($i+1).'" name="'.$this->id.'_multiple_search[]'.'" value="'.$this->multiple_search[$i].'" onkeyup="ctMultiSearch(\''.$this->id.'\');" /></a></th>';
			else
				$out_multiple_search.='<th></th>';
		}


		$out.='</tr>';

		if($this->multiple_search_init===true or strpos($this->multiple_search_init,'hide')!==false or strpos($this->multiple_search_init,'t')!==false)
			$out.='<tr id="'.$this->id.'_multiple_search"'.(($this->multiple_search_init!==true and strpos($this->multiple_search_init,'hide')!==false) ? ' style="display: none;"' : '').'>'.$out_multiple_search.'</tr>';

		$out.'</thead>';

		return $out;
	}

	// Draw the body of the table
	function draw_body(){
	
	global $PPT;
	
		$out='';

		if($this->total_items>0){
			$arr_width=explode(',',$this->width);
			
			for($i=0; $i<count($this->data);$i++){$c=0;
				$out.='<tr'.($this->odd_even ? ($i%2==0 ? ' class="odd"' : ' class="even"') : '').'>';
				foreach($this->data[$i] as $key => $value){
				
				$arr_header=explode(',',$this->header);
				
					if($arr_header[$c] == "ORDER ID"){
					
					$out.='<td'.(($i==0 and $this->width!='' and $arr_width[$key]>0) ? ' width="'.$arr_width[$key].'"' : '').'>
					
					<a href="javascript:void(0);" onClick="document.getElementById(\'delo\').value =\''.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])).'\';document.DeleteOrder.submit();" title="delete order"><img src="../wp-content/themes/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'/images/premiumpress/led-ico/cross.png" align="middle"></a>
					
										<a href="../wp-content/themes/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'/admin/_invoice.php?id='.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])).'" target="_blank" title="view invoice"><img src="../wp-content/themes/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'/images/premiumpress/led-ico/page.png" align="middle"></a>
					
					<a href="admin.php?page=orders&id='.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])).'" title="edit order"><img src="../wp-content/themes/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'/images/admin/icon-edit.gif" align="middle"> '.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])).'</a></td>';
			
					}elseif($arr_header[$c] == "TOTAL"){
					
					$out.='<td'.(($i==0 and $this->width!='' and $arr_width[$key]>0) ? ' width="'.$arr_width[$key].'"' : '').'>'.number_format(str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])),2).'</td>';
					
					
					}elseif($arr_header[$c] == "STATUS"){
						
							switch(str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i]))){
							
							case "0": { 	$O1 = "Awaiting Payment";	$O2 = "#0c95ff";		} break;
							case "3": { 	$O1 = "Paid & Completed ";	$O2 = "green";		} break;	
							case "5": { 	$O1 = "Payment Received";	$O2 = "green";		} break;	
							case "6": { 	$O1 = "Payment Failed";		$O2 = "red";		} break;
							case "7": { 	$O1 = "Payment Pending";	$O2 = "orange";		} break;	
							case "8": { 	$O1 = "Payment Refunded";	$O2 = "black";		} break;		
							
							}
							
							$out.='<td'.(($i==0 and $this->width!='' and $arr_width[$key]>0) ? ' width="'.$arr_width[$key].'"' : '').' style="background-color:'.$O2.'"><span style="color:white;">'.$O1.'</span></td>';
						
					}else{
					
					 
						$out.='<td'.(($i==0 and $this->width!='' and $arr_width[$key]>0) ? ' width="'.$arr_width[$key].'"' : '').'>'.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags($value),$this->data[$i])).'</td>';
						
					}
					$c++; 
				}
				$out.='</tr>'; 
			}
		}else{
			$arr_header=explode(',',$this->header);

			if($this->no_results!==false)
				$out.='<tr id="'.$this->id.'_no_results"><td colspan="'.count($arr_header).'">'.$this->no_results.'</td></tr>';
		}

		return $out;
	}

	// Draw the actions component
	function draw_actions(){
		$out='';

		if(count($this->actions)>0){

			$out='<select id="'.$this->id.'_actions" name="'.$this->id.'_actions" onchange="'.$this->change_tags($this->actions_url).'">';

			for($i=0; $i<count($this->actions); $i++){
				$out.='<option value="'.$this->actions[$i][0].'">'.$this->actions[$i][1].'</option>';
			}

			$out.='</select>';

		}

		return $out;
	}

	// Draw the pager component
	function draw_pager(){
		return $this->pager;
	}

	// Draw the necessary javascript block
	function draw_javascript_block(){

		// sort order
		$out_sort_order='var arr_sort_order= new Array();';

		for($i=0; $i<strlen($this->sort_order); $i++){
			if($i==strlen($this->sort_order)-1)
				$out_sort_order.='arr_sort_order["'.$this->sort_order[$i].'"]="'.$this->sort_order[0].'";';
			else
				$out_sort_order.='arr_sort_order["'.$this->sort_order[$i].'"]="'.$this->sort_order[$i+1].'";';
		}

		if(strpos($this->sort_order,'t')===false)
			$out_sort_order.='arr_sort_order["t"]="";';

		$out_sort_order.='arr_sort_order["first"]="'.$this->sort_order[0].'";';

		$out='<script type="text/javascript">'.$out_sort_order.'var extra_cols ='.json_encode($this->extra_cols).';';

		if($this->search=='')
			$out.='jQuery(document).ready(function(){ jQuery("#'.$this->id.'_search_value").css("opacity","1"); });';

		$out.='</script>';

		return $out;
	}

	// Displays the output
	function display($op=''){
		$out='';

		// Builds the all structure of the table
		$this->init_sort();

		if($op=='' or strpos($op,'form')!==false)
			$out.=$this->draw_form();

		if($op=='' or strpos($op,'search')!==false or strpos($op,'items_per_page')!==false){
			$out.='<div id="'.$this->id.'_top_container">';

			if(($op=='' or strpos($op,'search')!==false))
				$out.='<div id="'.$this->id.'_search_container">'.$this->draw_search().'</div>';

			if(($op=='' or strpos($op,'items_per_page')!==false))
				$out.='<div id="'.$this->id.'_items_per_page_container">'.$this->draw_items_per_page().'</div>';

			$out.='</div>';
		}

		if($op=='' or strpos($op,'table')!==false){
			$out.='<table id="'.$this->id.'"'.($this->class!='' ? ' class="'.$this->class.'"' : '').'>';
			if($this->header!==false)
				$out.=$this->draw_header();
			$out.='<tbody>'.$this->draw_body().'</tbody>';
			$out.='</table>';
		}

		if($op=='' or strpos($op,'actions')!==false or strpos($op,'pager')!==false){
			$out.='<div id="'.$this->id.'_bottom_container">';

			if(($op=='' or strpos($op,'actions')!==false) and count($this->actions)>0)
				$out.='<div id="'.$this->id.'_actions_container">'.$this->draw_actions().'</div>';

			if(($op=='' or strpos($op,'pager')!==false) and $this->pager!='')
				$out.='<div id="'.$this->id.'_pager_container">'.$this->draw_pager().'</div>';

			$out.='</div>';
		}

		if(($op=='' or strpos($op,'form')!==false) and $this->form_init)
				$out.='</form>';

		$out.=$this->draw_javascript_block();

		$this->out=$out;

		return $out;
	}

}

class CreativePager{

	var $id;
	var $class;
	var $selected_page;
	var $total_items;
	var $items_per_page;
	var $total_pages;
	var $nav_pages;
	var $url;
	var $first;
	var $last;
	var $out;

	function pager($params){
		global $tpl;

		// Default Values
		$this->id							= isset($params['id']) ? $params['id'] : 'pager';
		$this->class					= isset($params['class']) ? $params['class'] : '';
		$this->selected_page	= isset($params['selected_page']) ? ($params['selected_page']>0 ? $params['selected_page'] : 1) : 1;
		$this->total_items	 	= isset($params['total_items']) ? $params['total_items'] : '';
		$this->items_per_page	= isset($params['items_per_page']) ? ($params['items_per_page']>0 ? $params['items_per_page'] : 10) : 10;
		$this->total_pages	 	= isset($params['total_pages']) ? $params['total_pages'] : ceil($this->total_items/$this->items_per_page);
		$this->nav_pages		 	= isset($params['nav_pages']) ? (($params['nav_pages']!==true and $params['nav_pages']>0 or $params['nav_pages']===false) ? $params['nav_pages'] : 9) : 9;
		$this->url					 	= isset($params['url']) ? $params['url'] : '';
		$this->first				 	= true;
		$this->last					 	= true;

		// Builds the all structure of the pager (info1 + pager + info2)
		$out='<span id="'.$this->id.'_info1">'.'Total: '.$this->total_items.'</span>';
		if($this->total_pages>1){
			$out.='<ul id="'.$this->id.'"'.($this->class!='' ? ' class="'.$this->class.'"' : '').'>';
			$out.=$this->draw_type();
			$out.='</ul>';
		}
		$out.='<span id="'.$this->id.'_info2">'.'Page: '.$this->selected_page.' of '.$this->total_pages.'</span>';

		$this->out=$out;

	}

	// Analises the url passed, if it has the tag #NUM_PAGE# it substitues for the true value of the page,
	// otherwise puts ?pag=1 or &pag=1 in the end of url
	function get_url($page){
		if(strpos($this->url,'#NUM_PAGE#')!==false){
			return str_replace('#NUM_PAGE#',$page,$this->url);
		}else{
			return $this->url.(strpos($this->url,'?')!==false ? '&' : '?').'page='.$page;
		}
	}

	// Button first
	function draw_first(){
		$out='';

		if($this->first){

			$out='<li id="'.$this->id.'_first"><a href="'.$this->get_url(1).'">?First</a></li>';
			$out.='<li id="'.$this->id.'_pos_first">...</li>';

		}

		return $out;
	}

	// Button last
	function draw_last(){
		$out='';

		if($this->last){

			$out='<li id="'.$this->id.'_pre_last">...</li>';
			$out.='<li id="'.$this->id.'_last"><a href="'.$this->get_url($this->total_pages).'">Last </a></li>';

		}

		return $out;
	}

	// Builds the pager
	function draw_type(){
		$out='';

		if($this->nav_pages){

			if($this->selected_page>1) $out='<li id="'.$this->id.'_prev"><a href="'.$this->get_url($this->selected_page-1).'"></a></li>';
			$out.=$this->type_centered();
			if($this->selected_page<$this->total_pages) $out.='<li id="'.$this->id.'_next"><a href="'.$this->get_url($this->selected_page+1).'"></a></li>';

		}

		return $this->draw_first().$out.$this->draw_last();
	}


	// Centered type - the selected page allways stays in the center
	function type_centered(){
		$out='';

		if($this->selected_page<=ceil(($this->nav_pages+1)/2)){
			$min=1;
			$max=$this->nav_pages;
			$this->first=false;
		}elseif($this->selected_page>$this->total_pages-floor(($this->nav_pages+1)/2)){
			$min=$this->total_pages-$this->nav_pages+1;
			$max=$this->total_pages;
			$this->last=false;
		}else{
			$min=$this->selected_page-ceil(($this->nav_pages-1)/2);
			$max=$min+$this->nav_pages-1;
		}

		if($this->total_pages<=$this->nav_pages)
			$this->last=false;

		if($min<1) $min=1;
		if($max>$this->total_pages) $max=$this->total_pages;

		for($i=$min; $i<=$max; $i++)
			$out.='<li><a href="'.$this->get_url($i).'" '.($i==$this->selected_page ? 'class="selected"' : '').'>'.$i.'</a></li>';

		return $out;
	}

	// Display the output
	function display(){
		return $this->out;
	}

}

function getCreativePagerLite($page,$total_items,$items_per_page){
 
	$cp=new CreativePager();

	// Data Gathering
	$params['selected_page']			= $page;
	$params['total_items']				= $total_items;
	$params['items_per_page']			= $items_per_page=='all' ? $total_items : $items_per_page;
	$params['url']								= 'javascript: ctPager(\'ct\',\'#NUM_PAGE#\');';

	// Layout Configurations
	$params['id']									= 'ct_pager';
	$params['type']								= 'centered';
	$params['nav_pages']					= 5;

	$cp->pager($params);
	$out_pager=$cp->display();

	return $out_pager;
}


// function to check if an array as any value - for the real example and complex example
function filled_array($arr){

	for($i=0; $i<count($arr); $i++){
		if($arr[$i]!='')
			return true;
	}

	return false;

}

function TablePageMeta(){
?>
<style>



#ct_top_container{	width:100%;	height: 33px;background: #c0c0c0; }
#ct_search_container{	float: right;	margin-top: 5px;	margin-right: 15px;	position: relative;}
#ct_search_container a:link, #ct_search_container a:visited{	color: #555555;	text-decoration: none;}
#ct_search_container a:hover, #ct_search_container a:active {	color: #333333;	text-decoration: none;}

#ct_search{	color: #555555;	width: 200px;	height: 20px;	padding: 0px 25px 0px 5px;	border: 1px solid #aaaaaa;	}
#ct_search{	height: 18px;}
#ct_search_value{	position: absolute;	top: 0px;	left: 7px;	opacity:0;	filter:alpha(opacity=0);	z-index: 300;}
#ct_advanced_search{	position: absolute;	top: 3px;	left: 210px;	z-index: 300;}
#ct_loader{	width: 16px;	height: 16px;	margin-left: 10px;	float: right;}
/* ITEMS_PER_PAGE */
#ct_items_per_page_container{	float: left;	margin-top: 7px;	margin-left: 15px;}
/* TABLE */
#ct {	border-collapse:collapse;	width:100%;	margin-top: 0px;	clear: both;}
#ct th{	color: #555555;	font-weight: normal;	text-align: left;	text-decoration: none;	border:1px solid #d3d3d3;	background: #ddd;	padding:3px 0px 3px 8px;	margin: 0px;	cursor: pointer;}
#ct th span{	display: block;	float: right;	width: 21px;	height: 9px;	margin-top: 5px;}

#ct th span.sort{	background: url(../wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/images/admin/sort_black.gif) no-repeat right center;}
#ct th span.sort_asc{	background: url(../wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/images/admin/sort_asc_black.gif) no-repeat right center;}
#ct th span.sort_desc{	background: url(../wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/images/admin/sort_desc_black.gif) no-repeat right center;}
#ct_multiple_search th{	padding:0px;}
#ct_multiple_search th input{	width: 100%;	padding: 0px;	height: 20px;	border: 0px;}
#ct td{	color: 555555;	border:1px solid #d3d3d3;	padding:5px 8px;}
#ct .odd, #ct_no_results{	background-color: #EEFFEE;}
#ct .even{	background-color: #DDFFDD;} 
/* BOTTOM CONTAINER : ACTIONS + PAGER */
#ct_bottom_container{	width:100%;	height: 33px;	background: #fff;}
/* ACTIONS */
#ct_actions_container{	margin-top: 20px;	float: left;}

/* PAGER */
#ct_pager{	list-style: none;	float: right;	margin-top: 17px;	margin-right: 15px;	margin-bottom:10px;}
#ct_pager li{	display: inline;}
#ct_pager li a{	display: inline;	color: #555555;	font-size: 12px;	text-decoration: none;	padding: 5px 6px;	background: #ededed;	border: 1px solid #D3D3D3;margin-right:3px}
#ct_pager li a:hover{	background: #ededed;color: #333333;}
#ct_pager li a.selected{	background: #ededed;	color: #555555;	font-weight: bold;}
#ct_pager_info1{	font-size: 9px;	height: 20px;	margin: 5px 0px 10px 0px;  padding: 0px 10px 0px 10px;	float: left;}
#ct_pager_info2{	display: none;}
#ct_pager_pos_first{	color: #000000;	font-size: 12px;	margin-right: 3px;	padding: 5px;}
#ct_pager_pre_last{	color: #000000;	font-size: 12px;	margin-right: 3px;	padding: 5px;}
 
</style>
<script>
 
var timeout=0; // time to begin search after keypressed (miliseconds)
// ***************************************************

// Global vars
var typingTimeout;
var multiple_sort=0;

function ctSearchFocus(table_id){
	if(jQuery('#'+table_id+'_search').val()=='')
		jQuery('#'+table_id+'_search_value').animate({opacity: 0.25}, 300);
}

function ctSearchBlur(table_id){
	if(jQuery('#'+table_id+'_search').val()=='')
		jQuery('#'+table_id+'_search_value').animate({opacity: 1}, 300);
}

function ctSearchKeypress(table_id){
	if(jQuery('#'+table_id+'_search').val()=='')
		jQuery('#'+table_id+'_search_value').animate({opacity: 0}, 10);
}

function ctSearch(table_id){
	if(jQuery('#'+table_id+'_search').val()=='')
		jQuery('#'+table_id+'_search_value').animate({opacity: 0.25}, 300);

	window.clearInterval(typingTimeout);
  typingTimeout = window.setTimeout(function() { ctSubmitForm(table_id,'',1,'items_per_page,body,pager') },timeout);
}

function ctMultiSearch(table_id){
	window.clearInterval(typingTimeout);
  typingTimeout = window.setTimeout(function() { ctSubmitForm(table_id,'',1,'items_per_page,body,pager') },timeout);
}

function ctShowAdvancedSearch(table_id){
	jQuery('#'+table_id+'_multiple_search').toggle();
}


function ctItemsPerPage(table_id){
		jQuery('#'+table_id+'_items_per_page').val(jQuery('#'+table_id+'_items_per_page_change').val());
		ctSubmitForm(table_id,'',1,'body,pager');
}


function ctSort(table_id,sort_column){

	var sort_num;
	var sort_order;
	var max_num=1;
	var str_sort='';
	var sort_aux='';
	var arr_sort_aux='';

	var arr_sort_order_txt= new Array();
	arr_sort_order_txt["a"]="_asc";
	arr_sort_order_txt["d"]="_desc";
	arr_sort_order_txt["t"]="";


	sort_aux=jQuery('#'+table_id+'_sort').val();
	arr_sort_aux=sort_aux.split('_');

	if(multiple_sort==1){

		for(i=0; i<arr_sort_aux.length; i++){
			sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);

			if(sort_num>max_num)
				max_num=sort_num;
		}

		for(i=0; i<arr_sort_aux.length; i++){
			sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);
			sort_order=arr_sort_aux[i].substring(arr_sort_aux[i].length-1);

			if(sort_column==i+1){
				jQuery('#'+table_id+'_sort th span:eq('+(sort_column-1)+')').removeClass().addClass('sort'+arr_sort_order_txt[(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order])]);
     		str_sort+=(str_sort!='' ? '_' : '')+(arr_sort_order[sort_order]=='t' ? '' : (sort_num!='' ? sort_num : parseInt(max_num)+1))+(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order]);
			}else{
				str_sort+=(str_sort!='' ? '_' : '')+(sort_order=='f' ? 'f' : sort_num+sort_order);
			}
		}

		jQuery('#'+table_id+'_sort').val(str_sort);

		ctSubmitForm(table_id,str_sort,1,'body');

	}else{

		for(i=0; i<arr_sort_aux.length; i++){
			sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);
			sort_order=arr_sort_aux[i].substring(arr_sort_aux[i].length-1);

			if(sort_column==i+1){
				jQuery('#'+table_id+'_sort th span:eq('+(sort_column-1)+')').removeClass().addClass('sort'+arr_sort_order_txt[(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order])]);
					str_sort+=(str_sort!='' ? '_' : '')+(arr_sort_order[sort_order]=='t' ? '' : 1)+(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order]);
			}else{
				jQuery('#'+table_id+'_sort th span:eq('+i+')').filter("'.sort, .sort_asc, .sort_desc").removeClass('sort_asc sort_desc').addClass('sort');
					str_sort+=(str_sort!='' ? '_' : '')+(sort_order=='f' ? 'f' : 't');
			}
		}

		jQuery('#'+table_id+'_sort').val(str_sort);

		ctSubmitForm(table_id,str_sort,1,'body');

	}

}

function ctActions(table_id){

	return true;

}

function ctPager(table_id,page){

	ctSubmitForm(table_id,'',page,'body,pager');

}


// reload_option = 'items_per_page'; 'body'; 'pager'; 'items_per_page,body'; 'items_per_page,body,pager'; ...
function ctSubmitForm(table_id,sort,page,reload_option){

		// starts the loading gif
		jQuery('#'+table_id+'_loader').css("backgroundImage", "url(images/loading.gif)");

		var multiple_search_str='';

		jQuery('#'+table_id+'_multiple_search th').each(function(index) {
			multiple_search_str+=(multiple_search_str=='' ? '' : ',')+'"'+(jQuery('#'+table_id+'_multiple_search'+(index+1)).val()==undefined ? '' : jQuery('#'+table_id+'_multiple_search'+(index+1)).val())+'"';
		});

		var multiple_search=JSON.parse('['+multiple_search_str+']');

		// body refresh
		jQuery.ajax({
		   type: "POST",
		   url: "../wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/admin/_ad_orders.php?ajaxcall=1&op="+reload_option,
		   data: {
							"id" : table_id,
							"items_per_page" : jQuery('#'+table_id+'_items_per_page').val(),
							"sort" : sort,
							"page" : page,
							"search" : jQuery('#'+table_id+'_search').val(),
							"multiple_search" : multiple_search,
							"extra_cols" : extra_cols,
							"search_init" : jQuery('#'+table_id+'_search_init').val()
			 			 },

			 dataType: 'json',
		   success: function(out){

			 	 if(reload_option.indexOf('items_per_page')!=-1)
				   jQuery('#'+table_id+'_items_per_page_container').html(out.items_per_page);

			 	 if(reload_option.indexOf('body')!=-1){
			     jQuery('#'+table_id+' tbody').html(out.body);
				 }

			 	 if(reload_option.indexOf('pager')!=-1)
			     jQuery('#'+table_id+'_pager_container').html(out.pager);

		     jQuery('#info').html(out.info);

				// stops the loading gif
				jQuery('#'+table_id+'_loader').css("backgroundImage", "");

		   }
		});


}

function ctActions(table_id){

	alert('Action: '+jQuery('#'+table_id+'_actions').val()+' - '+jQuery('#'+table_id+'_actions :selected').text()+'\n\nSelect some checkboxes');

	jQuery('#'+table_id+'_actions option:eq(0)').attr("selected","selected") ;

	jQuery("#ct input[type='checkbox']:checked").each(function() {
  	alert('Checkbox selected : '+this.value);
  })

}

function checkAll(){
	if(jQuery('#ct_check_all').is(':checked'))
		jQuery("#ct input[type='checkbox']").attr('checked', true);
	else
		jQuery("#ct input[type='checkbox']").attr('checked', false);
}

function check(){
	return true;
}

function funcEdit(value){
	alert(value);
}

function funcDelete(value){
	alert(value);
}
jQuery(document).ready(function(){

	jQuery(document).keydown(function(e) {
		if (e.shiftKey || e.ctrlKey || e.altKey)
			multiple_sort=1;
	}).keyup(function(e) {
		multiple_sort=0;
	});

});
</script>
<?php 
}

?>