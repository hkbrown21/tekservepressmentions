<?php
/**
 * Plugin Name: Tekserve Press Mentions
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Custom Post Type for Press Mentions; Includes Custom Fields
 * Version: 1.0a
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2013 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

add_action( 'init', 'create_post_type_press' );
function create_post_type_press() {
	register_post_type( 'tekserve_press',
		array(
			'labels' => array(
				'name' => __( 'Press Mentions' ),
				'singular_name' => __( 'Press Mention' ),
				'add_new' => 'Add New',
            	'add_new_item' => 'Add New Press Mention',
            	'edit' => 'Edit',
            	'edit_item' => 'Edit Press Mention',
            	'new_item' => 'New Press Mention',
            	'view' => 'View',
            	'view_item' => 'View Press Mention',
            	'search_items' => 'Search Press Mentions',
            	'not_found' => 'No Press Mentions found',
            	'not_found_in_trash' => 'No Press Mentions found in Trash',
            	'parent' => 'Parent Press Mentions',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'press'),
            'supports' => array( 'title', 'editor', 'comments', 'excerpt', 'thumbnail' ),
		)
	);
}

add_action( 'admin_init', 'press_custom_fields' );

function press_custom_fields() {
    add_meta_box( 'tekserve_press_meta_box',
        'Article Details',
        'display_tekserve_press_meta_box',
        'tekserve_press', 'normal', 'high'
    );
}

function display_tekserve_press_meta_box( $tekserve_press ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
    $tekserve_press_publication = esc_html( get_post_meta( $tekserve_press->ID, 'tekserve_press_publication', true ) );
	$tekserve_press_author = esc_html( get_post_meta( $tekserve_press->ID, 'tekserve_press_author', true ) );
	$tekserve_press_url = esc_html( get_post_meta( $tekserve_press->ID, 'tekserve_press_url', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 100%">Publication</td>
            <td><input type="text" size="80" name="tekserve_press_publication" value="<?php echo $tekserve_press-publication; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Author</td>
            <td><input type="text" size="80" name="tekserve_press_author" value="<?php echo $tekserve_press-author; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Link to Original Article</td>
            <td><input type="text" size="80" name="tekserve_press_url" value="<?php echo $tekserve_press-url; ?>" /></td>
        </tr>
    </table>
    <?php
}

add_action( 'save_post', 'add_tekserve_press_fields', 10, 2 );

function add_tekserve_press_fields( $tekserve_press_id, $tekserve_press ) {
    // Check post type for 'tekserve_press'
    if ( $tekserve_press->post_type == 'tekserve_press' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['tekserve_press_publication'] ) && $_POST['tekserve_press_publication'] != '' ) {
            update_post_meta( $tekserve_press_id, 'tekserve_press_publication', $_POST['tekserve_press_tekserve_press_publication'] );
        }
        if ( isset( $_POST['tekserve_press_author'] ) && $_POST['tekserve_press_author'] != '' ) {
            update_post_meta( $tekserve_press_id, 'tekserve_press_author', $_POST['tekserve_press_tekserve_press_author'] );
        }
        if ( isset( $_POST['tekserve_press_url'] ) && $_POST['tekserve_press_url'] != '' ) {
            update_post_meta( $tekserve_press_id, 'tekserve_press_url', $_POST['tekserve_press_tekserve_press_url'] );
        }
    }
}
add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'tekserve_press' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-tekserve_press.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-tekserve_press.php';
            }
        }
    }
    return $template_path;
}