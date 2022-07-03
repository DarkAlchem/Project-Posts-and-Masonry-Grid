<?php 
/*
Plugin Name: Increase Project Posts
Description: Created to show project posts for a client using single post and a masonry.
Version: 1.4.6
Author: Brandon Reed
Author URI: https://TheGamerConnect.com
License: GPLv2 or later 
*/

$debug=false;

//Actions
//add_action( 'admin_init', 'ipp_plugin_has_parents' );
add_action('init', 'createProjectPosts');
add_action( 'wp_enqueue_scripts', 'enqueueSiteScripts' );
add_action('acf/init', 'createACFFields');
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
add_filter( 'single_template', 'ipp_custom_post' );
add_filter( 'template_include', 'template_chooser');
add_filter('theme_page_templates','ipp_template_register',10,3);
add_filter('template_include','ipp_template_select',99);
if (!$debug) add_filter('acf/settings/show_admin', '__return_false');

//Functions
function ipp_plugin_has_parents() {
    if ( is_admin() && current_user_can( 'activate_plugins') && !is_plugin_active( 'advanced-custom-fields-pro/acf.php') ) {

        add_action( 'admin_notices', 'ipp_plugin_notice' );
        deactivate_plugins( plugin_basename( __FILE__) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
function ipp_plugin_notice() {
    ?><div class="error"><p>Sorry, But Increase Project Posts requires Advanced Custom Fields to be installed and activated</p></div><?php 
}

function enqueueSiteScripts() {
    global $post;
    $post_slug = $post->post_name;
    if ((is_search() && get_post_type() == 'project-post') || is_page_template('template-project.php') || is_singular( 'project-post' )){
        wp_enqueue_script( 'masonryscripts',"https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js");
        wp_enqueue_script( 'ippscripts', plugin_dir_url( __FILE__ ) . 'js/scripts.js' );                      
        wp_enqueue_style( 'ippstyles',  plugin_dir_url( __FILE__ ) . 'css/styles.css' );                      
    }
}

function prefix_disable_gutenberg($current_status, $post_type){
    // Use your post type key instead of 'product'
    if ($post_type === 'project-post') return false;
    return $current_status;
}

function ipp_custom_post($single_template) {
     global $post;
     if ($post->post_type == 'project-post' ) {
          $single_template = dirname( __FILE__ ) . '/single-project-post.php';
     }
     return $single_template;
}

function ipp_template_loader(){
    $template=[];
    $template['template-project.php']='Project Post Masonry';
    return $template;
}

function ipp_template_register($page_templates,$theme,$post){
    $templates = ipp_template_loader();

    foreach($templates as $tk=>$tv){
        $page_templates[$tk]=$tv;    
    }
    return $page_templates;
}

function ipp_template_select($template){
    global $post,$wp_query,$wpdb;
    $page_temp_slug= get_page_template_slug($post->ID);
    $templates = ipp_template_loader();
    if(isset($templates[$page_temp_slug])){
        $template=plugin_dir_path(__FILE__).$page_temp_slug;
    }
    return $template;
}

function createProjectPosts(){
    register_taxonomy_for_object_type('category', 'project-post'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'project-post');
    register_post_type('project-post', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Project Post', 'project-post'), // Rename these to suit
            'singular_name' => __('Projects', 'project-post'),
            'add_new' => __('Add New', 'project-post'),
            'add_new_item' => __('Add New Project', 'project-post'),
            'edit' => __('Edit', 'project-post'),
            'edit_item' => __('Edit Project', 'project-post'),
            'new_item' => __('New Project', 'project-post'),
            'view' => __('View Project', 'project-post'),
            'view_item' => __('View Project', 'project-post'),
            'search_items' => __('Search Project', 'project-post'),
            'not_found' => __('No Projects Fount', 'project-post'),
            'not_found_in_trash' => __('No Projects found in Trash', 'project-post')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

function template_chooser($template)   {    
  global $wp_query;   
  $file_name = 'project-archives.php';
  $post_type = get_query_var('post_type');   
    if( $wp_query->is_search && $post_type == 'project-post' ) {
        return dirname( __FILE__ ) . '/' . $file_name;;  //  redirect to archive-search.php
    }   
    return $template;   
}

function createACFFields(){
    if( function_exists('acf_add_local_field_group') ):
        acf_add_local_field_group(array(
            'key' => 'group_6293de3b45aa8',
            'title' => 'Project Post Fields',
            'fields' => array(
                array(
                    'key' => 'field_6293de54ac55b',
                    'label' => 'Post Fields',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_6293de83ac55c',
                    'label' => 'Landing Image',
                    'name' => 'landing_image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_6294f6839cc64',
                    'label' => 'Photo Credits',
                    'name' => 'photo_credits',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => 'br',
                ),
                array(
                    'key' => 'field_6294f6989cc65',
                    'label' => 'Credits Color',
                    'name' => 'credits_color',
                    'type' => 'color_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_6294f6839cc64',
                                'operator' => '!=empty',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'enable_opacity' => 0,
                    'return_format' => 'string',
                ),
                array(
                    'key' => 'field_6293dedfac55d',
                    'label' => 'Location',
                    'name' => 'post_location',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Royal Oak, MI',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293df6fac55e',
                    'label' => 'Website',
                    'name' => 'website_url',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'http://www.google.com',
                ),
                array(
                    'key' => 'field_6293e08cac560',
                    'label' => 'Tagline',
                    'name' => 'post_tagline',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Tagline for Section',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293e0a2ac561',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'wysiwyg',
                    'instructions' => 'About Description for the Post',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacus magna, tempor ut purus vel, ultrices cursus dolor. Donec mattis quam luctus neque egestas, nec tempus ante ornare. Phasellus ullamcorper lobortis varius. Integer nec purus ac leo gravida rutrum in non dui. Praesent eu neque non tellus condimentum consectetur et porta ligula. Praesent odio justo, auctor in sapien eget, auctor elementum felis. Aenean egestas metus ut dolor volutpat, sit amet tempus ante dictum. Morbi finibus pharetra facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed felis a erat consequat blandit. Sed erat mauris, blandit nec aliquam et, feugiat ac justo. Phasellus quam felis, iaculis vitae sapien in, tincidunt scelerisque lacus. In tincidunt, metus sed posuere dignissim, odio ipsum iaculis risus, maximus commodo diam erat ac diam.

        Mauris placerat nisl vel magna dapibus, quis congue felis tincidunt. Vestibulum finibus eget nisi et egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse aliquam auctor finibus. Sed facilisis mi eu nibh vehicula lobortis ut et dolor. Sed faucibus tempus dui eget auctor. Praesent posuere rutrum ultricies. Suspendisse vulputate eros in lacinia sollicitudin. Proin ultrices justo ut risus pulvinar, sit amet aliquet mi dictum. Aliquam tristique nunc sem, a interdum neque pharetra sed. Fusce facilisis mauris metus, nec venenatis nisl scelerisque varius. Nulla aliquet mollis purus a placerat. Phasellus ac lectus eget leo volutpat malesuada vitae eget nulla. Ut non luctus turpis. Nam a libero vel nunc viverra vestibulum ut a purus. Duis gravida pretium lacinia.

        Curabitur nec rhoncus ex. Ut odio nulla, commodo nec risus in, ultrices lobortis mauris. Vivamus rutrum mauris quis eros finibus lobortis. Integer tempus mollis ex, dapibus condimentum lorem pellentesque non. Aliquam dignissim pretium sodales. Suspendisse semper iaculis libero, at commodo elit consectetur non. Integer mattis nisl et elit gravida, sed suscipit sem dapibus. Ut condimentum, eros eget elementum imperdiet, nisi lacus porttitor lorem, et semper ex eros vitae sapien. Nullam ut lobortis lacus, a scelerisque nisl. Morbi vestibulum lacus a ligula tincidunt facilisis. Morbi maximus orci a consequat facilisis. Suspendisse vitae ligula justo. In nisi justo, ultricies nec tempor et, aliquet in diam. Morbi porttitor ipsum eu feugiat gravida. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                    'delay' => 0,
                ),
                array(
                    'key' => 'field_6293e04cac55f',
                    'label' => 'Sidebar',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_6293e287ac562',
                    'label' => 'Stats',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_6293e394ac567',
                    'label' => 'Logo',
                    'name' => 'post_logo',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_6293e3caac568',
                    'label' => 'Year Founded',
                    'name' => 'year_founded',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 2022,
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => 1700,
                    'max' => 2100,
                    'step' => 1,
                ),
                array(
                    'key' => 'field_6293e428ac569',
                    'label' => 'Budget Size',
                    'name' => 'budget_side',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '$11M-50M',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293e523ac56a',
                    'label' => 'Staff Size',
                    'name' => 'staff_size',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'More than 50',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293e5ddac56b',
                    'label' => 'Project Director',
                    'name' => 'project_director',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Lourdes Lopez, Artistic Director',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293e35cac564',
                    'label' => 'Social Media',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_6293e7d4ac56c',
                    'label' => 'Social Media',
                    'name' => 'social_media',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'block',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6293e93bac56d',
                            'label' => 'Social Type',
                            'name' => 'social_type',
                            'type' => 'select',
                            'instructions' => 'Social Media Type',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'twitter' => 'Twitter',
                                'youtube' => 'Youtube',
                            ),
                            'default_value' => false,
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_6293ea8aac56e',
                            'label' => 'Social URL',
                            'name' => 'social_url',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '75',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_6293e371ac565',
                    'label' => 'Ad Space',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_6293eb90ac56f',
                    'label' => 'Ad Image',
                    'name' => 'ad_image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_6293ebb3ac570',
                    'label' => 'Ad Link',
                    'name' => 'ad_link',
                    'type' => 'link',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_6293e387ac566',
                    'label' => 'Contact',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_6293ec1aac571',
                    'label' => 'Contact Name',
                    'name' => 'contact_name',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Amber Dorsky',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293ec28ac572',
                    'label' => 'Contact Title',
                    'name' => 'contact_title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Director of Public Relations & Communications',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_6293ec38ac573',
                    'label' => 'Contact Email',
                    'name' => 'contact_email',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'amber.dorsky@miamicityballet.com',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'project-post',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'the_content',
                1 => 'excerpt',
                2 => 'discussion',
                3 => 'comments',
                4 => 'revisions',
                5 => 'format',
                6 => 'page_attributes',
                7 => 'featured_image',
                8 => 'tags',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    endif;		
}