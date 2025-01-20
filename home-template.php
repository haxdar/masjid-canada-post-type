<?php
/**
 * Template Name: Masjid Home Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>
<style>
    .masjid-states-table.double-border {
        border-bottom: 4px double #d7d7d7;
    }
</style>
<div id="primary" class="content-area masjid-home-page">
    <main id="main" class="site-main" role="main">
        <header class="entry-header masjid-home-title">
            <h1 class="entry-title">
                <?php
                if (class_exists('WPSEO_Options') && WPSEO_Options::get('breadcrumbs-enable', false)) {
                    echo do_shortcode('[wpseo_breadcrumb]');
                } else {
                    echo esc_html(the_title());
                }
                ?>
            </h1>
        </header>

        <div class="masjid-home-content">
            <h2>Masjid America</h2>
            <p>Masjid (Arabic: مـسـجـد‎) also called mosque in English, is defined as any place that Muslims pray facing Makkah, not necessarily a building. By that meaning, there were Masajid (plural of Masjid) in the United States by 1731 or earlier. Muslim Job ben Solomon (1701–1773), an African-American kidnapped into slavery, was documented by his slave narrative memoir to have prayed in the forest of Kent Island, Maryland, where he was brought during 1731–33. Now, we have over 2,000 Masajid (Islamic centers) in the United States:</p>

            <?php
            // Define taxonomy and arguments
            $taxonomy = 'masjid_location';
            $args = array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
                'parent'     => 0, 
            );

            $terms = get_terms($args);

            // Grouping states and territories
            $states_data = array(
                'U.S. Islamic Centers:' => ['al', 'ak', 'az', 'ar', 'ca', 'co', 'ct', 'de', 'fl', 'ga', 'hi', 'id', 'il', 'in', 'ia', 'ks', 'ky', 'la', 'me', 'md', 'ma', 'mi', 'mn', 'ms', 'mo', 'mt', 'ne', 'nv', 'nh', 'nj', 'nm', 'ny', 'nc', 'nd', 'oh', 'ok', 'or', 'pa', 'ri', 'sc', 'sd', 'tn', 'tx', 'ut', 'vt', 'va', 'wa', 'wv', 'wi', 'wy'],
                'Other U.S. Regions:' => ['as', 'gu', 'mp', 'pr', 'vi','dc'],
            );

            // Helper function to filter terms by valid names
            function filter_terms_by_slug($terms, $valid_slugs) {
                return array_filter($terms, function ($term) use ($valid_slugs) {
                    return in_array($term->slug, $valid_slugs);
                });
            }

            // Function to display terms in a table with optional double border
            function display_terms_table($terms, $taxonomy, $double_border = false) {
                if (empty($terms)) return;

                $table_class = $double_border ? 'masjid-states-table double-border' : 'masjid-states-table';
                echo "<table class='{$table_class}'>";
                $count = 0;
                foreach ($terms as $term) {
                    if ($count % 2 == 0) echo '<tr class="catsli">';
                    ?>
                    <td>
                        <a href="<?php echo esc_url(get_term_link($term->slug, $taxonomy)); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a> (<?php echo esc_html($term->count); ?>)
                    </td>
                    <?php
                    $count++;
                    if ($count % 2 == 0) echo '</tr>';
                }
                if ($count % 2 != 0) echo '<td></td></tr>';
                echo '</table>';
            }

            // Loop through each group and display terms only if available
            foreach ($states_data as $heading => $valid_slugs) {
                $filtered_terms = filter_terms_by_slug($terms, $valid_slugs);
                if (!empty($filtered_terms)) {
                    // Check if the heading is "Other U.S. Regions"
                    $double_border = ($heading === 'U.S. Islamic Centers:');
                    if ($heading !== 'Other U.S. Regions:') {
                        echo "<h3>{$heading}</h3>";
                    }
                    display_terms_table($filtered_terms, $taxonomy, $double_border);
                }
            }
            ?>


            <?php // Canada Country States

            // Define taxonomy and arguments
            $canada_taxonomy = 'masjid_canada_location';
            $canada_args = array(
                'taxonomy'   => $canada_taxonomy,
                'hide_empty' => true,
                'parent'     => 0, 
            );

            $canada_terms = get_terms($canada_args);

            // Grouping states and territories
            $canada_states_data = array(
                'Canadian Islamic Centers:' => ['ab', 'bc', 'mb', 'nb', 'nl', 'ns', 'on', 'pe', 'qc', 'sk'],
                'Other Canadian Regions:' => ['yt', 'nu', 'nt']
            );

            // Helper function to filter terms by valid names
            function filter_canada_terms_by_slug($canada_terms, $canada_valid_slugs) {
                return array_filter($canada_terms, function ($canada_term) use ($canada_valid_slugs) {
                    return in_array($canada_term->slug, $canada_valid_slugs);
                });
            }

            // Loop through each group and display terms only if available
            foreach ($canada_states_data as $canada_heading => $canada_valid_slugs) {
                $canada_filtered_terms = filter_canada_terms_by_slug($canada_terms, $canada_valid_slugs);
                if (!empty($canada_filtered_terms)) {
                    $double_border = ($canada_heading === 'Canadian Islamic Centers:');
                    if ($canada_heading !== 'Other Canadian Regions:') {
                        echo "<h3>{$canada_heading}</h3>";
                    }
                    display_terms_table($canada_filtered_terms, $canada_taxonomy, $double_border);
                }
            }

            ?>


            <p><strong>WHOEVER BUILDS A MASJID, EVEN TO THE EXTENT OF A BRICK OR LESSER, ALLAH WILL BUILD FOR HIM A HOUSE IN PARADISE. -MUHAMMAD (PBUH)</strong></p>
        </div>
    </main>
</div>

<?php
get_sidebar();
get_footer();