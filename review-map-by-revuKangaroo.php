<?php
/*
Plugin Name: Review Map by RevuKangaroo
Plugin URI: http://revukangaroo.com/
Description: Show off your customer's online reviews with Review Map by Revukangaroo.
Author: RevuKangaroo
Version: 1.7
Author URI: http://revukangaroo.com/
*/



/****
*************************************************Code for creating a template starts here *****************************************************************************
*****/

class reviewmapby_revkang_SchemaMap
{

    protected $plugin_slug;

    private static $instance_map_schapi;

    protected $templates_map_sch;


    public static function reviewmapby_revkang_get_instance()
    {
        if (null == self::$instance_map_schapi) {
            self::$instance_map_schapi = new reviewmapby_revkang_SchemaMap();
        }
        return self::$instance_map_schapi;
    }


    //===============================Code to Initialize the plugin by setting===========================================

    private function __construct()
    {
        $this->templates = array();


        if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

            // 4.6 and older

            add_filter(
                'page_attributes_dropdown_pages_args',
                array($this, 'register_map_templates')
            );


        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                'theme_page_templates', array( $this, 'rmbyrevk_add_new_template' )
            );

        }


        add_filter(
            'wp_insert_post_data',
            array($this, 'register_map_templates')
        );

        // Code for template assigned and return it's path
        add_filter(
            'template_include',
            array($this, 'view_map_template')
        );
        // ==================================Code for Template Name.====================================================
        $this->templates = array(
            'schema-template.php' => 'RevuKangaroo Review Map Template',
        );

    }
    public function rmbyrevk_add_new_template( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
    }


    public function register_map_templates($atts_map_schapi)
    {
        $theme_map_schapi = wp_get_theme();

        $cache_key_schapi = 'page_templates-' . md5($theme_map_schapi->get_theme_root() . '/' . $theme_map_schapi->get_stylesheet());

        $templates_map_sch = $theme_map_schapi->get_page_templates();

        $templates_map_sch = array_merge($templates_map_sch, $this->templates);

        wp_cache_set($cache_key_schapi, $templates_map_sch, 'themes', 1800);
        return $atts_map_schapi;
    }


    public function view_map_template($template_map_schapi)
    {
        global $post;
		if(!empty($post)){
			if (!isset($this->templates[get_post_meta(
					$post->ID, '_wp_page_template', true
				)])
			) {

				return $template_map_schapi;

			}
			$file_map_schapi = plugin_dir_path(__FILE__) . get_post_meta(
					$post->ID, '_wp_page_template', true
				);


			if (file_exists($file_map_schapi)) {
				return $file_map_schapi;
			} else {
				echo $file_map_schapi;
			}
		}
        return $template_map_schapi;
    }
}

add_action('plugins_loaded', array('reviewmapby_revkang_SchemaMap', 'reviewmapby_revkang_get_instance'));


/*
********************************************************Code for creating a template ends here ******************************************************************
*/


/*
**************************************Code of  Adding Admin page to change the API keys for the iframe starts here **********************************************
***/


function reviewmapby_revkang_admin()
{

        $schema_hidden_mapvar = (isset($_POST['schema_hidden_map'])) ? sanitize_text_field($_POST['schema_hidden_map']): '';


    if ($schema_hidden_mapvar == 'Y' && current_user_can('administrator')) {

        $retmap_nonce_for_api = sanitize_text_field($_REQUEST['_wpnonce']);

        if ( ! wp_verify_nonce( $retmap_nonce_for_api, 'revu_mapapi_key_update_act' ) ) {

            die( 'Failed security check' );
        }




        $schema_api_mapkey = sanitize_text_field($_POST['schema_api_keys']);

        //=================== Update option from setting in the admin

        update_option('reviewmapby_revkang_custom_map_key_map', $schema_api_mapkey);


        //========settings post variables ========================
        $city_post_api = sanitize_text_field($_POST['city_api']);
        $cities_post_api = sanitize_text_field($_POST['cities_api']);
        $state_post_api  = sanitize_text_field($_POST['state_api']);
        $show_post_mapapi = sanitize_text_field($_POST['show_map_api']);
        $show_post_postapi = sanitize_text_field($_POST['show_posts_api']);
        $count_map_apisc   = sanitize_text_field($_POST['count_api']);
        $pincount_map_apisc = sanitize_text_field($_POST['pincount_api']);
        $zoom_level_map_api = sanitize_text_field($_POST['map_zoom_level_api']);

        if (isset($city_post_api)) {


            update_option('reviewmapby_revkang_city_api', $city_post_api);
        }

        if (isset($cities_post_api)) {

            update_option('reviewmapby_revkang_cities_api', $cities_post_api);
        }

        if (isset($state_post_api)) {

            update_option('reviewmapby_revkang_state_api', $state_post_api);
        }

        if (isset($show_post_mapapi)) {

            update_option('reviewmapby_revkang_show_map_api', $show_post_mapapi);

        }

        if (isset($show_post_postapi)) {
            update_option('reviewmapby_revkang_show_posts_api', $show_post_postapi);

        }

        if (isset($count_map_apisc)) {
            update_option('reviewmapby_revkang_count_api', $count_map_apisc);

        }

        if (isset($pincount_map_apisc)) {
            update_option('reviewmapby_revkang_pincount_api', $pincount_map_apisc);

        }

        if (isset($zoom_level_map_api)) {
            if($zoom_level_map_api >= 1 && $zoom_level_map_api <= 16){
            update_option('reviewmapby_revkang_map_zoom_level', $zoom_level_map_api);
            }    
        }

    }
    ?>
    <div class="wrap">

        <?php if ($schema_hidden_mapvar  == 'Y') {?><div class="updated notice is-dismissible"><p>API key has been updated successfully.</p></div> <?php } ?>

        <h1>RevuKangaroo Schema Map API Settings</h1>

        <form class="form_map_api" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <br> <input type="hidden" name="schema_hidden_map" value="Y">


            <strong>API Key</strong>
            <input type="text" name="schema_api_keys" class="regular-text regular-api-text" id="schema_api_keys" placeholder="Enter your API key here"
                                              value="<?php if (get_option('reviewmapby_revkang_custom_map_key_map')) {
                                                  echo get_option('reviewmapby_revkang_custom_map_key_map');
                                              } ?>" required>
            <br>


            <!-- City Name -->
            <!--<strong>City</strong>

<input placeholder="Enter a city (Example:-phoenix)" type="text" name="city_api" id="city_api" class="regular-text regular-api-text" value="<?php //if(get_option('reviewmapby_revkang_city_api')){echo get_option('reviewmapby_revkang_city_api');} ?>"/><br> -->

            <!-- Cities Name -->
            <strong>Cities</strong>
            <input placeholder="Enter cities separated by comma (Example:-mesa,phoenix)"
                                             type="text" name="cities_api" id="cities_api"
                                             class="regular-text regular-api-text" value="<?php if(get_option('reviewmapby_revkang_cities_api')){echo get_option('reviewmapby_revkang_cities_api');} ?>"/><br>


            <!-- State Name -->
            <strong>State</strong>
            <input placeholder="Enter a Region Code (Example:-AZ)" type="text" name="state_api"
                                            id="state_api" class="regular-text regular-api-text" value="<?php if(get_option('reviewmapby_revkang_state_api')){echo get_option('reviewmapby_revkang_state_api');} ?>"/><br>


            <!-- Show Map -->
            <strong>Show Map</strong>
                <select id="show_map_api" name="show_map_api" class="regular-api-text">
                    <option value="Yes" <?php if(get_option('reviewmapby_revkang_show_map_api') == 'Yes'){echo 'selected';} ?>>Yes</option>
                    <option value="No" <?php if(get_option('reviewmapby_revkang_show_map_api') == 'No'){echo 'selected';} ?>>No</option>
                </select> <br>


            <!-- Show posts -->
            <strong>Show Posts</strong>
                <select id="show_posts_api" name="show_posts_api" class="regular-api-text">
                    <option value="Yes" <?php if(get_option('reviewmapby_revkang_show_posts_api') == 'Yes'){echo 'selected';} ?>>Yes</option>
                    <option value="No" <?php if(get_option('reviewmapby_revkang_show_posts_api') == 'No'){echo 'selected';} ?>>No</option>
                </select> <br>


            <!-- Pagination count -->
            <strong>Pagination Count</strong>
                <select id="count_api" name="count_api" class="regular-api-text">

                    <option value="10" <?php if(get_option('reviewmapby_revkang_count_api') == '10'){echo 'selected';} ?>>10</option>
                    <option value="15" <?php if(get_option('reviewmapby_revkang_count_api') == '15'){echo 'selected';} ?>>15</option>
                    <option value="20" <?php if(get_option('reviewmapby_revkang_count_api') == '20'){echo 'selected';} ?>>20</option>
                    <option value="25" <?php if(get_option('reviewmapby_revkang_count_api') == '25'){echo 'selected';} ?>>25</option>
                    <option value="30" <?php if(get_option('reviewmapby_revkang_count_api') == '30'){echo 'selected';} ?>>30</option>

                </select> <br>


            <!-- Pin count -->
            <strong>Mile Radius</strong> <select id="pincount_api" name="pincount_api" class="regular-api-text">
                <option value="50" <?php if(get_option('reviewmapby_revkang_pincount_api') == '50'){echo 'selected';} ?>>50</option>
                <option value="100" <?php if(get_option('reviewmapby_revkang_pincount_api') == '100'){echo 'selected';} ?>>100</option>
                <option value="150" <?php if(get_option('reviewmapby_revkang_pincount_api') == '150'){echo 'selected';} ?>>150</option>
                <option value="300" <?php if(get_option('reviewmapby_revkang_pincount_api') == '300'){echo 'selected';} ?>>300</option>
               
            </select> <br>

            <!-- Map Zoom Level -->
            <strong>Map Zoom Level</strong>
                <input type="text" name="map_zoom_level_api" placeholder="Map Zoom Level (between 1 to 16)" id="map_zoom_level_api" class="regular-text regular-api-text" value="<?php if(get_option('reviewmapby_revkang_map_zoom_level')){echo get_option('reviewmapby_revkang_map_zoom_level');} ?>"> 

                <br><br>


            <?php wp_nonce_field('revu_mapapi_key_update_act'); ?>
            <input type="submit" name="submit_btn_map" class="button button-primary button_subm" id="submit_btn_map" value="Save">

        </form>
            </div>


<?php

}

function reviewmapby_revkang_admin_actions()
{
    add_options_page("Schema API Settings", "Schema API Settings", 'remove_users', "schema-api-settings", "reviewmapby_revkang_admin");

}

add_action('admin_menu', 'reviewmapby_revkang_admin_actions');


/**
**************************************Code of  Adding Admin page to change the API keys for the iframe ends here **************************************************
****/



/******************************* code to Attach a stylesheet starts here*********************************************************************************  */
function reviewmapby_revkang_load_plugin_css()
{
    $plugin_url_map_schapi = plugin_dir_url(__FILE__);

    wp_enqueue_style('style2', $plugin_url_map_schapi . 'css/style_map.css');
}

add_action('admin_print_styles', 'reviewmapby_revkang_load_plugin_css');

/******************************* code to Attach a stylesheet ends here*********************************************************************************  */

