<?php

if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}

if (!defined('CP_PPP_ID'))
    define ('CP_PPP_ID',intval($_GET["cal"]));

global $wpdb;


$current_user = wp_get_current_user();
$current_user_access = current_user_can('manage_options');

$message = "";

if (isset($_GET['lu']) && $_GET['lu'] != '')
{
    $wpdb->query( $wpdb->prepare (
                                   'UPDATE `'.CP_PPP_POSTS_TABLE_NAME.'` SET paid=%s WHERE id=%d',
                                   $_GET["status"], intval($_GET['lu'])
                                  ) 
                 );           
    $message = "Item updated";        
}
else if (isset($_GET['ld']) && $_GET['ld'] != '')
{
    $wpdb->query( $wpdb->prepare ( 'DELETE FROM `'.CP_PPP_POSTS_TABLE_NAME.'` WHERE id=%d', intval($_GET['ld'])) );       
    $message = "Item deleted";
}

if (CP_PPP_ID != 0) {
    $myform = $wpdb->get_results( $wpdb->prepare ('SELECT * FROM '.$wpdb->prefix.CP_PPP_FORMS_TABLE .' WHERE id=%d', CP_PPP_ID) );    
    if (!$current_user_access && !@in_array($current_user->ID, unserialize($myform[0]->cp_user_access)))     
    {
        echo 'Current user permissions don\'t have access to this messages list.';
        exit;        
    }
}    


$current_page = intval($_GET["p"]);
if (!$current_page) $current_page = 1;
$records_per_page = 50;                                                                                  

$cond = '';
if ($_GET["search"] != '') $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR paypal_post LIKE '%".esc_sql($_GET["search"])."%')";
if ($_GET["dfrom"] != '') $cond .= " AND (`time` >= '".esc_sql($_GET["dfrom"])."')";
if ($_GET["dto"] != '') $cond .= " AND (`time` <= '".esc_sql($_GET["dto"])." 23:59:59')";
if (CP_PPP_ID != 0) $cond .= " AND formid=".CP_PPP_ID;

if (!$current_user_access && CP_PPP_ID == 0)
{
    echo 'Current user permissions don\'t have access to this messages list.';
    exit;
}

$events = $wpdb->get_results( "SELECT * FROM ".CP_PPP_POSTS_TABLE_NAME." WHERE 1=1 ".$cond." ORDER BY `time` DESC" );
$total_pages = ceil(count($events) / $records_per_page);



if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";


?>
<script type="text/javascript">
 function cp_updateMessageItem(id,status)
 {    
    document.location = 'admin.php?page=cp_ppp&cal=<?php echo $_GET["cal"]; ?>&list=1&status='+status+'&lu='+id+'&r='+Math.random( );   
 } 
 function cp_deleteMessageItem(id)
 {
    if (confirm('Are you sure that you want to delete this item?'))
    {        
        document.location = 'admin.php?page=cp_ppp&cal=<?php echo $_GET["cal"]; ?>&list=1&ld='+id+'&r='+Math.random();
    }
 }
</script>
<div class="wrap">
<h1>Payment Form for PayPal Pro - Message List</h1>

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=cp_ppp';">


<div id="normal-sortables" class="meta-box-sortables">
 <hr />
 <h3>This message list is from: <?php if (CP_PPP_ID != 0) echo $myform[0]->form_name; else echo 'All forms'; ?></h3>
</div>


<form action="admin.php" method="get">
 <input type="hidden" name="page" value="cp_ppp" />
 <input type="hidden" name="cal" value="<?php echo CP_PPP_ID; ?>" />
 <input type="hidden" name="list" value="1" />
 <nobr>Search for: <input type="text" name="search" value="<?php echo esc_attr($_GET["search"]); ?>" /> &nbsp; &nbsp; &nbsp;</nobr> 
 <nobr>From: <input type="text" id="dfrom" name="dfrom" value="<?php echo esc_attr($_GET["dfrom"]); ?>" /> &nbsp; &nbsp; &nbsp; </nobr>
 <nobr>To: <input type="text" id="dto" name="dto" value="<?php echo esc_attr($_GET["dto"]); ?>" /> &nbsp; &nbsp; &nbsp; </nobr>
 <nobr>Item: <select id="cal" name="cal">
        <?php if ($current_user_access) { ?><option value="0">[All Items]</option><?php } ?>
   <?php
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_PPP_FORMS_TABLE );                                                                     
    foreach ($myrows as $item)  
        if ($current_user_access || @in_array($current_user->ID, unserialize($item->cp_user_access)))     
            echo '<option value="'.$item->id.'"'.(intval($item->id)==intval(CP_PPP_ID)?" selected":"").'>'.$item->form_name.'</option>'; 
   ?>
    </select></nobr>
 <nobr><span class="submit"><input type="submit" name="ds" value="Filter" /></span> &nbsp; &nbsp; &nbsp; 
 <span class="submit"><input type="submit" name="cp_ppp_csv" value="Export to CSV" /></span></nobr>
</form>

<br />
                             
<?php


echo paginate_links(  array(
    'base'         => 'admin.php?page=cp_ppp&cal='.CP_PPP_ID.'&list=1%_%&dfrom='.urlencode($_GET["dfrom"]).'&dto='.urlencode($_GET["dto"]).'&search='.urlencode($_GET["search"]),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => False
    ) );

?>

<div id="dex_printable_contents">
<table class="wp-list-table widefat fixed pages" cellspacing="0">
	<thead>
	<tr>
      <th style="padding-left:7px;font-weight:bold;" width="50" nowrap>ID</th>
	  <th style="padding-left:7px;font-weight:bold;" width="125">Date</th>
	  <th style="padding-left:7px;font-weight:bold;">Email</th>
	  <th style="padding-left:7px;font-weight:bold;">Message</th>
	  <th style="padding-left:7px;font-weight:bold;">Payment Info</th>	  
	  <th style="padding-left:7px;font-weight:bold;">Options</th>	
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
	  <tr class='<?php if (!($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
        <td><?php echo $events[$i]->id; ?></td>
		<td><?php echo substr($events[$i]->time,0,16); ?></td>
		<td><?php echo $events[$i]->notifyto; ?></td>
		<td><?php 
		     $data = $events[$i]->data;
		     $posted_data = unserialize($events[$i]->posted_data);		     
		     foreach ($posted_data as $item => $value)
		         if (strpos($item,"_url") && is_array($value))		         
		         {
		             $data = str_replace ($posted_data[str_replace("_url","",$item)],'<a href="'.$value[0].'" target="_blank">'.$posted_data[str_replace("_url","",$item)].'</a><br />',$data);  		             
		         }    
		     echo str_replace("\n","<br />",$data); 
		     
		?></td>
		<td>
		    <?php 
		          if ($events[$i]->paid) {
		              echo '<span style="color:#00aa00;font-weight:bold">'.__("Paid").'</span><hr />';
		              if (substr($events[$i]->paypal_post,0,2) != 'a:') echo str_replace("\n","<br />",$events[$i]->paypal_post); 
		          }    
		          else 
		              echo '<span style="color:#ff0000;font-weight:bold">'.__("Not Paid").'</span>'; 
		    ?>
		    
		</td>
		<td>
		  <?php if ($events[$i]->paid) { ?>
   	        <input type="button" name="calmanage_<?php echo $events[$i]->id; ?>" value="Change status to NOT PAID" onclick="cp_updateMessageItem(<?php echo $events[$i]->id; ?>,0);" />                             
 		  <?php } else { ?>
 		    <input type="button" name="calmanage_<?php echo $events[$i]->id; ?>" value="Change status to PAID" onclick="cp_updateMessageItem(<?php echo $events[$i]->id; ?>,1);" />                             
 		  <?php } ?>
		  &nbsp;
		  <input type="button" name="caldelete_<?php echo $events[$i]->id; ?>" value="Delete" onclick="cp_deleteMessageItem(<?php echo $events[$i]->id; ?>);" />                             
		</td>
      </tr>
     <?php } ?>
	</tbody>
</table>
</div>

<p class="submit"><input type="button" name="pbutton" value="Print" onclick="do_dexapp_print();" /></p>

</div>


<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.print();
      w.close();    
 }
 
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dfrom").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 	$j("#dto").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 });
 
</script>














