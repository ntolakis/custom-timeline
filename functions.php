/**
 * Register Custom Post Type for Timeline
 *
 * This function registers a custom post type called 'timeline' with various settings and labels.
 * The custom post type will be used to create and manage timeline posts.
 *
 * @return void
 */
function register_timeline_post_type() {
	$labels = array(
		'name'                  => _x( 'Timelines', 'Post Type General Name', 'generatepress_child' ),
		'singular_name'         => _x( 'Timeline', 'Post Type Singular Name', 'generatepress_child' ),
		'menu_name'             => __( 'Timelines', 'generatepress_child' ),
		'name_admin_bar'        => __( 'Timeline', 'generatepress_child' ),
		'archives'              => __( 'Timeline Archives', 'generatepress_child' ),
		'attributes'            => __( 'Timeline Attributes', 'generatepress_child' ),
		'parent_item_colon'     => __( 'Parent Timeline:', 'generatepress_child' ),
		'all_items'             => __( 'All Timelines', 'generatepress_child' ),
		'add_new_item'          => __( 'Add New Timeline', 'generatepress_child' ),
		'add_new'               => __( 'Add New', 'generatepress_child' ),
		'new_item'              => __( 'New Timeline', 'generatepress_child' ),
		'edit_item'             => __( 'Edit Timeline', 'generatepress_child' ),
		'update_item'           => __( 'Update Timeline', 'generatepress_child' ),
		'view_item'             => __( 'View Timeline', 'generatepress_child' ),
		'view_items'            => __( 'View Timelines', 'generatepress_child' ),
		'search_items'          => __( 'Search Timeline', 'generatepress_child' ),
		'not_found'             => __( 'Not found', 'generatepress_child' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'generatepress_child' ),
		'featured_image'        => __( 'Featured Image', 'generatepress_child' ),
		'set_featured_image'    => __( 'Set featured image', 'generatepress_child' ),
		'remove_featured_image' => __( 'Remove featured image', 'generatepress_child' ),
		'use_featured_image'    => __( 'Use as featured image', 'generatepress_child' ),
		'insert_into_item'      => __( 'Insert into timeline', 'generatepress_child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this timeline', 'generatepress_child' ),
		'items_list'            => __( 'Timelines list', 'generatepress_child' ),
		'items_list_navigation' => __( 'Timelines list navigation', 'generatepress_child' ),
		'filter_items_list'     => __( 'Filter timelines list', 'generatepress_child' ),
	);
	$args = array(
		'label'                 => __( 'Timeline', 'generatepress_child' ),
		'description'           => __( 'Timeline Description', 'generatepress_child' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes'  ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-calendar',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'timeline', $args );
 }
 add_action( 'init', 'register_timeline_post_type', 0 );

 // Add order column to admin for timeline
function timeline_custom_columns($columns) {
    $columns['menu_order'] = __('Order', 'generatepress_child');
    return $columns;
}
add_filter('manage_timeline_posts_columns', 'timeline_custom_columns');

function timeline_custom_column_content($column, $post_id) {
    if ($column == 'menu_order') {
        echo get_post_field('menu_order', $post_id);
    }
}
add_action('manage_timeline_posts_custom_column', 'timeline_custom_column_content', 10, 2);

/**
  * Shortcode to Display Timeline Carousel
  *
  * This function creates a shortcode [timeline_carousel] to display a carousel of timeline posts.
  * It queries the 'timeline' custom post type and generates the HTML structure for the carousel.
  *
  * @return string The HTML output for the timeline carousel.
  */
function display_timeline_carousel() {
    $args = array(
        'post_type' => 'timeline',
        'posts_per_page' => -1,
        'order' => 'ASC',
		'orderby' => 'menu_order',
    );
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $output = '<div class="timeline-carousel">';

        while ( $query->have_posts() ) {
            $query->the_post();

            // Create the HTML structure for each timeline post
            $output .= '<div class="timeline-post">';
			$output .= '<div class="timeline-post-inner-wrapper">';
            // Title with the bullet and vertical line aligned underneath
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<div class="timeline-line"><span class="bullet"></span><span class="vertical-line"></span></div>';

            // Timeline content
            $output .= '<div class="timeline-content">' . get_the_content() . '</div>';

			$output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';

        wp_reset_postdata();
    } else {
        $output = '<p>No timeline posts found.</p>';
    }

    return $output;
}
add_shortcode( 'timeline_carousel', 'display_timeline_carousel' );

