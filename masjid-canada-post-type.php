<?php
/**
 * Plugin Name: Masjid Canada Post Type
 * Description: This plugin creates a post type for Masjid posts and provides an admin interface to import posts from a Excel file. It assigns categories with states as parent and cities as child categories.
 * Version: 1.0
 * Author: Ibrar Ayoub
 * Author URI: https://wisetechcenter.com
 * License: GPL2
 * Text Domain: masjid-canada-post-type
 */


if (!defined('ABSPATH')) {
    exit;
}

include plugin_dir_path(__FILE__) . 'admin/masjid-posttype-custom-settings.php';

if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
    // Include the PhpSpreadsheet autoloader
    require_once plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php';
}

// Use the necessary PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\IOFactory;

/*
*    All Actions and Filter Hooks Used
**/

// Masjid Custom Post Type 
add_action('init', 'fds_masjid_canada_custom_post_type');
add_filter('post_type_link', 'fds_masjid_canada_custom_permalink', 10, 2); 
add_filter('rewrite_rules_array', 'fds_masjid_canada_rewrite_rules'); 

// Masjid Custom Texonomy 
add_action('init', 'fds_register_masjid_canada_taxonomy');
add_filter('term_link', 'fds_custom_masjid_canada_location_term_link', 10, 3);
add_action('init', 'fds_masjid_canada_location_rewrite_rules');

// Majids Fields Metabox
add_action('add_meta_boxes', 'fds_masjid_canada_add_meta_boxes'); 
add_action('save_post', 'fds_save_masjid_canada_meta_fields', 10, 2);

// Masjid Import From Excel Settings 
add_action('admin_menu', 'fds_masjid_canada_add_import_setting_page');

// Custom Template Registration 
add_action('init', 'fds_masjid_canada_plugin_register_template');
add_filter('template_include', 'fds_masjid_canada_plugin_register_taxonomy_template');
//add_filter('template_include', 'fds_masjid_canada_plugin_set_homepage_template'); 

// Masjid Post Type Notice Messages
add_action('admin_notices', 'fds_masjid_canada_import_guide_notice');

// Functions to modify the breadcrumb structure
add_filter('wpseo_breadcrumb_single_link' ,'fds_masjid_canada_remove_yoast_breadcrumb_link', 10 ,2);
add_filter('wpseo_breadcrumb_links', 'fds_masjid_canada_remove_duplicate_breadcrumbs');
add_filter('wpseo_breadcrumb_links', 'fds_masjid_canada_custom_breadcrumbs');

// Flush rewrite rules on plugin activation
register_activation_hook(__FILE__, 'fds_masjid_canada_flush_rewrite_rules');
function fds_masjid_canada_flush_rewrite_rules() {
    flush_rewrite_rules();
}

// Create Menu and Submenu Pages
function fds_masjid_canada_add_import_setting_page() {
    add_submenu_page(
        'edit.php?post_type=masjid_canada_cpt',
        'Import From Excel Page',
        'Import From Excel',
        'manage_options',
        'masjid-canada-import',
        'fds_masjid_canada_import_setting_page_callback'
    );
    add_submenu_page(
        'edit.php?post_type=masjid_canada_cpt', 
        'Masjids Setting Page',        
        'Settings',              
        'manage_options',               
        'masjid-canada-setting',       
        'fds_masjid_canada_import_settings_page_html' 
    );
}


function fds_register_masjid_canada_taxonomy() {
    $labels = array(
        'name'              => 'State/City',
        'singular_name'     => 'Add State/City',
        'search_items'      => 'Search State/City',
        'all_items'         => 'All State/City',
        'parent_item'       => 'Parent State',
        'parent_item_colon' => 'Parent State:',
        'edit_item'         => 'Edit State/City',
        'update_item'       => 'Update State/City',
        'add_new_item'      => 'Add New State/City',
        'new_item_name'     => 'New State/City Name',
        'menu_name'         => 'Add State/City',
    );

    $args = array(
        'hierarchical'      => true, 
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug' => 'masjid_canada_location', 
            'hierarchical' => true,
        ),
    );

    register_taxonomy('masjid_canada_location', array('masjid_canada_cpt'), $args);
}

function fds_custom_masjid_canada_location_term_link($term_link, $term, $taxonomy) {
    if ($taxonomy === 'masjid_canada_location') {
        if ($term->parent) {
            // Get the parent term (state)
            $parent_term = get_term($term->parent, $taxonomy);
            // Replace the term slug with parent/term slug
            $term_link = home_url('ca/' . $parent_term->slug . '/' . $term->slug);
        } else {
            // For parent terms (states)
            $term_link = home_url('ca/' . $term->slug);
        }
    }
    return $term_link;
}

function fds_masjid_canada_location_rewrite_rules() {
    add_rewrite_rule(
        '^ca/([^/]+)/([^/]+)/?$',
        'index.php?masjid_canada_location=$matches[2]',
        'top'
    );

    add_rewrite_rule(
        '^ca/([^/]+)/?$',
        'index.php?masjid_canada_location=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^ca/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?masjid_canada_cpt=$matches[3]',
        'top'
    );
}

/*
*
*   Create Custom Masjid Post 
*
**/

function fds_masjid_canada_custom_post_type() {
    $labels = array(
        'name'               => 'Masjid Posts',
        'singular_name'      => 'Masjid Post',
        'menu_name'          => 'Masjid Canada',
        'name_admin_bar'     => 'Masjid',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Masjid',
        'new_item'           => 'New Masjid',
        'edit_item'          => 'Edit Masjid',
        'view_item'          => 'View Masjid',
        'all_items'          => 'All Masjid Posts',
        'search_items'       => 'Search Masjid',
        'parent_item_colon'  => 'Parent Masjid:',
        'not_found'          => 'No masjid found.',
        'not_found_in_trash' => 'No masjid found in Trash.'
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite' => array('slug' => 'ca/%state%/%city%', 'with_front' => true),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => 20,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'masjid_canada_location' ) 
    );

    register_post_type( 'masjid_canada_cpt', $args );
}


// Add meta boxes to the "Masjid Canada" post type
function fds_masjid_canada_add_meta_boxes() {
    add_meta_box(
        'masjid_details',
        'Masjid Details',
        'fds_masjid_canada_meta_box_callback',
        'masjid_canada_cpt',
        'normal',
        'high'
    );
}

function fds_masjid_canada_custom_permalink($permalink, $post) {
    if ($post->post_type != 'masjid_canada_cpt') {
        return $permalink;
    }

    // Get terms in 'masjid_canada_location' taxonomy
    $terms = wp_get_post_terms($post->ID, 'masjid_canada_location');

    if (!empty($terms) && !is_wp_error($terms)) {
        $parent_term = null;
        $child_term = null;

        foreach ($terms as $term) {
            if ($term->parent == 0) {
                $parent_term = $term;
            } else {
                $child_term = $term;
            }
        }

        if ($parent_term) {
            $permalink = str_replace('%state%', $parent_term->slug, $permalink);
        }

        if ($child_term) {
            $permalink = str_replace('%city%', $child_term->slug, $permalink);
        }
    } else {
        $permalink = str_replace('%state%', 'uncategorized', $permalink);
        $permalink = str_replace('%city%', 'uncategorized', $permalink);
    }

    return $permalink;
}


function fds_masjid_canada_rewrite_rules($rules) {
    $new_rules = array();

    // Rule for hierarchical state/city permalinks
    $new_rules['ca([^/]+)/([^/]+)/([^/]+)/?$'] = 'index.php?masjid_canada_cpt=$matches[3]';

    return $new_rules + $rules;
}

function fds_masjid_canada_import_setting_page_callback() {
    if (isset($_POST['import_canada_masjid_excel']) && isset($_FILES['masjid_canada_excel_file'])) {
        $file = $_FILES['masjid_canada_excel_file']['tmp_name'];

        // Load the Excel file
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        // Check if the worksheet has any data
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        // Initialize counter
        $importedNewCount = 0;
        $importedUpdateCount = 0;

        if ($highestRow < 2 || empty($highestColumn)) {
            echo '<div class="updated"><p>Imported Excel file is empty.</p></div>';
        }else{

        // Process each row starting from the second row
        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Extract and sanitize all fields
            $post_id = isset($data[0]) ? intval($data[0]) : 0; // "Post ID" is in the first column
            $city = isset($data[1]) ? sanitize_text_field($data[1]) : '';
            $state = isset($data[2]) ? sanitize_text_field($data[2]) : '';
            $title = isset($data[3]) ? sanitize_text_field($data[3]) : '';
            $description = isset($data[4]) ? wp_kses_post($data[4]) : '';
            $image_url = isset($data[5]) ? esc_url($data[5]) : '';
            $address = isset($data[6]) ? sanitize_text_field($data[6]) : '';
            $postcode = isset($data[7]) ? sanitize_text_field($data[7]) : '';
            $phone = isset($data[8]) ? sanitize_text_field($data[8]) : '';
            $email = isset($data[9]) ? sanitize_email($data[9]) : '';
            $whatsapp = isset($data[10]) ? sanitize_text_field($data[10]) : '';
            $facebook = isset($data[11]) ? esc_url($data[11]) : '';
            $instagram = isset($data[12]) ? esc_url($data[12]) : '';
            $youtube = isset($data[13]) ? esc_url($data[13]) : '';
            $linkedin = isset($data[14]) ? esc_url($data[14]) : '';
            $website = isset($data[15]) ? esc_url($data[15]) : '';
            $google_map = isset($data[16]) ? sanitize_text_field($data[16]) : '';
            $ask_muslims_link = isset($data[17]) ? esc_url($data[17]) : '';

            // Skip importing for empty rows 
            if (empty($title) && empty($city) && empty($state)) {
                continue;
            }

            if ($post_id && get_post($post_id)) {
                // Update existing post
                wp_update_post(array(
                    'ID'           => $post_id,
                    'post_title'   => $title,
                    'post_content' => $description,
                ));

                // Update custom fields (meta data)
                update_post_meta($post_id, 'city', $city);
                update_post_meta($post_id, 'state', $state);
                update_post_meta($post_id, 'image_url', $image_url);
                update_post_meta($post_id, 'address', $address);
                update_post_meta($post_id, 'postcode', $postcode);
                update_post_meta($post_id, 'phone', $phone);
                update_post_meta($post_id, 'email', $email);
                update_post_meta($post_id, 'whatsapp', $whatsapp);
                update_post_meta($post_id, 'facebook', $facebook);
                update_post_meta($post_id, 'instagram', $instagram);
                update_post_meta($post_id, 'youtube', $youtube);
                update_post_meta($post_id, 'linkedin', $linkedin);
                update_post_meta($post_id, 'website', $website);
                update_post_meta($post_id, 'google_map', $google_map);
                update_post_meta($post_id, 'ask_muslims_link', $ask_muslims_link);

                // Handle categories for state and city
                $state_slug = fdsMasjidCanadaConvertState($state);
                
                // Get or create the State term
                $state_term = term_exists($state, 'masjid_canada_location');
                if ($state_term === 0 || $state_term === null) {
                    $state_term = wp_insert_term($state, 'masjid_canada_location', array('slug' => $state_slug));
                    if (is_wp_error($state_term)) {
                        continue;
                    }
                    $state_term_id = $state_term['term_id'];
                } else {
                    $state_term_id = $state_term['term_id'];
                }

                // Create child texonomy slug cityslug-stateslug
                $city_slug = sanitize_title($city) . '-' . sanitize_title($state_slug);

                // Check if the City already exists under this State
                $city_term = get_terms(array(
                    'taxonomy' => 'masjid_canada_location',
                    'name' => $city,
                    'parent' => $state_term_id,
                    'hide_empty' => false
                ));

                // If city exists under this state, use the existing term, otherwise create a new one
                if (!empty($city_term) && !is_wp_error($city_term)) {
                    $city_term_id = $city_term[0]->term_id; 
                } else {
                    // Create a new city term under the specific state
                    $city_term = wp_insert_term($city, 'masjid_canada_location', array(
                        'slug' => $city_slug,  
                        'parent' => $state_term_id 
                    ));

                    if (is_wp_error($city_term)) {
                        continue;
                    }
                    $city_term_id = $city_term['term_id']; 
                }

                $importedUpdateCount++;

                wp_set_post_terms($post_id, array($state_term_id, $city_term_id), 'masjid_canada_location');


            } else {
                // Create new post
                $post_id = wp_insert_post(array(
                    'post_title'   => $title,
                    'post_content' => $description,
                    'post_status'  => 'publish',
                    'post_type'    => 'masjid_canada_cpt'
                ));

                if ($post_id) {
                    // Add custom fields (meta data) to the post
                    update_post_meta($post_id, 'city', $city);
                    update_post_meta($post_id, 'state', $state);
                    update_post_meta($post_id, 'image_url', $image_url);
                    update_post_meta($post_id, 'address', $address);
                    update_post_meta($post_id, 'postcode', $postcode);
                    update_post_meta($post_id, 'phone', $phone);
                    update_post_meta($post_id, 'email', $email);
                    update_post_meta($post_id, 'whatsapp', $whatsapp);
                    update_post_meta($post_id, 'facebook', $facebook);
                    update_post_meta($post_id, 'instagram', $instagram);
                    update_post_meta($post_id, 'youtube', $youtube);
                    update_post_meta($post_id, 'linkedin', $linkedin);
                    update_post_meta($post_id, 'website', $website);
                    update_post_meta($post_id, 'google_map', $google_map);
                    update_post_meta($post_id, 'ask_muslims_link', $ask_muslims_link);

                    // Handle categories for state and city
                    $state_slug = fdsMasjidCanadaConvertState($state);

                    // Get or create the State term
                    $state_term = term_exists($state, 'masjid_canada_location');
                    if (!$state_term) {
                        $state_term = wp_insert_term($state, 'masjid_canada_location', array('slug' => $state_slug));
                    }
                    $state_term_id = $state_term['term_id'];

                    $city_slug = sanitize_title($city) . '-' . sanitize_title($state_slug);

                    // Check if city already exists under the same state
                    $city_term = get_terms(array(
                        'taxonomy' => 'masjid_canada_location',
                        'name' => $city,
                        'parent' => $state_term_id,
                        'hide_empty' => false
                    ));

                    // If city exists with the same state as parent
                    if (!empty($city_term) && !is_wp_error($city_term)) {
                        $city_term_id = $city_term[0]->term_id; 
                    } else {
                        
                        $city_term = wp_insert_term($city, 'masjid_canada_location', array(
                            'slug' => $city_slug,  
                            'parent' => $state_term_id 
                        ));

                        if (!is_wp_error($city_term)) {
                            $city_term_id = $city_term['term_id'];
                        }
                    }

                    $importedNewCount++; 

                    // Now assign the state and city to the post
                    wp_set_post_terms($post_id, array($state_term_id, $city_term_id), 'masjid_canada_location');



                }
            }
        }

        echo '<div class="updated"><p>Imported successfully! <b>' . $importedNewCount . '</b> created and <b>' . $importedUpdateCount . '</b> updated</p></div>';
      }
    }

    ?>
    <div class="wrap">
        <h2>Import From Excel</h2>
    <style type="text/css">
     @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

    .close {
        float: right;
        font-size: 21px;
        font-weight: bold;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: 0.2;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        opacity: 0.5;
    }

    button.close {
        padding: 0;
        background: transparent;
        border: 0;
        -webkit-appearance: none;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert h4 {
        margin-top: 0;
        color: inherit;
    }

    .alert-dismissable {
        padding-right: 35px;
    }

    .alert-dismissable .close {
        position: relative;
        top: -2px;
        right: -21px;
        color: inherit;
    }

    .alert-warning {
        background-color: #fcf8e3;
        border-color: #faebcc;
        color: #8a6d3b;
    }

    .alert-warning hr {
        border-top-color: #f7e1b5;
    }

    .alert-warning .alert-link {
        color: #66512c;
    }

    .alert-white {
        background-image: linear-gradient(to bottom, #FFFFFF, #F9F9F9);
        border-top-color: #d8d8d8;
        border-bottom-color: #bdbdbd;
        border-left-color: #cacaca;
        border-right-color: #cacaca;
        color: #404040;
        padding-left: 61px;
        position: relative;
    }

    .alert-white .icon {
        text-align: center;
        width: 45px;
        height: 100%;
        position: absolute;
        top: -1px;
        left: -1px;
        border: 1px solid #bdbdbd;
    }

    .alert-white .icon:after {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
        display: block;
        content: '';
        width: 10px;
        height: 10px;
        border: 1px solid #bdbdbd;
        position: absolute;
        border-left: 0;
        border-bottom: 0;
        top: 50%;
        right: -6px;
        margin-top: -5px;
        background: #fff;
    }

    .alert-white.rounded {
        border-radius: 3px;
    }

    .alert-white.rounded .icon {
        border-radius: 3px 0 0 3px;
    }

    .alert-white .icon i {
        font-size: 20px;
        color: #FFF;
        left: 12px;
        margin-top: -10px;
        position: absolute;
        top: 50%;
    }

    .alert-white.alert-warning .icon,
    .alert-white.alert-warning .icon:after {
        border-color: #d68000;
        background: #fc9700;
    }
     </style>   
    <div class='warning-alert-content'>
    
    <div class="alert alert-warning alert-white rounded">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <div class="icon"><i class="fa fa-warning"></i></div>
        <strong>Warning!</strong> Take Database Backup Before Importing.
    </div>
    
    </div>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="masjid_canada_excel_file" required accept=".xlsx" >
            <input type="submit" name="import_canada_masjid_excel" value="Import Excel" class="button button-primary">
            <p class="description"><b>Make sure to leave the Post ID column empty for new posts. <a href="https://docs.google.com/spreadsheets/d/1668mLfROqsfZDHV-6UwHBbfaM4pT9X4dYKK__qize98/edit?usp=sharing" target="_blank">Check Sample</a> <br>The posts you want to update should have a Post ID listed.</b></p>
        </form>            
    </div>
    <?php
}


/*
*
*   Code for template registration and selection for Homepage, Categories, Single Post Page
*
**/

function fds_masjid_canada_plugin_register_template() {
    add_filter( 'template_include', 'fds_masjid_canada_plugin_single_masjid_cpt_template', 1 );
}

// Single CPT Template for 'masjid_cpt'
function fds_masjid_canada_plugin_single_masjid_cpt_template( $template ) {
    if ( is_singular( 'masjid_canada_cpt' ) && file_exists( plugin_dir_path( __FILE__ ) . 'single-masjid_canada_cpt.php' ) ) {
        return plugin_dir_path( __FILE__ ) . 'single-masjid_canada_cpt.php';
    }
    return $template;
}

// Custom Taxonomy Template for 'masjid_location'
function fds_masjid_canada_plugin_register_taxonomy_template( $template ) {
    // Check for the custom taxonomy 'masjid_location'
    if ( is_tax( 'masjid_canada_location' ) && file_exists( plugin_dir_path( __FILE__ ) . 'taxonomy-masjid_canada_location.php' ) ) {
        return plugin_dir_path( __FILE__ ) . 'taxonomy-masjid_canada_location.php';
    }
    return $template;
}