<?php
/**
 * Theme functions and definitions.
 *
 * @package Solutions
 * @author  QE <luiselcue@gmail.com>
 * @since   1.0.0
 */

function solutions_scripts() {
	wp_enqueue_style('solutions-style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'solutions_scripts', 20);

/*DEBUG MODE*/
define('WP_DEBUG', true);

/*Polylang functions*/
/*
function register_string_from_array($array) {
  if ($array) {
    foreach($array as $name => $string) {
      if (gettype($name) == 'string' && gettype($string) == 'string') {
        pll_register_string($name, $string, 'solutions');
        
      }
    }
  }
}
*/

$ProjectLabels = array(
  'label'        => 'projects',
  'description'     => 'Project news and reviews',
  'menu_icon'      => 'dashicons-admin-site-alt3',
  'menu_position'     => 5,

  'name'        => 'Projects',
  'singular_name'    => 'Project',
  'menu_name'      => 'Projects',
  'parent_item_colon'  => 'Parent Project',
  'all_items'      => 'All Projects',
  'view_item'      => 'View Project',
  'add_new_item'    => 'Add New Project',
  'add_new'       => 'Add New',
  'edit_item'      => 'Edit Project',
  'update_item'     => 'Update Project',
  'search_items'    => 'Search Project',
  'not_found'      => 'Not found',
  'not_found_in_trash' => 'Not found in Trash',
);

$ActivityLabels = array(
  'label'             => 'activities',
  'description'       => 'Activity news and reviews',
  'menu_icon'         => 'dashicons-art',
  'menu_position'     => 6,

  'name'              => 'Activities',
  'singular_name'     => 'Activity',
  'menu_name'         => 'Activities',
  'parent_item_colon' => 'Parent Activity',
  'all_items'      => 'All Activities',
  'view_item'      => 'View Activity',
  'add_new_item'    => 'Add New Activity',
  'add_new'       => 'Add New',
  'edit_item'      => 'Edit Activity',
  'update_item'     => 'Update Activity',
  'search_items'    => 'Search Activity',
  'not_found'      => 'Not found',
  'not_found_in_trash' => 'Not found in Trash',
);

//Polylang strings register
/*
register_string_from_array($ProjectLabels);
register_string_from_array($ActivityLabels);
*/

/*Add new custom type activity*/
function add_custom_post_type($labels = false) {

  //Polylang test
  /*
  foreach($labels as $name => $value) {
    $labels[$name] = pll__($value);
  }
  */

  $args = array(
    'label'        => $labels['label'],
    'description'     => $labels['description'],
    'labels'       => $labels,
    'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
    'hierarchical'    => false,
    'public'       => true,
    'show_ui'       => true,
    'show_in_menu'    => true,
    'show_in_nav_menus'  => true,
    'show_in_admin_bar'  => true,
    'menu_icon'      => $labels['menu_icon'],
    'menu_position'    => $labels['menu_position'],
    'can_export'     => true,
    'has_archive'     => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'capability_type'   => 'post',
    'show_in_rest'    => true,
  );

  register_post_type( $labels['label'], $args );
}
/*Add new custom types*/
add_action( 'init', function () use ($ProjectLabels) { add_custom_post_type($ProjectLabels); }, 0 );
add_action( 'init', function () use ($ActivityLabels) { add_custom_post_type($ActivityLabels); }, 0 );

/*Create new custom taxonomy*/ 
function custom_hierarchical_taxonomy() {
 $labels = array(
  'name' => 'Programs',
  'singular_name' => 'Program',
  'search_items' => 'Search Programs',
  'all_items' => 'All Programs',
  'parent_item' => 'Parent Program',
  'parent_item_colon' => 'Parent Program:',
  'edit_item' => 'Edit Program', 
  'update_item' => 'Update Program',
  'add_new_item' => 'Add New Program',
  'new_item_name' => 'New Program Name',
  'menu_name' => 'Programs',
);

 register_taxonomy('programs',array('projects'), array(
  'hierarchical' => true,
  'labels' => $labels,
  'show_ui' => true,
  'show_in_rest' => true,
  'show_admin_column' => true,
  'query_var' => true,
  'rewrite' => array( 'slug' => 'program' ),
 ));
}

add_action( 'init', 'custom_hierarchical_taxonomy', 0 );

// Add the widget area to the header
if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar( array(
		'name' => 'Header Widget',
		'id' => 'tj-header-widget',
		'description' => 'Widget on Header',
		'before_widget' => '<div id="custom_widget_%1$s" class="custom_widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	) );
}

function add_header_widget() {
	dynamic_sidebar('tj-header-widget');
}

add_action( 'sinatra_header_widget_location', 'add_header_widget', 10 );

//Custom taxonomy Widget
class Widget_Custom_tax_tag_cloud {

  function control(){
    echo 'No control panel';
  }
  function widget($args){
    echo $args['before_widget'];
    echo $args['before_title'] . 'Programs' . $args['after_title'];
    $cloud_args = array(
     'title_li' => '',
     'hide_title_if_empty' => true,
     'style' => '',
     'taxonomy' => 'programs',
     'order'  => 'ASC',
    );
    echo wp_list_categories($cloud_args);
    echo $args['after_widget'];
  }
  function register(){
    register_sidebar_widget('Custom taxonomy list', array('Widget_Custom_tax_tag_cloud', 'widget'));
    register_widget_control('Custom taxonomy list', array('Widget_Custom_tax_tag_cloud', 'control'));
  }
}

add_action("widgets_init", array('Widget_Custom_tax_tag_cloud', 'register'));

//Custom taxonomy Guttenberg block

function my_plugin_block_categories( $categories, $post ) {
 if ( $post->post_type !== 'post' ) {
   return $categories;
 }
 return array_merge(
   $categories,
   array(
     array(
       'slug' => 'program',
       'title' => pll_register_string( 'my_plugin_block_categories', 'My category', 'my-plugin' ),
       'icon' => 'wordpress',
     ),
   )
 );
}

add_filter( 'block_categories', 'my_plugin_block_categories', 10, 2 );