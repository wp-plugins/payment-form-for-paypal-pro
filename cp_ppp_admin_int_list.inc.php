<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;
$message = "";

$current_user_access = current_user_can('manage_options');

if (isset($_GET['a']) && $_GET['a'] == '1' && $current_user_access)
{
    define('CP_PPP_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
    define('CP_PPP_DEFAULT_fp_destination_emails', CP_PPP_DEFAULT_fp_from_email);        
    
    // insert line
    $wpdb->insert( $wpdb->prefix.CP_PPP_FORMS_TABLE, array( 
                                      'form_name' => stripcslashes($_GET["name"]),

                                      'form_structure' => CP_PPP_DEFAULT_form_structure,

                                      'fp_from_email' => cp_ppp_get_option('fp_from_email', CP_PPP_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => cp_ppp_get_option('fp_destination_emails', CP_PPP_DEFAULT_fp_destination_emails),
                                      'fp_subject' => cp_ppp_get_option('fp_subject', CP_PPP_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => cp_ppp_get_option('fp_inc_additional_info', CP_PPP_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => cp_ppp_get_option('fp_return_page', CP_PPP_DEFAULT_fp_return_page),
                                      'fp_message' => cp_ppp_get_option('fp_message', CP_PPP_DEFAULT_fp_message),
                                      'fp_emailformat' => cp_ppp_get_option('fp_emailformat', CP_PPP_DEFAULT_email_format),

                                      'cu_enable_copy_to_user' => cp_ppp_get_option('cu_enable_copy_to_user', CP_PPP_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => cp_ppp_get_option('cu_user_email_field', CP_PPP_DEFAULT_cu_user_email_field),
                                      'cu_subject' => cp_ppp_get_option('cu_subject', CP_PPP_DEFAULT_cu_subject),
                                      'cu_message' => cp_ppp_get_option('cu_message', CP_PPP_DEFAULT_cu_message),
                                      'cu_emailformat' => cp_ppp_get_option('cu_emailformat', CP_PPP_DEFAULT_email_format),

                                      'vs_use_validation' => cp_ppp_get_option('vs_use_validation', CP_PPP_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => cp_ppp_get_option('vs_text_is_required', CP_PPP_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => cp_ppp_get_option('vs_text_is_email', CP_PPP_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => cp_ppp_get_option('vs_text_datemmddyyyy', CP_PPP_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => cp_ppp_get_option('vs_text_dateddmmyyyy', CP_PPP_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => cp_ppp_get_option('vs_text_number', CP_PPP_DEFAULT_vs_text_number),
                                      'vs_text_digits' => cp_ppp_get_option('vs_text_digits', CP_PPP_DEFAULT_vs_text_digits),
                                      'vs_text_max' => cp_ppp_get_option('vs_text_max', CP_PPP_DEFAULT_vs_text_max),
                                      'vs_text_min' => cp_ppp_get_option('vs_text_min', CP_PPP_DEFAULT_vs_text_min),
                                      
                                      'enable_paypal' => cp_ppp_get_option('enable_paypal', CP_PPP_DEFAULT_ENABLE_PAYPAL),
                                      'paypal_notiemails' => cp_ppp_get_option('paypal_notiemails', '0'),
                                      'paypal_email' => cp_ppp_get_option('paypal_email', CP_PPP_DEFAULT_PAYPAL_EMAIL),
                                      'request_cost' => cp_ppp_get_option('request_cost', CP_PPP_DEFAULT_COST),
                                      'paypal_product_name' => cp_ppp_get_option('paypal_product_name', CP_PPP_DEFAULT_PRODUCT_NAME),
                                      'currency' => cp_ppp_get_option('currency', CP_PPP_DEFAULT_CURRENCY),
                                      'paypal_language' => cp_ppp_get_option('paypal_language', CP_PPP_DEFAULT_PAYPAL_LANGUAGE),                                         

                                      'cv_enable_captcha' => cp_ppp_get_option('cv_enable_captcha', CP_PPP_DEFAULT_cv_enable_captcha),
                                      'cv_width' => cp_ppp_get_option('cv_width', CP_PPP_DEFAULT_cv_width),
                                      'cv_height' => cp_ppp_get_option('cv_height', CP_PPP_DEFAULT_cv_height),
                                      'cv_chars' => cp_ppp_get_option('cv_chars', CP_PPP_DEFAULT_cv_chars),
                                      'cv_font' => cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font),
                                      'cv_min_font_size' => cp_ppp_get_option('cv_min_font_size', CP_PPP_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => cp_ppp_get_option('cv_max_font_size', CP_PPP_DEFAULT_cv_max_font_size),
                                      'cv_noise' => cp_ppp_get_option('cv_noise', CP_PPP_DEFAULT_cv_noise),
                                      'cv_noise_length' => cp_ppp_get_option('cv_noise_length', CP_PPP_DEFAULT_cv_noise_length),
                                      'cv_background' => cp_ppp_get_option('cv_background', CP_PPP_DEFAULT_cv_background),
                                      'cv_border' => cp_ppp_get_option('cv_border', CP_PPP_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => cp_ppp_get_option('cv_text_enter_valid_captcha', CP_PPP_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );   
    
    $message = "Item added";
} 
else if (isset($_GET['u']) && $_GET['u'] != '' && $current_user_access)
{
    $wpdb->query( $wpdb->prepare (
                                   'UPDATE `'.$wpdb->prefix.CP_PPP_FORMS_TABLE.'` SET form_name=%s WHERE id=%d',
                                   $_GET["name"], $_GET['u']
                                  ) 
                 );           
    $message = "Item updated";        
}
else if (isset($_GET['d']) && $_GET['d'] != '' && $current_user_access)
{
    $wpdb->query( $wpdb->prepare ( 'DELETE FROM `'.$wpdb->prefix.CP_PPP_FORMS_TABLE.'` WHERE id=%d', $_GET['d']) );       
    $message = "Item deleted";
} 
else if (isset($_GET['c']) && $_GET['c'] != '' && $current_user_access)
{
    $myrows = $wpdb->get_row( $wpdb->prepare ("SELECT * FROM ".$wpdb->prefix.CP_PPP_FORMS_TABLE." WHERE id=%d", $_GET['c']), ARRAY_A);    
    unset($myrows["id"]);
    $myrows["form_name"] = 'Cloned: '.$myrows["form_name"];
    $wpdb->insert( $wpdb->prefix.CP_PPP_FORMS_TABLE, $myrows);
    $message = "Item duplicated/cloned";
}
else if (isset($_GET['ac']) && $_GET['ac'] == 'st' && $current_user_access)
{   
    update_option( 'CP_CFPP_LOAD_SCRIPTS', ($_GET["scr"]=="1"?"0":"1") );   
    update_option( 'CP_CFPP_LOAD_SCRIPTS_ADMIN', ($_GET["scradmin"]=="1"?"0":"1") );    
    if ($_GET["chs"] != '')
    {
        $target_charset = esc_sql($_GET["chs"]);
        $tables = array( $wpdb->prefix.CP_PPP_POSTS_TABLE_NAME_NO_PREFIX, $wpdb->prefix.CP_PPP_FORMS_TABLE );                
        foreach ($tables as $tab)
        {  
            $myrows = $wpdb->get_results( "DESCRIBE {$tab}" );                                                                                 
            foreach ($myrows as $item)
	        {
	            $name = $item->Field;
		        $type = $item->Type;
		        if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat) || !strcasecmp($type, "CHAR") || !strcasecmp($type, "TEXT") || !strcasecmp($type, "MEDIUMTEXT"))
		        {
	                $wpdb->query("ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE {$target_charset}");	            
	            }
	        }
        }
    }
    $message = "Troubleshoot settings updated";
}


if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";

?>
<div class="wrap">
<h2>Payment Form for PayPal Pro</h2>

<script type="text/javascript">
 function cp_addItem()
 {
    var calname = document.getElementById("cp_itemname").value;
    document.location = 'options-general.php?page=cp_ppp&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname);       
 }
 
 function cp_updateItem(id)
 {
    var calname = document.getElementById("calname_"+id).value;    
    document.location = 'options-general.php?page=cp_ppp&u='+id+'&r='+Math.random()+'&name='+encodeURIComponent(calname);    
 }
 
 function cp_cloneItem(id)
 {
    document.location = 'options-general.php?page=cp_ppp&c='+id+'&r='+Math.random();  
 } 
 
 function cp_manageSettings(id)
 {
    document.location = 'options-general.php?page=cp_ppp&cal='+id+'&r='+Math.random();
 }
 
 function cp_viewMessages(id)
 {
    document.location = 'admin.php?page=cp_ppp&cal='+id+'&list=1&r='+Math.random();
 } 
 
 function cp_deleteItem(id)
 {
    if (confirm('Are you sure that you want to delete this item?'))
    {        
        document.location = 'options-general.php?page=cp_ppp&d='+id+'&r='+Math.random();
    }
 }
 
 function cp_updateConfig()
 {
    if (confirm('Are you sure that you want to update these settings?'))
    {        
        var scr = document.getElementById("ccscriptload").value;  
        var scradmin = document.getElementById("ccscriptloadadmin").value;    
        var chs = document.getElementById("cccharsets").value;    
        document.location = 'options-general.php?page=cp_ppp&ac=st&scr='+scr+'&scradmin='+scradmin+'&chs='+chs+'&r='+Math.random();
    }    
 }
 
</script>


<div id="normal-sortables" class="meta-box-sortables">


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form List / Items List</span></h3>
  <div class="inside">
  
  
  <table cellspacing="10"> 
   <tr>
    <th align="left">ID</th><th align="left">Form Name</th><th align="left">&nbsp; &nbsp; Options</th><?php if ($current_user_access) { ?> <th align="left">Shorttag for Pages and Posts</th> <?php } ?>
   </tr> 
<?php  

  $current_user = wp_get_current_user();
  $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_PPP_FORMS_TABLE );                                                                     
  foreach ($myrows as $item)    
  if ($current_user_access || @in_array($current_user->ID, unserialize($item->cp_user_access)))     
  {
?>
   <tr> 
    <td nowrap><?php echo $item->id; ?></td>
    <td nowrap><input type="text" name="calname_<?php echo $item->id; ?>" id="calname_<?php echo $item->id; ?>" value="<?php echo esc_attr($item->form_name); ?>" /></td>          
    
    <td nowrap>&nbsp; &nbsp; 
<?php if ($current_user_access) { ?>
                             <input type="button" name="calupdate_<?php echo $item->id; ?>" value="Update" onclick="cp_updateItem(<?php echo $item->id; ?>);" /> &nbsp;                              
                             <input type="button" name="calmanage_<?php echo $item->id; ?>" value="Settings" onclick="cp_manageSettings(<?php echo $item->id; ?>);" /> &nbsp;                              
<?php } ?>                             
                             <input type="button" name="calmanage_<?php echo $item->id; ?>" value="Messages" onclick="cp_viewMessages(<?php echo $item->id; ?>);" /> &nbsp;                              
<?php if ($current_user_access) { ?>                             
                             <input type="button" name="calclone_<?php echo $item->id; ?>" value="Clone" onclick="cp_cloneItem(<?php echo $item->id; ?>);" /> &nbsp;                              
                             <input type="button" name="caldelete_<?php echo $item->id; ?>" value="Delete" onclick="cp_deleteItem(<?php echo $item->id; ?>);" />                             
<?php } ?>                                                          
    </td>
<?php if ($current_user_access) { ?>      <td nowrap>[CP_PPP id="<?php echo $item->id; ?>"]</td>          <?php } ?>                            
   </tr>
<?php  
   } 
?>   
     
  </table> 
    
    
   
  </div>    
 </div> 

<?php if ($current_user_access) { ?>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>New Form</span></h3>
  <div class="inside"> 
   
    <form name="additem">
      Item Name:<br />
      <input type="text" name="cp_itemname" id="cp_itemname"  value="" /> <input type="button" onclick="cp_addItem();" name="gobtn" value="Add" />
      <br /><br />      
    </form>

  </div>    
 </div>


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Troubleshoot Area</span></h3>
  <div class="inside"> 
    <p><strong>Important!</strong>: Use this area <strong>only</strong> if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.</p>
    <form name="updatesettings">
      Script load method for public website:<br />
       <select id="ccscriptload" name="ccscriptload">
        <option value="0" <?php if (get_option('CP_CFPP_LOAD_SCRIPTS',"1") == "1") echo 'selected'; ?>>Classic (Recommended)</option>        
       </select><br />
       <em>* Change the script load method if the form doesn't appear in the public website.</em>                
      <br /><br />
<div style="display:none">   
      Script load method for admin area:<br />
       <select id="ccscriptloadadmin" name="ccscriptloadadmin">
        <option value="0" <?php if (get_option('CP_CFPP_LOAD_SCRIPTS_ADMIN',"1") == "1") echo 'selected'; ?>>Classic (Recommended)</option>
        <option value="1" <?php if (get_option('CP_CFPP_LOAD_SCRIPTS_ADMIN',"1") != "1") echo 'selected'; ?>>Direct</option>
       </select><br />
       <em>* Change the script load method if the form builder doesn't appear in the admin/settings area.</em>                
      <br /><br />
</div>
            
      Character encoding:<br />
       <select id="cccharsets" name="cccharsets">
        <option value="">Keep current charset (Recommended)</option>
        <option value="utf8_general_ci">UTF-8 (try this first)</option>
        <option value="latin1_swedish_ci">latin1_swedish_ci</option>
       </select><br />
       <em>* Update the charset if you are getting problems displaying special/non-latin characters. After updated you need to edit the special characters again.</em>
       <br />
       <input type="button" onclick="cp_updateConfig();" name="gobtn" value="UPDATE" />
      <br /><br />      
    </form>

  </div>    
 </div> 

<?php } ?>
  
</div> 


[<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">Request Custom Modifications</a>] | [<a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal" target="_blank">Help</a>]
</form>
</div>














