<?php
/**
 * Template Name: Masjid Single Page Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>
	<style type="text/css">
		.wp-block-social-links.has-large-icon-size{
			font-size: 24px;
		}
	</style>
	<div id="primary" class="content-area masjid-single-page">
		<main id="main" class="site-main" role="main">
			 <?php
            if(class_exists('WPSEO_Options')){
                 /* Yoast is active */
                 if(WPSEO_Options::get('breadcrumbs-enable', false)){
                            /* Yoast breadcrumbs is active */
                            ?>
                            <div class="fds-breadcrumbs">
                                <h3 style="margin-top: 0em;">
                                    <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>	
                                </h3>
                            </div>
                   <?php    
                   }
             }
             
        /* Display the list of masajids on single page */  
		if (have_posts()) :
			        while (have_posts()) : the_post();

			        	echo '<header class="entry-header masjid-title">
						<h1 class="entry-title">' . get_the_title() . '</h1>';

			            // Initialize an array to store the field values
						$field_values = array();

						// Define the custom fields
						$fields = array(
						    'city' => 'City',
						    'state' => 'State',
						    'image_url' => 'Image URL',
						    'address' => 'Address',
						    'postcode' => 'Postcode',
						    'phone' => 'Phone',
						    'email' => 'Email',
						    'whatsapp' => 'WhatsApp',
						    'facebook' => 'Facebook',
						    'instagram' => 'Instagram',
						    'youtube' => 'YouTube',
						    'linkedin' => 'LinkedIn',
						    'website' => 'Website',
						    'google_map' => 'Google Map',
						    'ask_muslims_link' => 'Ask Muslims Link',
						);

				
						foreach ($fields as $key => $label) {
						    $value = get_post_meta(get_the_ID(), $key, true);
						    $field_values[$key] = $value;
						}
	
						$city = $field_values['city'];
						$state = $field_values['state'];
						$image_url = $field_values['image_url'];
						$address = $field_values['address'];
						$postcode = $field_values['postcode'];
						$phone = $field_values['phone'];
						$email = $field_values['email'];
						$whatsapp = $field_values['whatsapp'];
						$facebook = $field_values['facebook'];
						$instagram = $field_values['instagram'];
						$youtube = $field_values['youtube'];
						$linkedin = $field_values['linkedin'];
						$website = $field_values['website'];
						$google_map = $field_values['google_map'];
						$ask_muslims_link = $field_values['ask_muslims_link'];

						if(!empty($image_url)){
						echo '<div class="entry-thumbnail masjid-feature-image">
							<img width="714" height="420" src="'.$image_url.'" class="attachment-hive-single-image size-hive-single-image wp-post-image" alt="" decoding="async" fetchpriority="high" sizes="(max-width: 899px) 100vw, (max-width: 999px) 65vw, (max-width: 1519px) 57vw, 860px">	</div>';
							}
						echo '</header>

						<h3 class="wp-block-heading masjid-single-subheading" style="margin-top: 0em;"><strong>Masjid in '.$city.', '.$state.'</strong></h3>';
						if (!empty(get_the_content())) {
							echo '<p class="masjid-about-info">' . get_the_content() . '</p>';
						}

						echo '<p masjid-contact-info>Address: '.$address.'<br>Website: <a href="'.$website.'" target="_blank" rel="noopener">'.$website.'</a><br>Phone: <a href="tel:'.$phone.'">'.$phone.'</a><br>Email: <a href="mailto:'.$email.'">'.$email.'</a></p>

						<div class="wp-block-group align-center masjid-social-links"><div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
								<ul class="wp-block-social-links has-large-icon-size is-layout-flex wp-block-social-links-is-layout-flex">';

							if(!empty($whatsapp)){
								
								echo '<li class="wp-social-link wp-social-link-whatsapp  wp-block-social-link"><a href="https://api.whatsapp.com/send?phone='.$whatsapp.'" target="_blank" class="wp-block-social-link-anchor" ><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M 12.011719 2 C 6.5057187 2 2.0234844 6.478375 2.0214844 11.984375 C 2.0204844 13.744375 2.4814687 15.462563 3.3554688 16.976562 L 2 22 L 7.2324219 20.763672 C 8.6914219 21.559672 10.333859 21.977516 12.005859 21.978516 L 12.009766 21.978516 C 17.514766 21.978516 21.995047 17.499141 21.998047 11.994141 C 22.000047 9.3251406 20.962172 6.8157344 19.076172 4.9277344 C 17.190172 3.0407344 14.683719 2.001 12.011719 2 z M 12.009766 4 C 14.145766 4.001 16.153109 4.8337969 17.662109 6.3417969 C 19.171109 7.8517969 20.000047 9.8581875 19.998047 11.992188 C 19.996047 16.396187 16.413812 19.978516 12.007812 19.978516 C 10.674812 19.977516 9.3544062 19.642812 8.1914062 19.007812 L 7.5175781 18.640625 L 6.7734375 18.816406 L 4.8046875 19.28125 L 5.2851562 17.496094 L 5.5019531 16.695312 L 5.0878906 15.976562 C 4.3898906 14.768562 4.0204844 13.387375 4.0214844 11.984375 C 4.0234844 7.582375 7.6067656 4 12.009766 4 z M 8.4765625 7.375 C 8.3095625 7.375 8.0395469 7.4375 7.8105469 7.6875 C 7.5815469 7.9365 6.9355469 8.5395781 6.9355469 9.7675781 C 6.9355469 10.995578 7.8300781 12.182609 7.9550781 12.349609 C 8.0790781 12.515609 9.68175 15.115234 12.21875 16.115234 C 14.32675 16.946234 14.754891 16.782234 15.212891 16.740234 C 15.670891 16.699234 16.690438 16.137687 16.898438 15.554688 C 17.106437 14.971687 17.106922 14.470187 17.044922 14.367188 C 16.982922 14.263188 16.816406 14.201172 16.566406 14.076172 C 16.317406 13.951172 15.090328 13.348625 14.861328 13.265625 C 14.632328 13.182625 14.464828 13.140625 14.298828 13.390625 C 14.132828 13.640625 13.655766 14.201187 13.509766 14.367188 C 13.363766 14.534188 13.21875 14.556641 12.96875 14.431641 C 12.71875 14.305641 11.914938 14.041406 10.960938 13.191406 C 10.218937 12.530406 9.7182656 11.714844 9.5722656 11.464844 C 9.4272656 11.215844 9.5585938 11.079078 9.6835938 10.955078 C 9.7955938 10.843078 9.9316406 10.663578 10.056641 10.517578 C 10.180641 10.371578 10.223641 10.267562 10.306641 10.101562 C 10.389641 9.9355625 10.347156 9.7890625 10.285156 9.6640625 C 10.223156 9.5390625 9.737625 8.3065 9.515625 7.8125 C 9.328625 7.3975 9.131125 7.3878594 8.953125 7.3808594 C 8.808125 7.3748594 8.6425625 7.375 8.4765625 7.375 z"></path></svg><span class="wp-block-social-link-label screen-reader-text">WhatsApp</span></a></li>';

							}		

							if(!empty($facebook)){
								
								echo '<li class="wp-social-link wp-social-link-facebook  wp-block-social-link"><a href="'.$facebook.'" class="wp-block-social-link-anchor" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg><span class="wp-block-social-link-label screen-reader-text">Facebook</span></a></li>';
							}

							if(!empty($youtube)){

								echo'<li class="wp-social-link wp-social-link-youtube  wp-block-social-link"><a href="'.$youtube.'" class="wp-block-social-link-anchor" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M21.8,8.001c0,0-0.195-1.378-0.795-1.985c-0.76-0.797-1.613-0.801-2.004-0.847c-2.799-0.202-6.997-0.202-6.997-0.202 h-0.009c0,0-4.198,0-6.997,0.202C4.608,5.216,3.756,5.22,2.995,6.016C2.395,6.623,2.2,8.001,2.2,8.001S2,9.62,2,11.238v1.517 c0,1.618,0.2,3.237,0.2,3.237s0.195,1.378,0.795,1.985c0.761,0.797,1.76,0.771,2.205,0.855c1.6,0.153,6.8,0.201,6.8,0.201 s4.203-0.006,7.001-0.209c0.391-0.047,1.243-0.051,2.004-0.847c0.6-0.607,0.795-1.985,0.795-1.985s0.2-1.618,0.2-3.237v-1.517 C22,9.62,21.8,8.001,21.8,8.001z M9.935,14.594l-0.001-5.62l5.404,2.82L9.935,14.594z"></path></svg><span class="wp-block-social-link-label screen-reader-text">YouTube</span></a></li>';
							}	

							if(!empty($linkedin)){

								echo'<li class="wp-social-link wp-social-link-linkedin  wp-block-social-link"><a href="'.$linkedin.'" class="wp-block-social-link-anchor" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z"></path></svg><span class="wp-block-social-link-label screen-reader-text">LinkedIn</span></a></li>';
							}
							
							if(!empty($instagram)){

								echo'<li class="wp-social-link wp-social-link-instagram  wp-block-social-link"><a href="'.$instagram.'" class="wp-block-social-link-anchor" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12,4.622c2.403,0,2.688,0.009,3.637,0.052c0.877,0.04,1.354,0.187,1.671,0.31c0.42,0.163,0.72,0.358,1.035,0.673 c0.315,0.315,0.51,0.615,0.673,1.035c0.123,0.317,0.27,0.794,0.31,1.671c0.043,0.949,0.052,1.234,0.052,3.637 s-0.009,2.688-0.052,3.637c-0.04,0.877-0.187,1.354-0.31,1.671c-0.163,0.42-0.358,0.72-0.673,1.035 c-0.315,0.315-0.615,0.51-1.035,0.673c-0.317,0.123-0.794,0.27-1.671,0.31c-0.949,0.043-1.233,0.052-3.637,0.052 s-2.688-0.009-3.637-0.052c-0.877-0.04-1.354-0.187-1.671-0.31c-0.42-0.163-0.72-0.358-1.035-0.673 c-0.315-0.315-0.51-0.615-0.673-1.035c-0.123-0.317-0.27-0.794-0.31-1.671C4.631,14.688,4.622,14.403,4.622,12 s0.009-2.688,0.052-3.637c0.04-0.877,0.187-1.354,0.31-1.671c0.163-0.42,0.358-0.72,0.673-1.035 c0.315-0.315,0.615-0.51,1.035-0.673c0.317-0.123,0.794-0.27,1.671-0.31C9.312,4.631,9.597,4.622,12,4.622 M12,3 C9.556,3,9.249,3.01,8.289,3.054C7.331,3.098,6.677,3.25,6.105,3.472C5.513,3.702,5.011,4.01,4.511,4.511 c-0.5,0.5-0.808,1.002-1.038,1.594C3.25,6.677,3.098,7.331,3.054,8.289C3.01,9.249,3,9.556,3,12c0,2.444,0.01,2.751,0.054,3.711 c0.044,0.958,0.196,1.612,0.418,2.185c0.23,0.592,0.538,1.094,1.038,1.594c0.5,0.5,1.002,0.808,1.594,1.038 c0.572,0.222,1.227,0.375,2.185,0.418C9.249,20.99,9.556,21,12,21s2.751-0.01,3.711-0.054c0.958-0.044,1.612-0.196,2.185-0.418 c0.592-0.23,1.094-0.538,1.594-1.038c0.5-0.5,0.808-1.002,1.038-1.594c0.222-0.572,0.375-1.227,0.418-2.185 C20.99,14.751,21,14.444,21,12s-0.01-2.751-0.054-3.711c-0.044-0.958-0.196-1.612-0.418-2.185c-0.23-0.592-0.538-1.094-1.038-1.594 c-0.5-0.5-1.002-0.808-1.594-1.038c-0.572-0.222-1.227-0.375-2.185-0.418C14.751,3.01,14.444,3,12,3L12,3z M12,7.378 c-2.552,0-4.622,2.069-4.622,4.622S9.448,16.622,12,16.622s4.622-2.069,4.622-4.622S14.552,7.378,12,7.378z M12,15 c-1.657,0-3-1.343-3-3s1.343-3,3-3s3,1.343,3,3S13.657,15,12,15z M16.804,6.116c-0.596,0-1.08,0.484-1.08,1.08 s0.484,1.08,1.08,1.08c0.596,0,1.08-0.484,1.08-1.08S17.401,6.116,16.804,6.116z"></path></svg><span class="wp-block-social-link-label screen-reader-text">Instagram</span></a></li>';
							}
								
						echo'</ul>
								</div></div>
						<div style="margin:20px 0px 5px 0px;"></div>';
					
					if (!empty($google_map)) {
							echo do_shortcode( $google_map ); 
                            //echo '<iframe src="'.$google_map.'" class="masjid-google-map" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
						}	
                        
                    $fds_canada_add_ask_muslim_links_html = get_option('fds_canada_add_ask_muslim_links_html', '');
					if (!empty($fds_canada_add_ask_muslim_links_html)) {
			    		echo '<div class="ask-muslim-text" style="margin:20px 0px 5px 0px;"><hr>' . wp_kses_post($fds_canada_add_ask_muslim_links_html) . '</div>';
					} else{
						echo'<div class="ask-muslim-text" style="margin:20px 0px 5px 0px;"><hr><p class="ask-muslim-text"><strong>What are some questions you would like to <a href="https://askmuslims.com/" target="_blank" rel="noopener">ask Muslims</a> about Islam?</strong></p></div>';
					}  
							
			        endwhile;
			    else :
			        echo '<p class="masjid-not-found">No Masjid found.</p>';
			    endif;
			    ?>
			    
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();