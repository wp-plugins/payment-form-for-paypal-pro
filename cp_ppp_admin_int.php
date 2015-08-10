<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;

if (!defined('CP_PPP_ID'))
    define ('CP_PPP_ID',intval($_GET["cal"]));
    

define('CP_PPP_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
define('CP_PPP_DEFAULT_fp_destination_emails', CP_PPP_DEFAULT_fp_from_email);

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_ppp_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

$scriptmethod = cp_ppp_get_option('script_load_method','0');

?>
<div class="wrap">
<h1>Payment Form for PayPal Pro</h1>

     <script type="text/javascript">        
       $easyFormQueryPPP = jQuery.noConflict(); 
       window.$ = jQuery; 
       if (typeof $easyFormQueryPPP == 'undefined')
       {
         // This code won't be used in most cases. This code is for preventing problems in wrong WP themes and conflicts with third party plugins.
         alert('JQuery not detected in your WordPress, some features may not work. You can contact our support service for more information: http://wordpress.dwbooster.com/support');         
       } 
     </script>    

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='options-general.php?page=cp_ppp';">
<br /><br />

<form method="post" action="" name="cpformconf"> 
<input name="cp_ppp_post_options" type="hidden" id="1" />
<input name="cp_ppp_id" type="hidden" value="<?php echo CP_PPP_ID; ?>" />

 
<div style="border:1px solid black;background-color:#ffffaa;padding:10px;">
   This plugin is for integrating <strong>PayPal Pro</strong> to accept credit cards directly into your website
   without navigating to a PayPal hosted payment page. 
   <br><br />
   <strong>The PayPal integration available in this plugin requires a PayPal Pro account.</strong>   
   <br><br />
   If you aren't sure if you have a <strong>PayPal Pro account</strong> or if you are looking for a classic <strong>PayPal Standard</strong> integration
then use the <a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal">CP Contact Form with PayPal</a> plugin.
   <br /><br />
   You can check the differences betwen <strong>PayPal Pro</strong> and <strong>PayPal Standard</strong> at <a href="https://www.paypal.com/webapps/mpp/compare-business-products" target="_blank">https://www.paypal.com/webapps/mpp/compare-business-products</a>
</div>

<br />        

<div id="normal-sortables" class="meta-box-sortables"> 
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Paypal Payment Configuration</span></h3>
  <div class="inside">

    <table class="form-table">      

        <tr valign="top">
        <th scope="row">Type of Paypal Integration:</th>
        <td><select name="enable_paypal" onchange="cfpp_update_pp_payment_selection();">             
             <option value="3" <?php if (cp_ppp_get_option('enable_paypal',CP_PPP_DEFAULT_ENABLE_PAYPAL) == '3') echo 'selected'; ?> >PayPal Pro</option>
            </select> 
            <br /><em style="font-size:11px;">Note: This plugin is for <strong>PayPal Pro</strong> payments. For <strong>PayPal Standard</strong> use the <a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal">CP Contact Form with PayPal plugin</a>.</em>
            
            <div id="cfpp_paypal_options_label" style="display:none;margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:260px;">
              Label for the "<strong>Pay with PayPal</strong>" option:<br />
              <input type="text" name="enable_paypal_option_yes" size="40" style="width:250px;" value="<?php echo esc_attr(cp_ppp_get_option('enable_paypal_option_yes',CP_PPP_DEFAULT_PAYPAL_OPTION_YES)); ?>" />
              <br />
              Label for the "<strong>Pay later</strong>" option:<br />
              <input type="text" name="enable_paypal_option_no" size="40" style="width:250px;"  value="<?php echo esc_attr(cp_ppp_get_option('enable_paypal_option_no',CP_PPP_DEFAULT_PAYPAL_OPTION_NO)); ?>" />
            </div>
            
            <div id="cfpp_paypal_options_pro"  style="display:none;margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:570px;">
              <table>
               <tr valign="top">        
               <th scope="row">PayPal Pro <nobr>API UserName</nobr></th>
               <td><input type="text" name="paypalpro_api_username" size="40" value="<?php echo esc_attr(cp_ppp_get_option('paypalpro_api_username','')); ?>" /></td>
               </tr>   
               <tr valign="top">        
               <th scope="row">PayPal Pro <nobr>API Password</nobr></th>
               <td><input type="text" name="paypalpro_api_password" size="20" value="<?php echo esc_attr(cp_ppp_get_option('paypalpro_api_password','')); ?>" /></td>
               </tr>   
               <tr valign="top">        
               <th scope="row">PayPal Pro <nobr>API Signature</nobr></th>
               <td><input type="text" name="paypalpro_api_signature" size="60" value="<?php echo esc_attr(cp_ppp_get_option('paypalpro_api_signature','')); ?>" /></td>
               </tr>      
              </table>            
            </div>
         </td>
        </tr>
        
        <tr valign="top" style="display:none">        
        <th scope="row">When should be sent the notification-confirmation emails?</th>
        <td><select name="paypal_notiemails">
             <option value="0" <?php if (cp_ppp_get_option('paypal_notiemails','0') != '0') echo 'selected'; ?>>When paid: AFTER receiving the PayPal payment</option> 
             <option value="1" <?php if (cp_ppp_get_option('paypal_notiemails','1') == '1') echo 'selected'; ?>>Always: BEFORE receiving the PayPal payment</option> 
            </select>
        </td>
        </tr>        
        
        <tr valign="top">        
        <th scope="row">Paypal Mode</th>
        <td><select name="paypal_mode">
             <option value="production" <?php if (cp_ppp_get_option('paypal_mode',CP_PPP_DEFAULT_PAYPAL_MODE) != 'sandbox') echo 'selected'; ?>>Production - real payments processed</option> 
             <option value="sandbox" <?php if (cp_ppp_get_option('paypal_mode',CP_PPP_DEFAULT_PAYPAL_MODE) == 'sandbox') echo 'selected'; ?>>SandBox - PayPal testing sandbox area</option> 
            </select>
        </td>
        </tr>
    
        <tr valign="top" style="display:none">        
        <th scope="row">Paypal email</th>
        <td><input type="text" name="paypal_email" size="40" value="<?php echo esc_attr(cp_ppp_get_option('paypal_email',CP_PPP_DEFAULT_PAYPAL_EMAIL)); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Request cost</th>
        <td><input type="text" name="request_cost" value="<?php echo esc_attr(cp_ppp_get_option('request_cost',CP_PPP_DEFAULT_COST)); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Currency</th>
        <td><input type="text" name="currency" value="<?php echo esc_attr(cp_ppp_get_option('currency',CP_PPP_DEFAULT_CURRENCY)); ?>" /></td>
        </tr>         
        
        <tr valign="top">
        <th scope="row" colspan="2">---- The following fields are useful in the <a href="http://wordpress.dwbooster.com/forms/paypal-payment-pro-form#download">commercial version of the plugin</a>:</th>
        </tr>
                
        <tr valign="top" style="color:#cccccc">        
        <th scope="row" style="color:#cccccc">Automatically identify prices on dropdown and checkboxes?</th>
        <td><input type="checkbox" name="paypal_identify_prices" value="1" <?php if (cp_ppp_get_option('paypal_identify_prices',CP_PPP_DEFAULT_PAYPAL_IDENTIFY_PRICES)) echo 'checked'; ?> />
            <em>If marked, any price in the selected checkboxes and dropdown fields will be added to the above request cost.</em>
            <br />
            <div id="cpcfppmoreinlink" style="color:#cccccc">[<a href="javascript:displaymorein();">+ more information</a>]</div>
            <div id="cpcfppmorein" style="display:none;border:1px solid black;background-color:#ffffaa;padding:10px;">             
             <p>If marked, any price in the selected checkboxes, radiobuttons and dropdown fields will be added to the above request cost. 
                Prices will be identified if are entered in the format $NNNN.NN, example: $30 , $24.99 and also $1,499.99</p>
             <p>For example, you can create a drop-down/select field with these options:
             <br /><br />
             &nbsp; - 1 hour tutoring for $30<br />
             &nbsp; - 2 hours tutoring for $60<br />
             &nbsp; - 3 hours tutoring for $90<br />
             &nbsp; - 4 hours tutoring for $120
             </p>
             <p>... and put the basic request cost to 0. After submission the price sent to PayPal will be the total sum of the selected options.</p>
             [<a href="javascript:displaylessin();">- less information</a>]
            </div>
            <script type="text/javascript">
             function displaymorein()
             {
                document.getElementById("cpcfppmorein").style.display="";
                document.getElementById("cpcfppmoreinlink").style.display="none";
             }
             function displaylessin()
             {
                document.getElementById("cpcfppmorein").style.display="none";
                document.getElementById("cpcfppmoreinlink").style.display="";
             }
            </script>
        </td>
        </tr>        
        
        <tr valign="top"style="color:#cccccc">
        <th scope="row"style="color:#cccccc">Use a specific field from the form for the payment amount</th>
        <td><select style="color:#cccccc" id="paypal_price_field" name="paypal_price_field" def="<?php echo esc_attr(cp_ppp_get_option('paypal_price_field', '')); ?>"></select>
           <br /><em>If selected, any price in the selected field will be added to the above request cost. Use this field for example for having an open donation amount.</em>
        </td>
        </tr>            
        
        <tr valign="top" style="display:none">
        <th scope="row">Taxes (percent)</th>
        <td><input type="text" name="request_taxes" value="<?php echo esc_attr(cp_ppp_get_option('request_taxes','0')); ?>" /></td>
        </tr>       
        
        <tr valign="top"  style="display:none">
        <th scope="row">Request address at PayPal</th>
        <td><select name="request_address">
             <option value="0" <?php if (cp_ppp_get_option('request_address','0') != '1') echo 'selected'; ?>>No</option> 
             <option value="1" <?php if (cp_ppp_get_option('request_address','0') == '1') echo 'selected'; ?>>Yes</option> 
            </select>
        </td>
        </tr>           
        
        <tr valign="top" style="display:none">        
        <th scope="row">A $0 amount to pay means:</th>
        <td><select name="paypal_zero_payment">
             <option value="0" <?php if (cp_ppp_get_option('paypal_zero_payment',CP_PPP_DEFAULT_PAYPAL_ZERO_PAYMENT) != '1') echo 'selected'; ?>>Let the user enter any amount at PayPal (ex: for a donation)</option> 
             <option value="1" <?php if (cp_ppp_get_option('paypal_zero_payment',CP_PPP_DEFAULT_PAYPAL_ZERO_PAYMENT) == '1') echo 'selected'; ?>>Don't require any payment. Form is submitted skiping the PayPal page.</option> 
            </select>
        </td>
        </tr>          
        
        <tr valign="top" style="display:none">
        <th scope="row">Paypal product name</th>
        <td><input type="text" name="paypal_product_name" size="50" value="<?php echo esc_attr(cp_ppp_get_option('paypal_product_name',CP_PPP_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>        
        
        <tr valign="top" style="display:none">
        <th scope="row">Paypal language</th>
        <td><input type="text" name="paypal_language" value="<?php echo esc_attr(cp_ppp_get_option('paypal_language',CP_PPP_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>         
        
        <tr valign="top" style="display:none">        
        <th scope="row">Payment frequency</th>
        <td><select name="paypal_recurrent" id="paypal_recurrent" onchange="ppp_update_recurrent();">
             <option value="0" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '0' || 
                                         cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == ''
                                        ) echo 'selected'; ?>>One time payment (default option, user is billed only once)</option>
             <option value="0.4" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '0.4') echo 'selected'; ?>>Bill the user every 1 week</option>
             <option value="1" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>>Bill the user every 1 month</option> 
             <option value="3" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>>Bill the user every 3 months</option> 
             <option value="6" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>>Bill the user every 6 months</option> 
             <option value="12" <?php if (cp_ppp_get_option('paypal_recurrent',CP_PPP_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>>Bill the user every 12 months</option> 
            </select>
            <div id="ppp_setupfee" style="width:350px;margin-top:5px;padding:5px;background-color:#ddddff;display:none;border:1px dotted black;">
             First period price (ex: include setup fee here if any):<br />
             <input type="text" name="paypal_recurrent_setup" size="10" value="<?php echo esc_attr(cp_ppp_get_option('paypal_recurrent_setup','0')); ?>" />
            </div> 
            <script type="text/javascript">
              function ppp_update_recurrent() {
                var f = document.getElementById("paypal_recurrent");
                  if (f.options[f.options.selectedIndex].value != '0')
                      document.getElementById("ppp_setupfee").style .display = "";
                  else
                      document.getElementById("ppp_setupfee").style .display = "none";    
              } 
              ppp_update_recurrent();
            </script>
        </td>        
        </tr>        
        
        <tr valign="top"style="color:#cccccc">
        <th scope="row" style="color:#cccccc">Discount Codes</th>
        <td> 
           <div id="dex_nocodes_availmsg">Loading...</div>
          
           <br />               
           <strong>Add new discount code:</strong>
           <br />
           <table border="0" cellpadding="0" cellspacing="0" style="margin-top:5px;">
            <tr>
             <td style="padding:0px;">Code:</td>
             <td style="padding:0px;">Discount:</td>
             <td style="padding:0px;">Can be used:</td>
             <td style="padding:0px;">Valid until:</td>             
             <td style="padding:0px;"></td>
            </tr> 
            <tr>
             <td style="padding:0px;" nowrap><input style="color:#cccccc" type="text" name="dex_dc_code" id="dex_dc_code" size="4" value="" /> &nbsp;</td>
             <td style="padding:0px;" nowrap><input style="color:#cccccc" type="text" size="3" name="dex_dc_discount" id="dex_dc_discount"  value="25" /><select style="color:#cccccc" name="dex_dc_discounttype" id="dex_dc_discounttype">
                   <option value="0">Percent</option>
                   <option value="1">Fixed Value</option>
                 </select> &nbsp;</td>
             <td style="padding:0px;" nowrap><select style="color:#cccccc" name="dex_dc_times" id="dex_dc_times">
                   <option value="0">Unlimited</option>
                   <?php for ($i=1;$i<20;$i++) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?> times</option><?php } ?>
                   <?php for ($i=20;$i<50;$i+=5) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?> times</option><?php } ?>
                   <?php for ($i=50;$i<500;$i+=10) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?> times</option><?php } ?>
                   <?php for ($i=500;$i<10000;$i+=50) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
                 </select> &nbsp;</td>
             <td style="padding:0px;" nowrap><input style="color:#cccccc" type="text"  size="10" name="dex_dc_expires" id="dex_dc_expires" value="" /> &nbsp;</td>             
             <td style="padding:0px;"><input style="color:#cccccc" type="button" name="dex_dc_subccode" id="dex_dc_subccode" value="Add" /></td>
            </tr>             
           </table>            
           <em>Note: Expiration date based in server time. Server time now is <?php echo date("Y-m-d H:i"); ?></em>
        </td>
        </tr>  
                   
     </table>  

  </div>    
 </div>    
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Notification Email Settings</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">"From" email</th>
        <td><input type="text" name="fp_from_email" size="40" value="<?php echo esc_attr(cp_ppp_get_option('fp_from_email', CP_PPP_DEFAULT_fp_from_email)); ?>" /></td>
        </tr>             
        <tr valign="top">
        <th scope="row">Destination emails (comma separated)</th>
        <td><input type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr(cp_ppp_get_option('fp_destination_emails', CP_PPP_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email subject</th>
        <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr(cp_ppp_get_option('fp_subject', CP_PPP_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Include additional information?</th>
        <td>
          <?php $option = cp_ppp_get_option('fp_inc_additional_info', CP_PPP_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Thank you page (after sending the message)</th>
        <td><input type="text" name="fp_return_page" size="70" value="<?php echo esc_attr(cp_ppp_get_option('fp_return_page', CP_PPP_DEFAULT_fp_return_page)); ?>" /></td>
        </tr>          
        <tr valign="top">
        <th scope="row">Email format?</th>
        <td>
          <?php $option = cp_ppp_get_option('fp_emailformat', CP_PPP_DEFAULT_email_format); ?>
          <select name="fp_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>>Plain Text (default)</option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>>HTML (use html in the textarea below)</option>
          </select>
        </td>
        </tr>        
        <tr valign="top">
        <th scope="row">Message</th>
        <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo esc_attr(cp_ppp_get_option('fp_message', CP_PPP_DEFAULT_fp_message)); ?></textarea></td>
        </tr>                                                               
     </table>  
  </div>    
 </div>  

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form Builder</span></h3>
  <div class="inside">   
  
     <div style="border:1px solid black;background-color:#ffffaa;padding:10px;">
        The following Form Builder is editable in the <a href="http://wordpress.dwbooster.com/forms/paypal-payment-pro-form#download">commercial version of the plugin</a>:
     </div>
     <br />
     <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(cp_ppp_cleanJSON(cp_ppp_get_option('form_structure', CP_PPP_DEFAULT_form_structure)))))); ?>" />
     <input type="hidden" name="templates" id="templates" value="<?php echo esc_attr( json_encode( cp_ppp_available_templates() ) ); ?>" />
                
     <script>
         $contactFormPPQuery = jQuery.noConflict();
         window.$ = jQuery;
         $contactFormPPQuery(document).ready(function() {
            var f = $contactFormPPQuery("#fbuilder").fbuilder();
     
            f.fBuild.loadData( "form_structure", "templates" );
            
            $contactFormPPQuery("#saveForm").click(function() {       
                f.fBuild.saveData("form_structure");
            });  
                 
            $contactFormPPQuery(".itemForm").click(function() {
     	       f.fBuild.addItem($contactFormPPQuery(this).attr("id"));
     	   });  
          
           $contactFormPPQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
     	   $contactFormPPQuery( "#fbuilder" ).droppable({
     	       accept: ".button",
     	       drop: function( event, ui ) {
     	           f.fBuild.addItem(ui.draggable.attr("id"));				
     	       }
     	   });
     		    
         });
        
        
         var $j = jQuery.noConflict();
         window.$ = jQuery;
         $j(function() {
         	$j("#dex_dc_expires").datepicker({     	                
                            dateFormat: 'yy-mm-dd'
                         }); 	
         });
         $j('#dex_nocodes_availmsg').load('<?php echo cp_ppp_get_site_url(true); ?>/?cp_ppp_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_PPP_ID; ?>');
         $j('#dex_dc_subccode').click (function() {
                                       var code = $j('#dex_dc_code').val();
                                       var discount = $j('#dex_dc_discount').val();
                                       var dc_times = $j('#dex_dc_times').val();                                       
                                       var discounttype = $j('#dex_dc_discounttype').val();
                                       var expires = $j('#dex_dc_expires').val();
                                       if (code == '') { alert('Please enter a code'); return; }
                                       if (parseInt(discount)+"" != discount) { alert('Please numeric discount percent'); return; }
                                       if (expires == '') { alert('Please enter an expiration date for the code'); return; }
                                       var params = '&add=1&expires='+encodeURI(expires)+'&discount='+encodeURI(discount)+'&discounttype='+encodeURI(discounttype)+'&code='+encodeURI(code)+'&tm='+encodeURI(dc_times);
                                       $j('#dex_nocodes_availmsg').load('<?php echo cp_ppp_get_site_url(true); ?>/?cp_ppp_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_PPP_ID; ?>'+params);
                                       $j('#dex_dc_code').val();
                                     });
                                     
          function dex_delete_coupon(id)                             
          {
             $j('#dex_nocodes_availmsg').load('<?php echo cp_ppp_get_site_url(true); ?>/?cp_ppp_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_PPP_ID; ?>&delete=1&code='+id);
          }        
        
        
        function generateCaptcha()
        {            
           var d=new Date();
           var f = document.cpformconf;    
           var qs = "&width="+f.cv_width.value;
           qs += "&height="+f.cv_height.value;
           qs += "&letter_count="+f.cv_chars.value;
           qs += "&min_size="+f.cv_min_font_size.value;
           qs += "&max_size="+f.cv_max_font_size.value;
           qs += "&noise="+f.cv_noise.value;
           qs += "&noiselength="+f.cv_noise_length.value;
           qs += "&bcolor="+f.cv_background.value;
           qs += "&border="+f.cv_border.value;
           qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
           qs += "&rand="+d;
           
           document.getElementById("captchaimg").src= "<?php echo cp_ppp_get_site_url(true); ?>/?cp_ppp=captcha&inAdmin=1"+qs;
        }
        
  
  function cfpp_update_pp_payment_selection() 
  {
     var f = document.cpformconf;
     var ppoption = f.enable_paypal.options[f.enable_paypal.selectedIndex].value;     
     if (ppoption == '2')
     {
         document.getElementById("cfpp_paypal_options_label").style.display = "";
         document.getElementById("cfpp_paypal_options_pro").style.display = "none";  
     }
     else if (ppoption == '3')
     {
         document.getElementById("cfpp_paypal_options_label").style.display = "none";
         document.getElementById("cfpp_paypal_options_pro").style.display = "";      
     } else
     {
         document.getElementById("cfpp_paypal_options_label").style.display = "none";  
         document.getElementById("cfpp_paypal_options_pro").style.display = "none";
     }
  }   
  
  cfpp_update_pp_payment_selection();        

     </script>
     
     <div style="background:#fafafa;width:780px;" class="form-builder">
     
         <div class="column width50">
             <div id="tabs">
     			<ul>
     				<li><a href="#tabs-1">Add a Field</a></li>
     				<li><a href="#tabs-2">Field Settings</a></li>
     				<li><a href="#tabs-3">Form Settings</a></li>
     			</ul>
     			<div id="tabs-1">
     			    
     			</div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>	
         </div>
         <div class="columnr width50 padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
             <!--<div class="button" id="saveForm">Save Form</div>-->
         </div>
         <div class="clearer"></div>
         
     </div>        
     
  </div>    
 </div>    
   
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Submit Button</span></h3>
  <div class="inside">   
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Submit button label (text):</th>
        <td><input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = esc_attr(cp_ppp_get_option('vs_text_submitbtn', 'Submit')); echo ($label==''?'Submit':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Previous button label (text):</th>
        <td><input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr(cp_ppp_get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row">Next button label (text):</th>
        <td><input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr(cp_ppp_get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
        </tr>  
        <tr valign="top">
        <td colspan="2"> - The  <em>class="pbSubmit"</em> can be used to modify the button styles. <br />
        - The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"cp-contact-form-with-paypal\css\stylepublic.css"</em>. <br />
        - For further modifications the submit button is located at the end of the file <em>"cp_ppp_public_int.inc.php"</em>.<br />
        - For general CSS styles modifications to the form and samples <a href="http://wordpress.dwbooster.com/faq/cp-contact-form-with-paypal#q61" target="_blank">check this FAQ</a>.
        </tr>
     </table>
  </div>    
 </div> 
 


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Validation Settings</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Use Validation?</th>
        <td>
          <?php $option = cp_ppp_get_option('vs_use_validation', CP_PPP_DEFAULT_vs_use_validation); ?>
          <select name="vs_use_validation">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <!--<option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>-->
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">"is required" text:</th>
        <td><input type="text" name="vs_text_is_required" size="40" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_is_required', CP_PPP_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>             
         <tr valign="top">
        <th scope="row">"is email" text:</th>
        <td><input type="text" name="vs_text_is_email" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_is_email', CP_PPP_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>       
        <tr valign="top">
        <th scope="row">"is valid captcha" text:</th>
        <td><input type="text" name="cv_text_enter_valid_captcha" size="70" value="<?php echo esc_attr(cp_ppp_get_option('cv_text_enter_valid_captcha', CP_PPP_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">"is valid date (mm/dd/yyyy)" text:</th>
        <td><input type="text" name="vs_text_datemmddyyyy" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_datemmddyyyy', CP_PPP_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"is valid date (dd/mm/yyyy)" text:</th>
        <td><input type="text" name="vs_text_dateddmmyyyy" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_dateddmmyyyy', CP_PPP_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"is number" text:</th>
        <td><input type="text" name="vs_text_number" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_number', CP_PPP_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"only digits" text:</th>
        <td><input type="text" name="vs_text_digits" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_digits', CP_PPP_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"under maximum" text:</th>
        <td><input type="text" name="vs_text_max" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_max', CP_PPP_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"over minimum" text:</th>
        <td><input type="text" name="vs_text_min" size="70" value="<?php echo esc_attr(cp_ppp_get_option('vs_text_min', CP_PPP_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>             
        
     </table>  
  </div>    
 </div>   
 
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Email Copy to User</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Send confirmation/thank you message to user?</th>
        <td>
          <?php $option = cp_ppp_get_option('cu_enable_copy_to_user', CP_PPP_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Email field on the form</th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr(cp_ppp_get_option('cu_user_email_field', CP_PPP_DEFAULT_cu_user_email_field)); ?>"></select></td>
        </tr>             
        <tr valign="top">
        <th scope="row">Email subject</th>
        <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr(cp_ppp_get_option('cu_subject', CP_PPP_DEFAULT_cu_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email format?</th>
        <td>
          <?php $option = cp_ppp_get_option('cu_emailformat', CP_PPP_DEFAULT_email_format); ?>
          <select name="cu_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>>Plain Text (default)</option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>>HTML (use html in the textarea below)</option>
          </select>
        </td>
        </tr>  
        <tr valign="top">
        <th scope="row">Message</th>
        <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo esc_attr(cp_ppp_get_option('cu_message', CP_PPP_DEFAULT_cu_message)); ?></textarea></td>
        </tr>        
     </table>  
  </div>    
 </div>  
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Captcha Verification</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Use Captcha Verification?</th>
        <td colspan="5">
          <?php $option = cp_ppp_get_option('cv_enable_captcha', CP_PPP_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>
        
        <tr valign="top">
         <th scope="row">Width:</th>
         <td><input type="text" name="cv_width" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_width', CP_PPP_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row">Height:</th>
         <td><input type="text" name="cv_height" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_height', CP_PPP_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Chars:</th>
         <td><input type="text" name="cv_chars" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_chars', CP_PPP_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>             

        <tr valign="top">
         <th scope="row">Min font size:</th>
         <td><input type="text" name="cv_min_font_size" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_min_font_size', CP_PPP_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Max font size:</th>
         <td><input type="text" name="cv_max_font_size" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_max_font_size', CP_PPP_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>        
         <td colspan="2" rowspan="">
           Preview:<br />
             <br />
            <img src="<?php echo cp_ppp_get_site_url(true); ?>/?cp_ppp=captcha&inAdmin=1"  id="captchaimg" alt="security code" border="0"  />            
         </td> 
        </tr>             
                

        <tr valign="top">
         <th scope="row">Noise:</th>
         <td><input type="text" name="cv_noise" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_noise', CP_PPP_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Noise Length:</th>
         <td><input type="text" name="cv_noise_length" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_noise_length', CP_PPP_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>          
        

        <tr valign="top">
         <th scope="row">Background:</th>
         <td><input type="text" name="cv_background" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_background', CP_PPP_DEFAULT_cv_background)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Border:</th>
         <td><input type="text" name="cv_border" size="10" value="<?php echo esc_attr(cp_ppp_get_option('cv_border', CP_PPP_DEFAULT_cv_border)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>    
        
        <tr valign="top">
         <th scope="row">Font:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>            
         </td>              
        </tr>                          
           
        
     </table>  
  </div>    
 </div>    
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Users with access to the messages list</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Select users with access (CTRL+click for multiple selection):</th>
        <td>
          <?php 
             $users = $wpdb->get_results( "SELECT user_login,ID FROM ".$wpdb->users." ORDER BY ID DESC" );                                                                     
             $options = unserialize(cp_ppp_get_option('cp_user_access', array())); 
          ?>          
          <select name="cp_user_access[]" multiple="multiple" size="5">
            <?php foreach ($users as $user) { ?>
             <option value="<?php echo $user->ID; ?>"<?php if ( in_array ($user->ID, $options) ) echo ' selected'; ?>><?php echo $user->user_login; ?></option>
            <?php  } ?>           
          </select>
        </td>
       </tr>
     </table>  
  </div>    
 </div>  
  
 
 
<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Note</span></h3>
  <div class="inside">
   To insert this form in a post/page, use the dedicated icon 
   <?php print '<a href="javascript:cp_ppp_insertForm();" title="'.__('Insert Payment Form for PayPal Pro').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert Payment Form for PayPal Pro').'" /></a>';     ?>
   which has been added to your Upload/Insert Menu, just below the title of your Post/Page.
   <br /><br />
  </div>
</div>   
  
</div> 


<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"  /></p>


[<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">Request Custom Modifications</a>] | [<a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal" target="_blank">Help</a>]
</form>
</div>
<script type="text/javascript">generateCaptcha();</script>













