<?php
/**
 * Plugin Name: WSUWP User Roles
 * Plugin URI: https://web.wsu.edu/
 * Description: Create additional user roles for sites
 * Version: 0.0.1
 * Requires PHP: 7.0
 * Author: Washington State University, Danial Bleile
 * Author URI: https://web.wsu.edu/
 * Text Domain: wsuwp-plugin-user-roles
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


register_activation_hook( __FILE__, 'wsuwp_plugin_user_roles_activation' );


function wsuwp_plugin_user_roles_activation( $is_network_wide ) {

	if ( is_multisite() && $is_network_wide ) {

		foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blog_id ) {

			switch_to_blog( $blog_id );

				wsuwp_plugin_user_roles_create_site_editor();
				wsuwp_plugin_user_roles_create_site_contributor();
				wsuwp_plugin_user_roles_create_inactive();

			restore_current_blog();
		}
	} else {

		wsuwp_plugin_user_roles_create_site_editor();
		wsuwp_plugin_user_roles_create_site_contributor();
		wsuwp_plugin_user_roles_create_inactive();

	}

}


function wsuwp_plugin_user_roles_create_site_editor() {

	add_role(
		'site_editor',
		'Site Editor',
		array(
			'delete_others_pages'    => true,
			'delete_others_posts'    => true,
			'delete_pages'           => true,
			'delete_posts'           => true,
			'delete_private_pages'   => true,
			'delete_private_posts'   => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'edit_others_pages'      => true,
			'edit_others_posts'      => true,
			'edit_pages'             => true,
			'edit_posts'             => true,
			'edit_private_pages'     => true,
			'edit_private_posts'     => true,
			'edit_published_pages'   => true,
			'edit_published_posts'   => true,
			'edit_theme_options'     => true,
			'manage_categories'      => true,
			'manage_links'           => true,
			'manage_options'         => true,
			'moderate_comments'      => true,
			'publish_pages'          => true,
			'publish_posts'          => true,
			'read'                   => true,
			'read_private_pages'     => true,
			'read_private_posts'     => true,
			'unfiltered_html'        => true,
			'upload_files'           => true,
			'customize'              => true,
		)
	);

}

function wsuwp_plugin_user_roles_create_site_contributor() {

	add_role(
		'site_contributor',
		'Site Contributor',
		array(
			'delete_pages'           => true,
			'delete_posts'           => true,
			'delete_private_pages'   => true,
			'delete_private_posts'   => true,
			'edit_others_pages'      => true,
			'edit_others_posts'      => true,
			'edit_pages'             => true,
			'edit_posts'             => true,
			'edit_private_pages'     => true,
			'edit_private_posts'     => true,
			'edit_published_pages'   => true,
			'edit_published_posts'   => true,
			'manage_categories'      => true,
			'moderate_comments'      => true,
			'read'                   => true,
			'read_private_pages'     => true,
			'read_private_posts'     => true,
			'upload_files'           => true,
		)
	);

}


function wsuwp_plugin_user_roles_create_inactive() {

	add_role(
		'inactive',
		'Inactive',
		array(
			'read' => true,
		)
	);

}

function wsuwp_plugin_user_roles_filter_post( $data, $postarr ) {

	if ( ! current_user_can( 'publish_posts' ) && $data['post_status'] === 'pending' && ( get_post_status( $postarr['ID'] ) === 'publish' ) ) {

		$data['post_status'] = 'publish';

	}

	return $data;

}
