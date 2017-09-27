<?
add_action("admin_menu", "add_new_menu_items");
function add_new_menu_items(){
  add_menu_page( "Dealership Information", "Dealership Information", "read","dealership-information", "dealer_options_page", "dashicons-star-empty" );
}
function setup_enque_js() {
  wp_enqueue_script( 'jquery.maskedinput', dirname( plugin_dir_url( __FILE__ ) ) . '/js/jquery.maskedinput.js' );
}
add_action( 'admin_enqueue_scripts', 'setup_enque_js' );

function dealer_options_page(){ ?>

    <div class="wrap">
     <div id="icon-options-general" class="icon32"></div>

     <?php settings_errors(); ?>

     <h1>Dealer Information</h1>
     
     <?php
     $active_tab = "dealer-options";
     if( isset( $_GET["tab"] ) ){
       if( $_GET["tab"] == "dealer-options" ){
          $active_tab = "dealer-options";
       }else{
         $active_tab = "ads-options";
       }
     } ?>

      <form method="post" action="options.php" enctype="multipart/form-data">
          <?php
          //creates hidden inputs fields for security in form submission (nonce)
          settings_fields("dealer_information_section");
          ?>
          <div id="accordion">
          <?php
          //runs through all add_settings_field call back functions and renders the input fields for the form
          do_settings_sections("dealership-information");
          submit_button();
          ?>
          </div>
      </form>

    </div>
    <!--  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <script>
  jQuery(function($) {
        $('#accordion h2').click(function() {

          if(jQuery(this).next('table').is(':hidden')) {
            $('table').css('display','none');
            jQuery(this).next('table').slideDown('slow');
          } else {
            jQuery(this).next('table').slideUp();
          }
        });
  });
  </script>

    <?php
}
/**
 * Function that creates setting sections and fields to be display
 * each section holds fields that relate to each other
 */
function display_options(){

    /*start general section*/
add_settings_section("dealer_general_section", "General Information <i class='fa fa-caret-right'></i>", "display_general_description", "dealership-information" );
    add_settings_field("dealership_name", "Dealership Name", "display_single_text_element", "dealership-information", "dealer_general_section", array('type'=>'text','label_for'=>"dealership_name", 'comment'=>'') );
    register_setting("dealer_information_section", "dealership_name");
    add_settings_field("dealership_tagline", "Tag Line", "display_single_text_element", "dealership-information", "dealer_general_section", array( 'type'=>'text','label_for'=>"dealership_tagline", 'comment'=>'') );
    register_setting("dealer_information_section", "dealership_tagline");
    add_settings_field("dealership_makes", "Dealership Make(s)", "display_single_text_element", "dealership-information", "dealer_general_section", array( 'type'=>'text','label_for'=>"dealership_makes", 'comment'=>'') );
    register_setting("dealer_information_section", "dealership_makes");
    add_settings_field("dealership_manager", "Manager's Best Price Name", "display_single_text_element", "dealership-information", "dealer_general_section", array( 'type'=>'text','label_for'=>"dealership_manager",'comment'=>'Defaults to use "Manager\'s" if no name provided' ) );
    register_setting("dealer_information_section", "dealership_manager" );
    add_settings_field("dealership_manager_pic", "Manager's Picture", "display_media_element", "dealership-information", "dealer_general_section", array( 'label_for'=>"dealership_manager_pic" ) );
    register_setting("dealer_information_section", "dealership_manager_pic", 'handle_file_upload');
    add_settings_field("dealership_disclaimer", "Standard Disclaimer", "display_single_textarea_element", "dealership-information", "dealer_general_section", array( 'type'=>'textarea','label_for'=>"dealership_disclaimer", 'comment'=>'Displayed on any page the has inventory' ) );
    register_setting("dealer_information_section", "dealership_disclaimer");

    /*start dealer contact section*/
add_settings_section("dealer_contact_person_section", "Dealership Contact Person <i class='fa fa-caret-right'></i>", "display_contact_description", "dealership-information" );
    add_settings_field("contact_name", "Contact Name", "display_combined_text_element", "dealership-information", "dealer_contact_person_section", array('type'=>'text','label_for'=>"contact_name", 'option'=>'dealership_contact_person', 'comment'=>'' ) );
    add_settings_field("contact_email", "Email", "display_combined_text_element", "dealership-information", "dealer_contact_person_section", array( 'type'=>'email','label_for'=>"contact_email", 'option'=>'dealership_contact_person', 'comment'=>'' ) );
    add_settings_field("contact_phone", "Phone", "display_combined_text_element", "dealership-information", "dealer_contact_person_section", array( 'type'=>'text','label_for'=>"contact_phone", 'option'=>'dealership_contact_person', 'comment'=>'' ) );
    add_settings_field("contact_mobile", "Mobile", "display_combined_text_element", "dealership-information", "dealer_contact_person_section", array( 'type'=>'text','label_for'=>"contact_mobile", 'option'=>'dealership_contact_person', 'comment'=>'' ) );
    register_setting("dealer_information_section", "dealership_contact_person");

    /*start address section*/
add_settings_section("dealer_address_section", "Dealership Address <i class='fa fa-caret-right'></i>", "display_address_description", "dealership-information");
    add_settings_field("dealership_street", "Street", "display_combined_text_element", "dealership-information", "dealer_address_section", array( 'type'=>'text','label_for'=>"dealership_street", 'option'=>'dealership_address', 'comment'=>'' ) );
    add_settings_field("dealership_city", "City", "display_combined_text_element", "dealership-information", "dealer_address_section", array( 'type'=>'text','label_for'=>"dealership_city", 'option'=>'dealership_address', 'comment'=>'' ) );
    add_settings_field("dealership_state", "State/Province", "display_combined_text_element", "dealership-information", "dealer_address_section", array( 'type'=>'text','label_for'=>"dealership_state", 'option'=>'dealership_address', 'comment'=>'Please use abbreviation' ) );
    add_settings_field("dealership_zip", "Zip/Postal", "display_combined_text_element", "dealership-information", "dealer_address_section", array( 'type'=>'text','label_for'=>"dealership_zip", 'option'=>'dealership_address', 'comment'=>'' ) );
    $o_CurrentUser = wp_get_current_user();
    if ( $o_CurrentUser->allcaps['administrator'] == '1' )
      add_settings_field("dealership_country", "Country", "display_select", "dealership-information", "dealer_address_section", array( 'countries'=> array( 'US', 'CA' ),'label_for'=>"dealership_country", 'option'=>'dealership_address', 'comment'=>'' ) );
    register_setting("dealer_information_section", "dealership_address");

    /*Sales Hours section */
add_settings_section("dealer_sales_hours_section", "Sales Hours <i class='fa fa-caret-right'></i>", "display_hours_description", "dealership-information");
    add_settings_field("monday", "Monday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"monday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("tuesday", "Tuesday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"tuesday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("wednesday", "Wednesday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"wednesday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("thursday", "Thursday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"thursday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("friday", "Friday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"friday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("saturday", "Saturday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"saturday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    add_settings_field("sunday", "Sunday", "display_combined_text_element", "dealership-information", "dealer_sales_hours_section", array( 'type'=>'text','label_for'=>"sunday", 'option'=>'dealership_sales_hours', 'comment'=>'' ) );
    register_setting("dealer_information_section", "dealership_sales_hours");

    /*Phone number section*/
add_settings_section("dealer_phone_section", "Dealership Phone Numbers <i class='fa fa-caret-right'></i>", "display_phone_description", "dealership-information");
    add_settings_field("toll_free", "Toll Free", "display_combined_text_element", "dealership-information", "dealer_phone_section", array( 'type'=>'text','label_for'=>"toll_free", 'option'=>'dealership_phone', 'comment'=>'' ) );
    add_settings_field("new_phone", "New Phone", "display_combined_text_element", "dealership-information", "dealer_phone_section", array( 'type'=>'text','label_for'=>"new_phone", 'option'=>'dealership_phone', 'comment'=>'' ) );
    add_settings_field("used_phone", "Used Phone", "display_combined_text_element", "dealership-information", "dealer_phone_section", array( 'type'=>'text','label_for'=>"used_phone", 'option'=>'dealership_phone', 'comment'=>'' ) );
    add_settings_field("mobile_phone", "SMS for Mobile Phone", "display_combined_element", "dealership-information", "dealer_phone_section", array( 'type'=>'text','label_for'=>"mobile_phone", 'option'=>'dealership_phone', 'comment'=>'If you have a carrier not on the list please contact support at support@ppadv.com and it will be added.' ) );
    add_settings_field("fax", "Fax", "display_combined_text_element", "dealership-information", "dealer_phone_section", array( 'type'=>'text','label_for'=>"fax", 'option'=>'dealership_phone', 'comment'=>'' ) );
    register_setting("dealer_information_section", "dealership_phone");

    /*Emails section*/
add_settings_section("dealer_email_section", "Dealership Emails <i class='fa fa-caret-right'></i>", "display_email_description", "dealership-information");
   	add_settings_field("default_email", "Default Email", "display_combined_text_element", "dealership-information", "dealer_email_section", array( 'type'=>'text','label_for'=>"default_email", 'option'=>'dealership_emails', 'comment'=>'Default for non-sales submissions or if no address provided' ) );
   	add_settings_field("hr_email", "Human Resources Email", "display_combined_text_element", "dealership-information", "dealer_email_section", array( 'type'=>'text','label_for'=>"hr_email", 'option'=>'dealership_emails', 'comment'=>'For HR leads' ) );
   	add_settings_field("sales_email", "Sales Email", "display_combined_text_element", "dealership-information", "dealer_email_section", array( 'type'=>'text','label_for'=>"sales_email", 'option'=>'dealership_emails', 'comment'=>'For sales leads' ) );
   	add_settings_field("credit_email", "Credit Email", "display_combined_text_element", "dealership-information", "dealer_email_section", array( 'type'=>'text','label_for'=>"credit_email", 'option'=>'dealership_emails', 'comment'=>'For credit leads, if not provided sales email will be used' ) );
   	add_settings_field("adf_email", "ADF Email", "display_combined_text_element", "dealership-information", "dealer_email_section", array( 'type'=>'text','label_for'=>"adf_email", 'option'=>'dealership_emails', 'comment'=>'For leads emailed to ADF/xml format to DMS' ) );
   	register_setting("dealer_information_section", "dealership_emails");

    /*Social Media Links*/
add_settings_section("dealer_social_media_section", "Social Media Links <i class='fa fa-caret-right'></i>", "display_social_description", "dealership-information");
    add_settings_field("facebook_url", "Facebook URL", "display_combined_text_element", "dealership-information", "dealer_social_media_section", array( 'type'=>'text','label_for'=>"facebook_url", 'option'=>'dealership_social_media_links', 'comment'=>'' ) );
    add_settings_field("twitter_url", "Twitter URL", "display_combined_text_element", "dealership-information", "dealer_social_media_section", array( 'type'=>'text','label_for'=>"twitter_url", 'option'=>'dealership_social_media_links', 'comment'=>'' ) );
    add_settings_field("youtube_url", "YouTube URL", "display_combined_text_element", "dealership-information", "dealer_social_media_section", array( 'type'=>'text','label_for'=>"youtube_url", 'option'=>'dealership_social_media_links', 'comment'=>'' ) );
    add_settings_field("google_plus_url", "Google Plus URL", "display_combined_text_element", "dealership-information", "dealer_social_media_section", array( 'type'=>'text','label_for'=>"google_plus_url", 'option'=>'dealership_social_media_links', 'comment'=>'' ) );
    add_settings_field("instagram_url", "Instagram URL", "display_combined_text_element", "dealership-information", "dealer_social_media_section", array( 'type'=>'text','label_for'=>"instagram_url", 'option'=>'dealership_social_media_links', 'comment'=>'' ) );
    register_setting("dealer_information_section", "dealership_social_media_links");

    /*Tracking and Conversion Codes*/
add_settings_section("dealer_added_codes_section", "Added Codes <i class='fa fa-caret-right'></i>", "display_codes_description", "dealership-information");
    add_settings_field("pre_head", "Placed Before Closing HEAD Tag", "display_combined_textarea_element", "dealership-information", "dealer_added_codes_section", array( 'type'=>'textarea','label_for'=>"pre_head", 'option'=>'dealership_added_code', 'comment'=> 'All Pages - Code to be placed before closing HEAD tag') );
    add_settings_field("pre_body", "Placed Before Closing BODY Tags", "display_combined_textarea_element", "dealership-information", "dealer_added_codes_section", array( 'type'=>'textarea','label_for'=>"pre_body", 'option'=>'dealership_added_code', 'comment'=> 'All Pages - Code to be placed before closing BODY tag') );
    add_settings_field("thank_you", "Thank You Page Only", "display_combined_textarea_element", "dealership-information", "dealer_added_codes_section", array( 'type'=>'textarea','label_for'=>"thank_you", 'option'=>'dealership_added_code', 'comment'=> 'Code placed before closing Body tag') );
    add_settings_field("home_only", "Home Page Only", "display_combined_textarea_element", "dealership-information", "dealer_added_codes_section", array( 'type'=>'textarea','label_for'=>"home_only", 'option'=>'dealership_added_code', 'comment'=> 'Code placed before closing Body tag') );
    add_settings_field("vdp_only", "VDP Page Only", "display_combined_textarea_element", "dealership-information", "dealer_added_codes_section", array( 'type'=>'textarea','label_for'=>"vdp_only", 'option'=>'dealership_added_code', 'comment'=> 'Code placed on VDP ONLY before closing Body tag') );
    register_setting("dealer_information_section", "dealership_added_code");

    /*Potratz Contact person section*/
add_settings_section("potratz_contact_person_section", "DLD Websites CSR <i class='fa fa-caret-right'></i>", "display_contact_description", "dealership-information" );
    add_settings_field("contact_name", "Contact Name", "display_combined_text_element", "dealership-information", "potratz_contact_person_section", array('type'=>'text','label_for'=>"contact_name", 'option'=>'potratz_contact_person', 'comment'=>'' ) );
    add_settings_field("contact_email", "Email", "display_combined_text_element", "dealership-information", "potratz_contact_person_section", array( 'type'=>'email','label_for'=>"contact_email", 'option'=>'potratz_contact_person', 'comment'=>'' ) );
    add_settings_field("contact_phone", "Phone", "display_combined_text_element", "dealership-information", "potratz_contact_person_section", array( 'type'=>'text','label_for'=>"contact_phone", 'option'=>'potratz_contact_person' , 'comment'=>'') );
    add_settings_field("contact_mobile", "Mobile", "display_combined_text_element", "dealership-information", "potratz_contact_person_section", array( 'type'=>'text','label_for'=>"contact_mobile", 'option'=>'potratz_contact_person', 'comment'=>'' ) );
    register_setting("dealer_information_section", "potratz_contact_person");

    /*Dealer Options section*/
add_settings_section("dealer_options_section", "Dealer Options <i class='fa fa-caret-right'></i>", "display_hide_site_option_description", "dealership-information" );
    add_settings_field("label_new_msrp", "New MSRP", "display_combined_text_element", "dealership-information", "dealer_options_section", array( 'type'=>'text','label_for'=>"label_new_msrp", 'option'=>'dld_labels', 'comment'=>'', 'values'=> 'MSRP' ) );
    add_settings_field("label_used_msrp", "Used MSRP", "display_combined_text_element", "dealership-information", "dealer_options_section", array( 'type'=>'text','label_for'=>"label_used_msrp", 'option'=>'dld_labels', 'comment'=>'', 'values'=> 'Our Price' ) );    
    add_settings_field("label_discount", "Discount Label", "display_combined_text_element", "dealership-information", "dealer_options_section", array( 'type'=>'text','label_for'=>"label_discount", 'option'=>'dld_labels', 'comment'=>'', 'values'=> 'Discount' ) );
    add_settings_field("label_new_sale_price", "New Sale Price", "display_combined_text_element", "dealership-information", "dealer_options_section", array( 'type'=>'text','label_for'=>"label_new_sale_price", 'option'=>'dld_labels', 'comment'=>'', 'values'=> 'Sale Price' ) );
    add_settings_field("label_used_sale_price", "Used Sale Price", "display_combined_text_element", "dealership-information", "dealer_options_section", array( 'type'=>'text','label_for'=>"label_used_sale_price", 'option'=>'dld_labels', 'comment'=>'', 'values'=> 'Now Price' ) );
    register_setting("dealer_information_section", "dld_labels");

    add_settings_field("hide_text_us", "Hide Text Us", "display_select_checkbox", "dealership-information", "dealer_options_section", array( 'option'=>'hide_site_options', 'label_for'=>"hide_text_us", 'values'=> '.text-us', 'comment'=>'' ) );
    add_settings_field("hide_fax_us", "Hide Fax Us", "display_select_checkbox", "dealership-information", "dealer_options_section", array( 'option'=>'hide_site_options', 'label_for'=>"hide_fax_us", 'values'=> '.fax-us', 'comment'=>'' ) );
    add_settings_field("hide_request_broadcast", "Hide Request Broadcast", "display_select_checkbox", "dealership-information", "dealer_options_section", array( 'option'=>'hide_site_options', 'label_for'=>"hide_request_broadcast", 'values'=> '.request-broadcast', 'comment'=>'' ) );
    add_settings_field("hide_facetime_option", "Hide Facetime Option", "display_select_checkbox", "dealership-information", "dealer_options_section", array( 'option'=>'hide_site_options', 'label_for'=>"hide_facetime_option", 'values'=> '.facetime-option', 'comment'=>'' ) );
    register_setting("dealer_information_section", "hide_site_options");
}

function handle_file_upload( $input ){
  if( !empty( $_FILES["dealership_manager_pic"]["tmp_name"] ) ){
    //wp function to handle upload of files
        //needs to have array('test_form' => FALSE) or else the upload will fail
        $urls = wp_handle_upload($_FILES["dealership_manager_pic"], array('test_form' => FALSE));
        $temp = $urls["url"];
        return $temp;
     }
     return get_option("dealership_manager_pic");
}

function display_general_description(){ ?>
  <!-- <p>General information about the dealership</p> -->
<?php }

function display_contact_description(){   ?>
  <!-- <p>This is the contact person for additional information</p> -->
<?php }

function display_social_images_description(){ ?>
  <!-- <p>Images to be used when pages from the website are shared on a social media site</p> -->
<?php }

function display_address_description(){ ?>
  <!-- <p>Address provided will be used for directions to rooftop.</p> -->
<?php }

function display_phone_description(){ ?>
  <!-- <p>Phone numbers to be used throughout the site</p> -->
<?php }

function display_hours_description(){ ?>
  <!-- <p>Displayed on the hours/directions page.</p> -->
<?php }

function display_email_description(){ ?>
  <!-- <p>Website forms will be submitted to the following email addresses. For multiple email addresses, separate w/ comma - eg. (jon@rooftop.com,sue@rooftop.com)</p> -->
<?php }

function display_social_description(){ ?>
  <!-- <p>Weither or not these display is based on your personalized theme. Not all websites will display these. If you website has a position for them and you do not supply and links the will not be displayed.</p> -->
<?php }

function display_codes_description(){ ?>
  <!-- <p>Added code will be placed on all pages of the website before the designated closing tags with the exception of the "Thank you" page code which will be placed on thank you pages only. Caution should be exercised when placing code as it can damage your website. Once placed it should be tested thoroughly to ensure that the website still functions properly. If you have a problem with the code you are trying to place, please contact your CSR and they will gladly have one of our programmers look into the issue.</p> -->
<?php }

function display_hide_site_option_description(){ ?>
 <!--  <p>Set features for dealership</p> -->
<?php }

function display_media_element(array $a_args){
  $s_label = $a_args['label_for'];
  $s_option = get_option( $s_label );
  add_meta_box( $s_label, "Upload Manager's Picture", 'dld_manager_image', 'dealership-information', 'normal', 'default' );
  ?>
    <img alt="Manager's Picture" height="50px" width="45px" src="<?php echo ( $s_option ); ?>"><br />
    <input id="<?php echo ( $s_label ); ?>" name='<?php echo ( $s_label ); ?>' type="file" value="<?php echo ( $s_option ); ?>" />
    <p class="description">Image must be 45px wide by 50px high.</p>
  
<?php }

/* Image Callback */
function dld_manager_image(  ){
  $a_PostMeta = $a_Args['args'][0];
  $i_AttachmentID = $a_PostMeta['Image'];
  $a_Image = wp_get_attachment_image( $i_AttachmentID, 'full' );
}

/**
* Function to create text related HTML inputs for setting fields saved individually in the database
*/
function display_single_text_element(array $a_args){
  $s_label = $a_args['label_for'];
  $s_type = $a_args['type'];
  $s_comment = $a_args['comment'];
  $s_option = get_option( $s_label );
  if( $s_label == 'dealership_manager' && $s_option == "" ){
    $s_option = "Manager's";
  }
  ?>
  <input type="<?php echo ( $s_type ); ?>" name="<?php echo ( $s_label ); ?>" id="<?php echo ( $s_label ); ?>" value="<?php echo ( $s_option ); ?>" /><p class="description"><?php echo ( $s_comment ); ?></p>
  
<?php }

/**
* Function to display a text area field for
*/
function display_single_textarea_element(array $a_args ){
  $s_label = $a_args['label_for'];
  $s_type = $a_args['type'];
  $s_comment = $a_args['comment'];
  ?>
  <textarea name="<?php echo ( $s_label ); ?>" id="<?php echo ( $s_label ); ?>" class="large-text" cols="50" rows="8" ><?php echo get_option( $s_label ); ?></textarea><p class="description"><?php echo ( $s_comment ); ?></p>
  
<?php }

/**
* Function used to display setting fields that are saved into one setting option as an array
*/
function display_combined_text_element(array $a_args) {
  $s_label = $a_args['label_for'];
  $s_get_option = $a_args['option'];
  $s_comment = $a_args['comment'];
  $s_type = $a_args['type'];
  $a_options = get_option( $s_get_option );
if(isset($a_args['values'])) {
  $s_value = $a_args['values'];
  if ($a_options[$s_label] == '') {
    $a_options[$s_label] = $s_value;
  }
} ?>

  <input id='<?php echo $s_label; ?>' name='<?php echo ( $s_get_option."[".$s_label."]" ); ?>' size='40' type='<?php echo ( $s_type ); ?>' value='<?php echo ( $a_options[$s_label] ); ?>' /><p class="description"><?php echo ( $s_comment ); ?></p>
  
<?php }


/**
 * Function used to display setting fields that are saved into one setting option as an array
 */
function display_combined_element(array $a_args) {
  $s_label = $a_args['label_for'];
  $s_get_option = $a_args['option'];
  $s_comment = $a_args['comment'];
  $s_type = $a_args['type'];
  $a_options = get_option( $s_get_option );
  ?>
  <select name='<?php echo ( $s_get_option."[carrier]" ); ?>'>
    <option value="mobile.att.net" <?php echo $a_options['carrier'] == "mobile.att.net" ? 'selected' : ''; ?> >AT&T</option>
    <option value="messaging.sprintpcs.com" <?php echo $a_options['carrier'] == "messaging.sprintpcs.com" ? 'selected' : ''; ?>>Sprint</option>
    <option value="tmomail.net" <?php echo $a_options['carrier'] == "tmomail.net" ? 'selected' : ''; ?>>T-Mobile</option>
    <option value="uscc.textmsg.com" <?php echo $a_options['carrier'] == "uscc.textmsg.com" ? 'selected' : ''; ?>>U.S. Celluar</option>
    <option value="vtext.com" <?php echo $a_options['carrier'] == "vtext.com" ? 'selected' : ''; ?>>Verizon</option>
  </select>
  <input id="SMSPhone" name='<?php echo ( $s_get_option."[sms_phone]" ); ?>' size='14' type='<?php echo ( $s_type ); ?>' value='<?php echo ( $a_options['sms_phone'] ); ?>' placeholder="(XXX) XXX-XXXX" /><p class="description"><?php echo ( $s_comment ); ?></p>
  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('#SMSPhone').mask( '(999) 999-9999' );
      $('#toll_free').mask( '(999) 999-9999' );
      $('#new_phone').mask( '(999) 999-9999' );
      $('#used_phone').mask( '(999) 999-9999' );
      $('#fax').mask( '(999) 999-9999' );
    });
  </script>

<?php }

/**
 * Function used to display setting fields that are saved into one setting option as an array
 */
function display_combined_textarea_element(array $a_args) {
  $s_label = $a_args['label_for'];
  $s_get_option = $a_args['option'];
  $s_comment = $a_args['comment'];
  $s_type = $a_args['type'];
  $a_options = get_option( $s_get_option );
  ?>

  <textarea id="<?php echo $s_label; ?>" name="<?php echo $s_get_option."[".$s_label."]"; ?>" class="large-text" cols="50" rows="8" ><?php echo $a_options[$s_label]; ?></textarea>
  
<?php }








function display_select( array $a_args) {
  $s_label = $a_args['label_for'];
  $s_get_option = $a_args['option'];
  $s_comment = $a_args['comment'];
  $a_Countries = $a_args['countries']; 
  $a_options = get_option( $s_get_option );

  foreach ( $a_Countries as $s_Country ) {
      if (isset($a_options[$s_label]) && $s_Country == $a_options[$s_label] ) {
       echo "<label>$s_Country:</label> <input type='radio' name='".$s_get_option."[".$s_label."]' value='$s_Country' checked /> ";
      } else {
       echo "<label>$s_Country:</label> <input type='radio' name='".$s_get_option."[".$s_label."]' value='$s_Country' /> ";
      }
    echo " ";
  }
  ?>

  <p class="description"><?php echo ( $s_comment ); ?></p>

<?php }

function display_select_checkbox( array $a_args) {
  $s_label = $a_args['label_for'];
  $s_get_option = $a_args['option'];
  $s_comment = $a_args['comment'];
  $s_Value = $a_args['values'];
  $a_options = get_option( $s_get_option );
  $checked = "";
  if(isset($a_options) && !empty($a_options) && is_array($a_options)) {
    if(isset($a_options[$s_label])) {
      $class = $a_options[$s_label];
      if ( $s_Value == $class ) {
        $checked = "checked";
      }
    }
  }
      echo "<input type='checkbox' name='".$s_get_option."[".$s_label."]' value='$s_Value' $checked />";
  ?>

  <p class="description"><?php echo ( $s_comment ); ?></p>
  
<?php }

add_action("admin_init", "display_options");

function setup_pre_head(){
  $a_options = get_option( 'dealership_added_code' );
  print_r( $a_options['pre_head'] );
}
add_action( 'wp_head', 'setup_pre_head' );

function setup_pre_body(){
  $a_options = get_option( 'dealership_added_code' );
  print_r( $a_options['pre_body'] );
}
add_action( 'wp_footer', 'setup_pre_body', 1000 );
?>
