<?php
/**
 * Template Name: Masjid Category Template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<style type="text/css">
    .content-area {
        display: flex;
        flex-wrap: wrap;
    }

    @media only screen and (min-width: 768px) {
      .content-area {
        flex-wrap: nowrap;
      }
        div#secondary {
        width: 18.5em;
    }
    }

    #primary {
        flex: 1 1 70%; 
        padding: 0 120px; 
    }

    @media only screen and (max-width: 600px) {
        .site-main{
            margin-bottom: 40px;
        }
        #primary {
            flex: 1 1 70%; 
            padding: 0 20px; 
        }
        aside#masjid-secondary-sidbar {
            padding-left: 0px!important;
            border: none!important;
        }    
    }

    #masjid-secondary-sidbar {
        flex: 1 1 30%; 
        padding: 0px; 
    }

    .widget-area {
        background-color: #f9f9f9; 
        border: 1px solid #ddd; 
        padding: 20px; 
    }
    
    aside#masjid-secondary-sidbar {
        padding-right: 0px;
        padding-left: 3.95em;
        border-left: 1px solid  #c5c5c5;;
        border-right: 0px;
        border-bottom: 0px;
        border-top: 0px;
        background-color: white;
        padding-top: 10px;
    }

    div#secondary {
        border: none;
    }
    
    
</style>

<div class="content-area masjid-cities-page">
    <div id="primary" class="content-area">
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
             ?> 
            <article class="masjid-cities-content">
                <?php
                $term = get_queried_object(); // Get the current taxonomy term
                global $wp_query;
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                //echo '<h2 class="state-city-title">' . esc_html($term->name) . ' ('.esc_html(strtoupper($term->slug)).') Islamic Centers</h2>';
                    
            if ($term->parent) {
               
        $parent_term = get_term($term->parent, 'masjid_canada_location');
        // Display parent term name and slug
                echo '<h2 class="state-city-title">' . esc_html($parent_term->name) . ' (' . esc_html(strtoupper($parent_term->slug)) . ') Islamic Centers</h2>';
            } else {
                echo '<h2 class="state-city-title">' . esc_html($term->name) . ' (' . esc_html(strtoupper($term->slug)) . ') Islamic Centers</h2>';
            }
                

                // Fetch child categories (cities in this case)
                $child_categories = get_terms(array(
                    'taxonomy' => 'masjid_canada_location',
                    'child_of' => $term->term_id,
                    'hide_empty' => true,
                ));

                if ($child_categories) {
                    echo '<h3 class="cities-title">Locations in '.esc_html($term->name).':</h3>';
                    echo '<table class="masjid-cities-table">'; 
                    $count = 0;
                    foreach ($child_categories as $child_category) {
                        if ($count % 2 == 0) {
                            echo '<tr class="catsli">';
                        }
                        ?>
                        <td>
                            <a href="<?php echo esc_url(get_term_link($child_category)); ?>">
                                <?php echo esc_html($child_category->name); ?>
                            </a> 
                            (<?php echo esc_html($child_category->count); ?>)
                        </td>
                        <?php
                        $count++;
                        if ($count % 2 == 0) {
                            echo '</tr>';
                        }
                    }
                    if ($count % 2 != 0) {
                        echo '<td></td></tr>';
                    }
                    echo '</table>';
                }


                // Query posts in the selected category (masajids under this location)
                $cat_query = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_type' => 'masjid_canada_cpt',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'masjid_canada_location',
                            'field'    => 'slug',
                            'terms'    => $term->slug,
                        ),
                    ),
                    'paged' => $paged,
                ));

                if ($cat_query->have_posts()) :
                    echo '<h3>Masajids in '.esc_html($term->name).':</h3>';
                    echo '<div style="padding-left:40px;"><ol>'; 
                    while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile;
                    echo '</ol><div>'; 
                    wp_reset_postdata();
                else :
                    echo '<p>No Masajids found.</p>';
                endif;
                ?>
            </article>
            <?php 
                 $fds_canada_add_ask_muslim_links_html = get_option('fds_canada_add_ask_muslim_links_html', '');
                    if (!empty($fds_canada_add_ask_muslim_links_html)) {
                        echo '<div class="ask-muslim-text" style="margin:20px 0px 5px 0px;"><hr>' . wp_kses_post($fds_canada_add_ask_muslim_links_html) . '</div>';
                    } else{
                        echo'<div class="ask-muslim-text" style="margin:20px 0px 5px 0px;"><hr><p class="ask-muslim-text"><strong>What are some questions you would like to <a href="https://askmuslims.com/" target="_blank" rel="noopener">ask Muslims</a> about Islam?</strong></p></div>';
                    }     
            ?>
        </main>
    </div>

    <aside id="masjid-secondary-sidbar" class="widget-area">
        <?php get_sidebar(); ?>
    </aside>
</div>

<?php
get_footer();