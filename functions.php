<?php
/**
 * Theme Functions
 *
 * @package Wordpress
 * @subpackage daltons
 */

/**
 * Enqueue styles
 */
function daltons_styles() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.1.1' );
    wp_enqueue_style( 'icons', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css' );
    wp_enqueue_style( 'font', 'http://fonts.googleapis.com/css?family=Lato' );
    wp_enqueue_style( 'daltons-css', get_stylesheet_uri(), array('bootstrap-css'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'daltons_styles' );

/**
 * Enqueue scripts
 */
function daltons_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.1.1' );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery','bootstrap-js') );
}
add_action( 'wp_enqueue_scripts', 'daltons_scripts' );

function daltons_admin_init() {
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script( 'jquery-ui-tabs' );    
    wp_enqueue_style( 'admin-css', get_template_directory_uri() . '/css/admin.css' );
    wp_enqueue_script( 'admin-js', get_template_directory_uri() . '/js/admin.js', array('jquery') );
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts','daltons_admin_init');

function daltons_theme_setup() {
    add_theme_support( 'html5', array('search-form') );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus( array(
        'primary' => __('Primary', 'daltons'),
    ) );
}
add_action( 'after_setup_theme', 'daltons_theme_setup' );

function daltons_admin_menu() {
    add_theme_page('Theme Options', 'Theme Options', 'edit_theme_options', 'daltons-theme-options','daltons_theme_options');
}
add_action('admin_menu', 'daltons_admin_menu');

function daltons_theme_options() { 
    $option = get_option( 'daltons_theme_options' );?>
    <div id="sdn-theme-options-wrap" class="wrap">
        <h2>Theme Options</h2>
        <p><i>From here you can modify different settings for this theme.</i></p>
        <div id="theme-options-frame" class="metabox-holder">
            <section id="branding" class="postbox">
                <h3>Branding</h3>
                <div class="inside">
                    <img id="daltons-logo-src" src="<?php echo isset($option['branding'] ) ? $option['branding']['src'] : ''; ?>"><br>
                    <input type="hidden" id="daltons-logo-id" value="<?php echo isset($option['branding'] ) ? $option['branding']['id'] : ''; ?>">
                    <input type="button" id="daltons-logo-change" value="Set Image">
                    <input type="button" id="daltons-logo-remove" value="Remove Image">
                </div>
            </section>
        </div>
        <div>
            <p class="submit"><input id="save-changes-btn" name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>"></p>
            <h2 id="ajax-response-field" style="text-align: left"></h2>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(function($) {
        //handle image edit
        var uploadID = '';
        $(document).on("click", "#daltons-logo-change", function() { // button
            uploadID = "logo";
            formfield = 'add image';
            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });

        window.send_to_editor = function(html) {
            var imgurl = $('img',html).attr('src');
            var imgid = $('img', html).attr('class');
            imgid = imgid.replace(/(.*?)wp-image-/, '');
            tb_remove();
            $("#daltons-"+uploadID+"-src").attr( 'src', imgurl ); // image preview
            $("#daltons-"+uploadID+"-id").val(imgid); // hidden id 
        }
        $(document).mouseup(function(e) {
            if ( $("#TP_iframeContent").has(e.target).length === 0 ) {
                tb_remove();
            }
        });
        $(document).on("click", "#daltons-logo-remove", function() { // button
            $("#daltons-logo-src").attr('src', '');
            $("#daltons-logo-id").val('');
        });
        
        //handle save
        $("#save-changes-btn").click(function() {
            $("#save-changes-btn").val( 'Saving...' );
            //send ajax request to update
            var data = { 
                action: 'sdn_theme_options_ajax_action',
                sdn_theme_options: { 
                    branding: { src: $("#daltons-logo-src").attr('src'), id: $("#daltons-logo-id").val() },
                }
                
            };
            //console.log(data);
            $.post(ajaxurl, data, function( msg ) 
            {
                $("#save-changes-btn").val( msg );
            });//enf of .post()
        });
    });
    </script>
<?php }

function daltons_theme_options_ajax_callback() {
    global $wpdb; // this is how you get access to the database
    update_option( 'daltons_theme_options', $_POST['daltons_theme_options'] );

    echo 'Changes Saved'; // save confirmation
    exit(); // this is required to return a proper result
}
add_action( 'wp_ajax_daltons_theme_options_ajax_action', 'daltons_theme_options_ajax_callback' );


function daltons_metabox() {    
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
    if ($template_file == 'about.php') {
        add_meta_box('daltons-about-slide-meta', 'Slides', 'daltons_about_slide_meta', 'page', 'normal', 'high');
        add_meta_box('daltons-about-hours-meta', 'Hours', 'daltons_about_hours_meta', 'page', 'side', 'low');
        add_meta_box('daltons-about-contact-meta', 'Contact', 'daltons_about_contact_meta', 'page', 'side', 'low');
    }                          
}
add_action( 'add_meta_boxes', 'daltons_metabox' );

function daltons_about_slide_meta() {
    global $post;
    $slides = get_post_meta($post->ID, 'daltons_about_slides', true); ?>
    <table id="about-slide-table">
        <tbody><?php
            if ( $slides ) :
                foreach ( $slides as $slide ) {
                    $img_src = wp_get_attachment_image_src( $slide['img'] ); ?>
                    <tr class="about-slide">
                        <td>
                            <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                            <input type="hidden" name="img[]" value="<?php if($slide['img'] != '') echo esc_attr( $slide['img'] ); ?>" />
                            <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                            <div class="preview-container"><img id="slide_image-preview" src="<?php if($slide['img'] != '') echo $img_src[0]; ?>"/></div>
                        </td>
                    </tr><?php
                }
            else : ?>
                <tr class="about-slide">
                    <td>
                        <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                        <input type="hidden" name="img[]" placeholder="Image URL" />
                        <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                        <div class="preview-container"><img id="slide_image-preview" src="" /></div>
                    </td>
                </tr><?php 
            endif; ?>
            <tr class="empty-row screen-reader-text about-slide">
                <td>
                    <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                    <input type="hidden" name="img[]" placeholder="Image URL"/>
                    <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                    <div class="preview-container"><img id="slide_image-preview" src="" /></div>
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        <a id="add-slide" class="button" href="#">Add Slide</a>
        <input type="submit" class="metabox_submit button" value="Save" />
    </p><?php
}

function daltons_about_hours_meta() {

}

function daltons_about_contact_meta() {

}

/**
 * Save Meta Boxes
 */
function daltons_metabox_save( $post_id ) {
    $old = get_post_meta($post_id, 'daltons_about_slides', true);
    $new = array();
    $imgs = isset($_POST['img']) ? $_POST['img'] : false;
    $count = count( $imgs );
    if ($imgs) {
        for ( $i = 0; $i < $count; $i++ ) {
            if ( $imgs[$i] != '' ) :
                $new[$i]['img'] = stripslashes( $imgs[$i] ); 
            endif;
        }
        if ( !empty( $new ) && $new != $old )
            update_post_meta( $post_id, 'daltons_about_slides', $new );
        elseif ( empty($new) && $old )
            delete_post_meta( $post_id, 'daltons_about_slides', $old );
    }
}
add_action( 'save_post', 'daltons_metabox_save' );
