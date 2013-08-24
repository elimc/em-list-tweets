<?php
/*
Plugin Name: EM List Tweets
Plugin URI: https://github.com/elimc/em-list-tweets
Description: Displays recent tweets with Twitter API 1.1
Version: .1
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
 * @link http://wp.tutsplus.com/tutorials/plugins/how-to-create-a-recent-tweets-widget/ Soft cache code with transients and other code goodness.
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

		// load plugin text domain

		// Hooks fired when the Widget is activated and deactivated
		// register_activation_hook( __FILE__, array( $this, 'activate' ) );
		// register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		parent::__construct(
			'widget-name-id',
			__( 'EM List Tweets' ),
			array(
				'classname'		=>	'em-list-tweets',
				'description'	=>	__( 'Display latest tweets with Twitter API 1.1' )
			)
		);

		// Register admin styles and scripts
		// add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

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

		echo $before_widget;

		// TODO:	Here is where you manipulate your widget's values based on their input fields

        
        // Code to fetch the feed was taken from here: http://stackoverflow.com/a/12939923
        function buildBaseString($baseURI, $method, $params)
        {
            $r = array();
            ksort($params);
            foreach ($params as $key => $value) {
                $r[] = "$key=" . rawurlencode($value);
            }
            return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
        }

        function buildAuthorizationHeader($oauth)
        {
            $r = 'Authorization: OAuth ';
            $values = array();
            foreach ($oauth as $key => $value)
                $values[] = "$key=\"" . rawurlencode($value) . "\"";
            $r .= implode(', ', $values);
            return $r;
        }

        $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

        $oauth_access_token = "393914675-3VVKsDCwWaeNYBgFoEDlu3uC1UmwGgYYAiZJFkhq";
        $oauth_access_token_secret = "xd7VAW5RHiOcpmRfnyr7DrHotkGk2RqdxENqLtg1p1E";
        $consumer_key = "VBhd4yfDM5InG2WlUOP4xQ";
        $consumer_secret = "jR6kfTczx3CuGzjQPi7pbJNhbcUSaUlhyRunimOk";

        $oauth = array('oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0');

        $base_info = buildBaseString($url, 'GET', $oauth);
        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        // Make Requests
        $header = array(buildAuthorizationHeader($oauth), 'Expect:');
        $options = array(CURLOPT_HTTPHEADER => $header,
            //CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false);

        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);

        // JSON data, with our tweets, comes back as object. We convert it into an array and assign it to a var.
        $twitter_data = json_decode($json, true);
        
        var_dump($twitter_data[3]['entities']['hashtags']);

        // TODO: Change this into a dynamic var.
        $twitter_username = "EliMcMakin";
        
        $twitter_output = "<ul>";
        if ($twitter_data['errors'][0]['message'] == 'Could not authenticate you') {
            $twitter_output .= "<li>There was an issue authenticating you with Twitter. Did you properly enter your oAuth information?</li>";
        } elseif ($twitter_data[0]['id_str']) { // List any tweets if they exist.
            $i = 0;
            foreach ($twitter_data as $tweet) {
                if ($i < 5) {
                    if ($tweet['in_reply_to_screen_name'] === NULL) {

                        $twitter_output .= "<li>";
                            $twitter_output .= $tweet['text'];

                            // Output a human readable time stamp.
                            if ($tweet['created_at']) {
                                $twitter_output .= "<span class='time-meta'>";
                                    $time_diff = human_time_diff( strtotime( $tweet['created_at'] ) ) . ' ago';
                                    $tweet_id_str = $tweet['id_str'];
                                    $twitter_output .= "<a href=\"https://twitter.com/$twitter_username/status/$tweet_id_str\">" . $time_diff . "</a>";
                                $twitter_output .= "</span>";
                            }

                        $twitter_output .= "</li>";

                    } else {
                        continue;
                    }
                }
                $i++;
            }
        } else { // If tweets don't exist, or if they cannot be retrieved, then display an error message.
            $twitter_output .= "<li>There was an error: Either Twitter is down, or you have no tweets.</li>";
        }
        $twitter_output .= "</ul>";
        
        echo $twitter_output;
        
        var_dump($twitter_data);
        
        
        
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

		$instance = $old_instance;

		// TODO:	Here is where you update your widget's old values with the new, incoming values

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {

    	// TODO:	Define default values for your variables
		$instance = wp_parse_args(
			(array) $instance
		);

		// TODO:	Store the values of the widget in their own variable

		// Display the admin form
		include( plugin_dir_path(__FILE__) . '/views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param		boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	// public function activate( $network_wide ) {
		// TODO define activation functionality here
	// } // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	// public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	// } // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
//	public function register_admin_styles() {

		// TODO:	Change 'widget-name' to the name of your plugin
//		wp_enqueue_style( 'widget-name-admin-styles', plugins_url( 'widget-name/css/admin.css' ) );

//	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
//	public function register_admin_scripts() {

		// TODO:	Change 'widget-name' to the name of your plugin
//		wp_enqueue_script( 'widget-name-admin-script', plugins_url( 'widget-name/js/admin.js' ), array('jquery') );

//	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
//	public function register_widget_styles() {

		// TODO:	Change 'widget-name' to the name of your plugin
//		wp_enqueue_style( 'widget-name-widget-styles', plugins_url( 'widget-name/css/widget.css' ) );

//	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
//	public function register_widget_scripts() {

		// TODO:	Change 'widget-name' to the name of your plugin
//		wp_enqueue_script( 'widget-name-script', plugins_url( 'widget-name/js/widget.js' ), array('jquery') );

//	} // end register_widget_scripts

} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("EM_List_Tweets");' ) );
