<?php if ( !defined('CP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; } ?>
</p>
<?php
$raw_form_str = str_replace("\r"," ",str_replace("\n"," ",cp_ppp_cleanJSON(cp_ppp_get_option('form_structure', CP_PPP_DEFAULT_form_structure,$id))));

$form_data = json_decode( $raw_form_str );
if( is_null( $form_data ) ){
	$json = new JSON;
	$form_data = $json->unserialize( $raw_form_str );
}

if( !is_null( $form_data ) )	
{
	if( !empty( $form_data[ 0 ] ) )
	{
		foreach( $form_data[ 0 ] as $key => $object )
		{
			if( isset( $object->isDataSource ) && $object->isDataSource && function_exists( 'mcrypt_encrypt' ) )
			{
				$connection = new stdClass();
				$connection->connection = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, cp_ppp_get_option('form_structure', CP_PPP_DEFAULT_form_structure,$id), serialize( $object->list->database->databaseData ), MCRYPT_MODE_ECB ) );
				$connection->form = $id;
				
				$object->list->database->databaseData = $connection;
				$form_data[ 0 ][ $key ] = $object;
				$raw_form_str = json_encode( $form_data );
			}
		}
	}
	
	if( isset( $form_data[ 1 ] ) && isset( $form_data[ 1 ][ 0 ] ) && isset( $form_data[ 1 ][ 0 ]->formtemplate ) )
	{
		$templatelist = cp_ppp_available_templates();
		if( isset( $templatelist[ $form_data[ 1 ][ 0 ]->formtemplate ] ) );
		wp_enqueue_style ('cp_ppp_buikder_script_f_pf_styles', plugins_url(esc_attr( esc_url( $templatelist[ $form_data[ 1 ][ 0 ]->formtemplate ][ 'file' ] ) ), __FILE__));
		
	}	
}

$raw_form_str = str_replace('"','&quot;',esc_attr($raw_form_str));
?>
<script type="text/javascript">
 function cp_ppp_cerror(id){$dexQuery = jQuery.noConflict();$dexQuery("#hdcaptcha_error"+id).css('top',$dexQuery("#hdcaptcha"+id).outerHeight());$dexQuery("#hdcaptcha_error"+id).css("display","inline");}
 function doValidate<?php echo $CP_CPP_global_form_count; ?>(form)
 {
    $dexQuery = jQuery.noConflict();
    document.cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>.cp_ref_page.value = document.location;
    <?php if (cp_ppp_get_option('cv_enable_captcha', CP_PPP_DEFAULT_cv_enable_captcha,$id) != 'false') { ?> if ($dexQuery("#hdcaptcha_cp_ppp_post<?php echo $CP_CPP_global_form_count; ?>").val() == '')
    {
        setTimeout( "cp_ppp_cerror('<?php echo $CP_CPP_global_form_count; ?>')", 100);
        return false;
    }
    var result = $dexQuery.ajax({
        type: "GET",
        url: "<?php echo cp_ppp_get_site_url(); ?>?ps=<?php echo $CP_CPP_global_form_count; ?>"+String.fromCharCode(38)+"inAdmin=1"+String.fromCharCode(38)+"cp_ppp_id=<?php echo $id; ?>"+String.fromCharCode(38)+"hdcaptcha_cp_ppp_post="+$dexQuery("#hdcaptcha_cp_ppp_post<?php echo $CP_CPP_global_form_count; ?>").val(),
        async: false
    }).responseText;
    if (result.indexOf("captchafailed") != -1)
    {
        $dexQuery("#captchaimg<?php echo $CP_CPP_global_form_count; ?>").attr('src', $dexQuery("#captchaimg<?php echo $CP_CPP_global_form_count; ?>").attr('src')+'&'+Date());
        setTimeout( "cp_ppp_cerror('<?php echo $CP_CPP_global_form_count; ?>')", 100);
        return false;
    }
    else <?php } ?>
    {
<?php if (cp_ppp_get_option('enable_paypal',CP_PPP_DEFAULT_ENABLE_PAYPAL) == "3") { ?>
        if (document.getElementById("cp_ppp_paymentspro<?php echo $CP_CPP_global_form_count; ?>").value != "")
            $dexQuery.ajax({
                type: "POST",
                async: false,
                url: '<?php echo cp_ppp_get_site_url(); ?>/',
                data: $dexQuery("#cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>").serialize(), // serializes the form's elements.
                success: function(data)
                {
                   if (data != 'OK')
                       alert(data);
                   else
                       document.getElementById("cp_ppp_paymentspro<?php echo $CP_CPP_global_form_count; ?>").value = "";
                }
            });
        if (document.getElementById("cp_ppp_paymentspro<?php echo $CP_CPP_global_form_count; ?>").value == "")
        {
<?php } ?>        
            var cpefb_error = 0;
            $dexQuery("#cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>").find(".cpefb_error").each(function(index){
                if ($dexQuery(this).css("display")!="none")
                    cpefb_error++;
                });
            if (cpefb_error==0)
            {
                $dexQuery("#cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>").find("select").children().each(function(){
		        	    $dexQuery(this).val($dexQuery(this).attr("vt"));
	            });
	            $dexQuery("#cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>").find("input:checkbox,input:radio").each(function(){
		        	    $dexQuery(this).val($dexQuery(this).attr("vt"));
	            });
		    	$dexQuery("#cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>").find( '.ignore' ).parents( '.fields' ).remove();
		    }
            return true;
<?php if (cp_ppp_get_option('enable_paypal',CP_PPP_DEFAULT_ENABLE_PAYPAL) == "3") { ?>
        }
        else
        {
            return false;
        }
<?php } ?>
    }
 }
</script>
<form class="cpp_form" name="cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>" id="cp_ppp_pform<?php echo $CP_CPP_global_form_count; ?>" action="<?php get_site_url(); ?>" method="post" enctype="multipart/form-data" onsubmit="return doValidate<?php echo $CP_CPP_global_form_count; ?>(this);"><input type="hidden" name="cp_pform_psequence" value="<?php echo $CP_CPP_global_form_count; ?>" /><input type="hidden" name="cp_ppp_pform_process" value="1" /><input type="hidden" name="cp_ppp_id" value="<?php echo $id; ?>" /><input type="hidden" name="cp_ref_page" value="<?php esc_attr(cp_ppp_get_FULL_site_url); ?>" /><input type="hidden" name="form_structure<?php echo $CP_CPP_global_form_count; ?>" id="form_structure<?php echo $CP_CPP_global_form_count; ?>" size="180" value="<?php echo $raw_form_str; ?>" /><input type="hidden" id="cp_ppp_paymentspro<?php echo $CP_CPP_global_form_count; ?>" name="cp_ppp_paymentspro<?php echo $CP_CPP_global_form_count; ?>" value="1" />
<div id="fbuilder">
  <div id="fbuilder<?php echo $CP_CPP_global_form_count; ?>">
      <div id="formheader<?php echo $CP_CPP_global_form_count; ?>"></div>
      <div id="fieldlist<?php echo $CP_CPP_global_form_count; ?>"></div>
  </div>
</div>
<div id="cpcaptchalayer<?php echo $CP_CPP_global_form_count; ?>">
<?php if (count($codes)) { ?>
     <?php _e('Coupon code (optional)'); ?>:<br />
     <input type="text" name="couponcode" value=""><br />
<?php } ?>
<?php if (cp_ppp_get_option('enable_paypal',CP_PPP_DEFAULT_ENABLE_PAYPAL,$id) == '2') { ?>
      <div class="fields" id="field-c0">
         <label>Payment options:</label>
         <div class="dfield">
           <input type="radio" name="bccf_payment_option_paypal" vt="1" value="1" checked> <?php echo cp_ppp_get_option('enable_paypal_option_yes',CP_PPP_PAYPAL_OPTION_YES,$id); ?><br />
           <input type="radio" name="bccf_payment_option_paypal" vt="0" value="0"> <?php echo cp_ppp_get_option('enable_paypal_option_no',CP_PPP_PAYPAL_OPTION_NO,$id); ?>
         </div>
         <div class="clearer"></div>
      </div>
<?php } ?>
<?php if (cp_ppp_get_option('enable_paypal',CP_PPP_DEFAULT_ENABLE_PAYPAL) == "3") @include_once dirname( __FILE__ ) . '/cp_ppp_paypal_pro_int.inc.php';  ?>
<?php if (cp_ppp_get_option('cv_enable_captcha', CP_PPP_DEFAULT_cv_enable_captcha,$id) != 'false') { ?>
  <br /><?php echo __('Please enter the security code','cpppp'); ?>:<br />
  <img src="<?php echo cp_ppp_get_site_url().'/?cp_ppp=captcha&ps='.$CP_CPP_global_form_count.'&inAdmin=1&width='.cp_ppp_get_option('cv_width', CP_PPP_DEFAULT_cv_width,$id).'&height='.cp_ppp_get_option('cv_height', CP_PPP_DEFAULT_cv_height,$id).'&letter_count='.cp_ppp_get_option('cv_chars', CP_PPP_DEFAULT_cv_chars,$id).'&min_size='.cp_ppp_get_option('cv_min_font_size', CP_PPP_DEFAULT_cv_min_font_size,$id).'&max_size='.cp_ppp_get_option('cv_max_font_size', CP_PPP_DEFAULT_cv_max_font_size,$id).'&noise='.cp_ppp_get_option('cv_noise', CP_PPP_DEFAULT_cv_noise,$id).'&noiselength='.cp_ppp_get_option('cv_noise_length', CP_PPP_DEFAULT_cv_noise_length,$id).'&bcolor='.cp_ppp_get_option('cv_background', CP_PPP_DEFAULT_cv_background,$id).'&border='.cp_ppp_get_option('cv_border', CP_PPP_DEFAULT_cv_border,$id).'&font='.cp_ppp_get_option('cv_font', CP_PPP_DEFAULT_cv_font,$id); ?>"  id="captchaimg<?php echo $CP_CPP_global_form_count; ?>" alt="security code" border="0"  />
  <br />
  <?php echo __('Security Code','cpppp'); ?>:<br />
  <div class="dfield">
  <input type="text" size="20" name="hdcaptcha_cp_ppp_post" id="hdcaptcha_cp_ppp_post<?php echo $CP_CPP_global_form_count; ?>" value="" />
  <div class="error cpefb_error message" id="hdcaptcha_error<?php echo $CP_CPP_global_form_count; ?>" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"><?php _e('Please enter the captcha verification code.'); ?></div>
  </div>
  <br />
<?php } ?>
</div>
<div id="cp_subbtn<?php echo $CP_CPP_global_form_count; ?>" class="cp_subbtn"><?php _e($button_label); ?></div>
</form>