<?php

/*
*
*   Masjid Post Type Settings Meta Box, Breadcrumbs, State Name Two Letter etc
*
**/


// Render the meta box content
function fds_masjid_canada_meta_box_callback($post) {
    // Use nonce for verification
    wp_nonce_field('fds_save_masjid_canada_meta_box_data', 'fds_masjid_canada_meta_box_nonce');

    // Retrieve existing values from the database
    $city = get_post_meta($post->ID, 'city', true);
    $state = get_post_meta($post->ID, 'state', true);
    $image_url = get_post_meta($post->ID, 'image_url', true);
    $address = get_post_meta($post->ID, 'address', true);
    $postcode = get_post_meta($post->ID, 'postcode', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    $email = get_post_meta($post->ID, 'email', true);
    $whatsapp = get_post_meta($post->ID, 'whatsapp', true);
    $facebook = get_post_meta($post->ID, 'facebook', true);
    $instagram = get_post_meta($post->ID, 'instagram', true);
    $youtube = get_post_meta($post->ID, 'youtube', true);
    $linkedin = get_post_meta($post->ID, 'linkedin', true);
    $website = get_post_meta($post->ID, 'website', true);
    $google_map = get_post_meta($post->ID, 'google_map', true);
    $ask_muslims_link = get_post_meta($post->ID, 'ask_muslims_link', true);

    // City Field
    echo '<p><label for="city">City</label><br/>';
    echo '<input type="text" style="width:100%;" id="city" name="city" value="' . esc_attr($city) . '" required />';
    echo 'e.g: Aliceville</p>';

    // State Field
    echo '<p><label for="state">State</label><br/>';
    echo '<input type="text" style="width:100%;" id="state" name="state" value="' . esc_attr($state) . '" required />';
    echo 'e.g: Alabama</p>';

    // Image URL Field
    echo '<p><label for="image_url">Image URL</label><br/>';
    echo '<input type="url" style="width:100%;" id="image_url" name="image_url" value="' . esc_attr($image_url) . '" />';
    echo 'e.g: https://example.com/image.jpg</p>';

    // Address Field
    echo '<p><label for="address">Address</label><br/>';
    echo '<input type="text" style="width:100%;" id="address" name="address" value="' . esc_attr($address) . '" required />';
    echo 'e.g: 903 Columbus Rd, Aliceville, AL 35442, USA</p>';

    // Postcode Field
    echo '<p><label for="postcode">Postcode</label><br/>';
    echo '<input type="text" style="width:100%;" id="postcode" name="postcode" value="' . esc_attr($postcode) . '" />';
    echo 'e.g: AL 35442</p>';

    // Phone Field
    echo '<p><label for="phone">Phone</label><br/>';
    echo '<input type="tel" style="width:100%;" id="phone" name="phone" value="' . esc_attr($phone) . '" required />';
    echo 'e.g: (907) 248-7333</p>';

    // Email Field
    echo '<p><label for="email">Email</label><br/>';
    echo '<input type="email" style="width:100%;" id="email" name="email" value="' . esc_attr($email) . '" required />';
    echo 'e.g: info@example.com</p>';

    // WhatsApp Field
    echo '<p><label for="whatsapp">WhatsApp</label><br/>';
    echo '<input type="tel" style="width:100%;" id="whatsapp" name="whatsapp" value="' . esc_attr($whatsapp) . '" />';
    echo 'e.g: 14200000000</p>';

    // Facebook Field
    echo '<p><label for="facebook">Facebook</label><br/>';
    echo '<input type="url" style="width:100%;" id="facebook" name="facebook" value="' . esc_attr($facebook) . '" />';
    echo 'e.g: https://facebook.com/masjidpage</p>';

    // Instagram Field
    echo '<p><label for="instagram">Instagram</label><br/>';
    echo '<input type="url" style="width:100%;" id="instagram" name="instagram" value="' . esc_attr($instagram) . '" />';
    echo 'e.g: https://instagram.com/masjidprofile</p>';

    // YouTube Field
    echo '<p><label for="youtube">YouTube</label><br/>';
    echo '<input type="url" style="width:100%;" id="youtube" name="youtube" value="' . esc_attr($youtube) . '" />';
    echo 'e.g: https://youtube.com/channel/masjidchannel</p>';

    // LinkedIn Field
    echo '<p><label for="linkedin">LinkedIn</label><br/>';
    echo '<input type="url" style="width:100%;" id="linkedin" name="linkedin" value="' . esc_attr($linkedin) . '" />';
    echo 'e.g: https://linkedin.com/company/masjid</p>';

    // Website Field
    echo '<p><label for="website">Website</label><br/>';
    echo '<input type="url" style="width:100%;" id="website" name="website" value="' . esc_attr($website) . '" />';
    echo 'e.g: https://www.masjidwebsite.com</p>';

    // Google Map Field with Description
    echo '<p><label for="google_map">Google Map</label><br/>';
    echo '<input type="text" style="width:100%;" id="google_map" name="google_map" value="' . esc_attr($google_map) . '" />';
    echo 'Enter the Google Map Shortcode:<br>e.g: [su_gmap address="370 Somerset St, Saint John, NB E2K 2Y4, Canada" responsive="yes"]';

    // Ask Muslims Link Field
    echo '<p><label for="ask_muslims_link">Ask Muslims Link</label><br/>';
    echo '<input type="url" style="width:100%;" id="ask_muslims_link" name="ask_muslims_link" value="' . esc_attr($ask_muslims_link) . '" />';
    echo 'e.g: https://www.askmuslims.com/</p>';


}


function fds_save_masjid_canada_meta_fields($post_id, $post) {
    // Verify nonce
    if (!isset($_POST['fds_masjid_canada_meta_box_nonce']) || !wp_verify_nonce($_POST['fds_masjid_canada_meta_box_nonce'], 'fds_save_masjid_canada_meta_box_data')) {
        return;
    }

    // Verify if this is an auto-save routine
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if this is the correct post type
    if ($post->post_type != 'masjid_canada_cpt') {
        return;
    }


    // Check if title, city, or state is empty
    $title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : '';
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';

    if (empty($title) || empty($city) || empty($state)) {
        // If any of the fields are empty, do not create/update the post
        return;
    }


    // Sanitize and update the meta fields
    $fields = [
        'city',
        'state',
        'image_url',
        'address',
        'postcode',
        'phone',
        'email',
        'whatsapp',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'website',
        'google_map',
        'ask_muslims_link'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            if ($field === 'email') {
                $value = sanitize_email($value);
            } elseif (in_array($field, ['image_url', 'facebook', 'instagram', 'youtube', 'linkedin', 'website', 'ask_muslims_link'])) {
                $value = esc_url_raw($value);
            } else {
                $value = sanitize_text_field($value);
            }

            update_post_meta($post_id, $field, $value);
        }
    }

    // Get the state and city from the meta fields
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';

    if ($city && $state) {
        $canada_state_slug = fdsMasjidCanadaConvertState($state);

         // Get or create the State term
         $state_term = term_exists($state, 'masjid_canada_location');
         if (!$state_term) {
               $state_term = wp_insert_term($state, 'masjid_canada_location', array('slug' => $canada_state_slug));
          }
          $state_term_id = $state_term['term_id'];

         $canada_city_slug = sanitize_title($city) . '-' . sanitize_title($canada_state_slug);

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
                        'slug' => $canada_city_slug,  
                        'parent' => $state_term_id 
                   ));

                   if (!is_wp_error($city_term)) {
                        $city_term_id = $city_term['term_id'];
                    }
                }

        // Assign state and city categories to the post
        wp_set_post_terms($post_id, array($state_term_id, $city_term_id), 'masjid_canada_location');

    }
}


/*
*
*   Settings Page for Import Notices 
*
**/

// Add admin notice to display the documentation link
function fds_masjid_canada_import_guide_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'masjid_canada_cpt') {
        // Get the dynamic URL for the sample file using plugins_url()
        $plugin_url = plugin_dir_url(dirname(__FILE__));
        $sample_file_url = $plugin_url . 'assets/masajid-import-sample.xlsx';

        echo '<div class="notice notice-info is-dismissible">
                <p>Need help importing Masjid posts? <a href="https://scribehow.com/shared/Importing_Masjid_Posts_from_Excel__oYknTIfgRVCy9Pdwmvv9oA" target="_blank">Check out our documentation here</a>.</p>
              </div>
        
              <div class="notice notice-info">
                <p><strong>Download Masjids Posts Sample Excel Import File:</strong>
                    <a href="' . esc_url($sample_file_url) . '" download>Click here.</a></p>
              </div>';
    }     
}



// Callback function to render the settings page HTML
function fds_masjid_canada_import_settings_page_html() {
   	
    $plugin_url = plugin_dir_url(dirname(__FILE__));
    $sample_file_url = $plugin_url . 'assets/masajid-import-sample.xlsx';
    
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save the settings if the form is submitted
    if (isset($_POST['fds_canada_add_ask_muslim_links_html'])) {
        check_admin_referer('fds_save_canada_html');
        update_option('fds_canada_add_ask_muslim_links_html', wp_kses_post($_POST['fds_canada_add_ask_muslim_links_html']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    // Get the current value of the custom HTML
    $fds_canada_add_ask_muslim_links_html = get_option('fds_canada_add_ask_muslim_links_html', '');

    echo '<div class="wrap">
        <h1>Masjids Settings Page</h1>
        <h4>Need help importing Masjid posts? <a href="https://scribehow.com/shared/Importing_Masjid_Posts_from_Excel__oYknTIfgRVCy9Pdwmvv9oA" target="_blank">Check out our documentation here</a>.</h4>
        
        <h4><strong>Download Masjids Posts Sample Excel Import File:</strong>
        <a href="' . esc_url($sample_file_url) . '" download>Click here.</a></h4>
        
        <form method="post" action="">
            ' . wp_nonce_field('fds_save_canada_html', '_wpnonce', true, false) . '
            <h2>Add Masjid Posts Bottom Tagline Here</h2>
            <textarea name="fds_canada_add_ask_muslim_links_html" rows="10" cols="50" style="width:60%;">' . esc_textarea($fds_canada_add_ask_muslim_links_html) . '</textarea>
            <p style="width:60%"><em>Add custom HTML content to display as a tagline at the bottom of Masjid posts pages. Use this field for links, text, or other relevant information.</em></p>
            <input type="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>';
}


/*
*
*   Yoast Breadcrumbs Structure Customization Code 
*
**/


/**
 * Remove "Masjid Posts" from breadcrumbs
 */
function fds_masjid_canada_remove_yoast_breadcrumb_link($link_output , $link)
{
    $text_to_remove = 'Masjid Posts';

    if($link['text'] == $text_to_remove) {
      $link_output = '';
    }

    return $link_output;
}


/**
 * Remove duplicate "State" and "City" from Yoast breadcrumbs.
 */
function fds_masjid_canada_remove_duplicate_breadcrumbs($links) {
    $seen = array(); // To keep track of seen breadcrumb texts
    $new_links = array(); // To store filtered breadcrumbs

    foreach ($links as $link) {
        // Check if the current link text is already in the seen array
        if (!in_array($link['text'], $seen)) {
            $new_links[] = $link; // Add it to the new links array
            $seen[] = $link['text']; // Mark this text as seen
        }
    }

    return $new_links;
}


/**
 * Setting breadcrumbs for single post page in yoast SEO
 */ 

function fds_masjid_canada_custom_breadcrumbs($links) {
    if (is_singular('masjid_canada_cpt')) {
        global $post;

        // Get terms in 'category' taxonomy
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

            // Ensure we don't add duplicate breadcrumbs
            $breadcrumb_texts = array_column($links, 'text');

            if ($parent_term && !in_array($parent_term->name, $breadcrumb_texts)) {
                $parent_breadcrumb = array(
                    'url' => get_term_link($parent_term->term_id, 'masjid_canada_location'),
                    'text' => $parent_term->name,
                    'id' => $parent_term->term_id,
                );
                array_splice($links, 1, 0, array($parent_breadcrumb));
            }

            if ($child_term && !in_array($child_term->name, $breadcrumb_texts)) {
                $child_breadcrumb = array(
                    'url' => get_term_link($child_term->term_id, 'masjid_canada_location'),
                    'text' => $child_term->name,
                    'id' => $child_term->term_id,
                );
                array_splice($links, 2, 0, array($child_breadcrumb));
            }
        }
    }

    return $links;
}


/*
*
*   Functions to Convert US State name into two letter 
*
**/

// Convert State name to two letter abbr.
function fdsMasjidCanadaConvertState($name) {
      $states = array(
    // 50 USA States
    array('name'=>'Alabama', 'abbr'=>'AL'),     
    array('name'=>'Alaska', 'abbr'=>'AK'),      
    array('name'=>'Arizona', 'abbr'=>'AZ'),     
    array('name'=>'Arkansas', 'abbr'=>'AR'),    
    array('name'=>'California', 'abbr'=>'CA'),  
    array('name'=>'Colorado', 'abbr'=>'CO'),    
    array('name'=>'Connecticut', 'abbr'=>'CT'), 
    array('name'=>'Delaware', 'abbr'=>'DE'),    
    array('name'=>'Florida', 'abbr'=>'FL'),     
    array('name'=>'Georgia', 'abbr'=>'GA'),     
    array('name'=>'Hawaii', 'abbr'=>'HI'),      
    array('name'=>'Idaho', 'abbr'=>'ID'),      
    array('name'=>'Illinois', 'abbr'=>'IL'),    
    array('name'=>'Indiana', 'abbr'=>'IN'),    
    array('name'=>'Iowa', 'abbr'=>'IA'),        
    array('name'=>'Kansas', 'abbr'=>'KS'),      
    array('name'=>'Kentucky', 'abbr'=>'KY'),    
    array('name'=>'Louisiana', 'abbr'=>'LA'),   
    array('name'=>'Maine', 'abbr'=>'ME'),       
    array('name'=>'Maryland', 'abbr'=>'MD'),    
    array('name'=>'Massachusetts', 'abbr'=>'MA'),   
    array('name'=>'Michigan', 'abbr'=>'MI'),        
    array('name'=>'Minnesota', 'abbr'=>'MN'),      
    array('name'=>'Mississippi', 'abbr'=>'MS'),     
    array('name'=>'Missouri', 'abbr'=>'MO'),       
    array('name'=>'Montana', 'abbr'=>'MT'),        
    array('name'=>'Nebraska', 'abbr'=>'NE'),        
    array('name'=>'Nevada', 'abbr'=>'NV'),          
    array('name'=>'New Hampshire', 'abbr'=>'NH'),   
    array('name'=>'New Jersey', 'abbr'=>'NJ'),      
    array('name'=>'New Mexico', 'abbr'=>'NM'),      
    array('name'=>'New York', 'abbr'=>'NY'),        
    array('name'=>'North Carolina', 'abbr'=>'NC'),  
    array('name'=>'North Dakota', 'abbr'=>'ND'),    
    array('name'=>'Ohio', 'abbr'=>'OH'),            
    array('name'=>'Oklahoma', 'abbr'=>'OK'),        
    array('name'=>'Oregon', 'abbr'=>'OR'),          
    array('name'=>'Pennsylvania', 'abbr'=>'PA'),     
    array('name'=>'Rhode Island', 'abbr'=>'RI'),    
    array('name'=>'South Carolina', 'abbr'=>'SC'),  
    array('name'=>'South Dakota', 'abbr'=>'SD'),     
    array('name'=>'Tennessee', 'abbr'=>'TN'),       
    array('name'=>'Texas', 'abbr'=>'TX'),          
    array('name'=>'Utah', 'abbr'=>'UT'),           
    array('name'=>'Vermont', 'abbr'=>'VT'),         
    array('name'=>'Virginia', 'abbr'=>'VA'),        
    array('name'=>'Washington', 'abbr'=>'WA'),      
    array('name'=>'West Virginia', 'abbr'=>'WV'),   
    array('name'=>'Wisconsin', 'abbr'=>'WI'),       
    array('name'=>'Wyoming', 'abbr'=>'WY'),            
    array('name'=>'Washington, D.C. (Capital)', 'abbr'=>'DC'),
    // 5 US Territories
    array('name'=>'American Samoa', 'abbr'=>'AS'),
    array('name'=>'Guam', 'abbr'=>'GU'), 
    array('name'=>'Northern Mariana Islands', 'abbr'=>'MP'),
    array('name'=>'Puerto Rico', 'abbr'=>'PR'),
    array('name'=>'U.S. Virgin Islands', 'abbr'=>'VI'),
    // 10 Canada States
    array('name'=>'Alberta', 'abbr'=>'AB'),
    array('name'=>'British Columbia', 'abbr'=>'BC'),
    array('name'=>'Manitoba', 'abbr'=>'MB'),
    array('name'=>'New Brunswick', 'abbr'=>'NB'),
    array('name'=>'Newfoundland and Labrador', 'abbr'=>'NL'),
    array('name'=>'Nova Scotia', 'abbr'=>'NS'),
    array('name'=>'Ontario', 'abbr'=>'ON'),
    array('name'=>'Prince Edward Island', 'abbr'=>'PE'),
    array('name'=>'Quebec', 'abbr'=>'QC'),
    array('name'=>'Saskatchewan', 'abbr'=>'SK'),
    // 3 Canada Territories
    array('name'=>'Yukon', 'abbr'=>'YT'),
    array('name'=>'Nunavut', 'abbr'=>'NU'),
    array('name'=>'Northwest Territories', 'abbr'=>'NT'),
   );

   $return = false;   
   $strlen = strlen($name);

   foreach ($states as $state) :
      if ($strlen < 2) {
         return false;
      } else if ($strlen == 2) {
         if (strtolower($state['abbr']) == strtolower($name)) {
            $return = $state['name'];
            break;
         }   
      } else {
         if (strtolower($state['name']) == strtolower($name)) {
            $return = strtoupper($state['abbr']);
            break;
         }         
      }
   endforeach;
   
   return $return;
} // end function convertState