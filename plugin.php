<?php
/*
Plugin Name: EM List Tweets
Plugin URI: https://github.com/elimc/em-list-tweets
Description: Displays recent tweets with Twitter API 1.1
Version: .9
Author: Eli McMakin
Author URI: http://elimcmakin.com/
Author Email: elimc184@hotmail.com
Network: false
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2013 Eli McMakin (elimc184@hothmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Display Tweets on frontend.
 * 
 * @link https://dev.twitter.com/docs/application-permission-model Twitter API 1.0 deprecation notice.
 * @link https://dev.twitter.com/docs Twitter developer documentation.
 * @link http://stackoverflow.com/a/15314662 Create a developer account for Twitter (tutorial).
 * @link http://stackoverflow.com/a/12939923 Grab Twitter feed.
 * @link http://wp.tutsplus.com/tutorials/plugins/how-to-create-a-recent-tweets-widget/ Code goodness.
 * @link http://wp.tutsplus.com/tutorials/getting-started-with-the-wordpress-transient-api-part-2/?search_index=2 Transients technique.
 */
class EM_List_Tweets extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'widget-name-id',
			__( 'EM List Tweets' ),
			array(
				'classname'		=>	'em-list-tweets',
				'description'	=>	__( 'Display latest tweets with Twitter API 1.1' )
			)
		);

	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
        
        // Dynamic vars input by the user.
        $twitter_username = $instance['username'];
        $tweets_to_display = $instance[ 'tweets_to_display' ];
        $cache_expiration = $instance[ 'cache_expiration' ];
        
        $oauth_access_token = $instance['oauth_access_token'];
        $oauth_access_token_secret = $instance['oauth_access_token_secret'];
        $consumer_key = $instance['consumer_key'];
        $consumer_secret = $instance['consumer_secret'];

		echo $before_widget;
        
        echo $before_title . $instance['title'] . $after_title;

		include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );

		echo $after_widget;

	} // end widget
    
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param	array	new_instance	The previous instance of values before the update.
	 * @param	array	old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

        $old_instance = array();
        
		$old_instance[ 'title' ] = ( !empty( $new_instance[ 'title' ] ) ) ? trim( strip_tags( $new_instance[ 'title' ] ) ) : '';
		$old_instance[ 'username' ] = ( !empty( $new_instance[ 'username' ] ) ) ? trim( strip_tags( str_replace( "@", "", $new_instance[ 'username' ]) ) ) : '';
		$old_instance[ 'tweets_to_display' ] = ( !empty( $new_instance[ 'tweets_to_display' ] ) ) ? trim( strip_tags( intval( $new_instance[ 'tweets_to_display' ] ) ) ) : '';
		$old_instance[ 'cache_expiration' ] = ( !empty( $new_instance[ 'cache_expiration' ] ) ) ? trim( strip_tags( intval( $new_instance[ 'cache_expiration' ] ) ) ) : '';
        
		$old_instance[ 'oauth_access_token' ] = ( !empty( $new_instance[ 'oauth_access_token' ] ) ) ? trim( strip_tags( $new_instance[ 'oauth_access_token' ] ) ) : '';
		$old_instance[ 'oauth_access_token_secret' ] = ( !empty( $new_instance[ 'oauth_access_token_secret' ] ) ) ? trim( strip_tags( $new_instance[ 'oauth_access_token_secret' ] ) ) : '';
		$old_instance[ 'consumer_key' ] = ( !empty( $new_instance[ 'consumer_key' ] ) ) ? trim( strip_tags( $new_instance[ 'consumer_key' ] ) ) : '';
		$old_instance[ 'consumer_secret' ] = ( !empty( $new_instance[ 'consumer_secret' ] ) ) ? trim( strip_tags( $new_instance[ 'consumer_secret' ] ) ) : '';

		return $old_instance;

		// TODO:	Here is where you update your widget's old values with the new, incoming values

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$title = ( isset( $instance[ 'title' ] ) ) ? esc_attr( $instance[ 'title' ] ) : "Twitter Feed";
		$username = ( isset( $instance[ 'username' ] ) ) ? esc_attr( $instance[ 'username' ] ) : "Twitter Username";
		$tweets_to_display = ( isset( $instance[ 'tweets_to_display' ] ) ) ? esc_attr( $instance[ 'tweets_to_display' ] ) : 5;
		$cache_expiration = ( isset( $instance[ 'cache_expiration' ] ) ) ? esc_attr( $instance[ 'cache_expiration' ] ) : 12;
        
		$oauth_access_token = ( isset( $instance[ 'oauth_access_token' ] ) ) ? esc_attr( $instance[ 'oauth_access_token' ] ) : "";
		$oauth_access_token_secret = ( isset( $instance[ 'oauth_access_token_secret' ] ) ) ? esc_attr( $instance[ 'oauth_access_token_secret' ] ) : "";
		$consumer_key = ( isset( $instance[ 'consumer_key' ] ) ) ? esc_attr( $instance[ 'consumer_key' ] ) : "";
		$consumer_secret = ( isset( $instance[ 'consumer_secret' ] ) ) ? esc_attr( $instance[ 'consumer_secret' ] ) : "";
        
		// Display the admin form
		include( plugin_dir_path(__FILE__) . '/views/admin.php' );

	} // end form

} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("EM_List_Tweets");' ) );
