<!-- This file is used to markup the public-facing widget. -->
<?php

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
        $twitter_data_feed = json_decode($json, true);
        
        /**
         * Grab Twitter feed from soft cache. Prevents the need to grab Twitter data ever time someone comes to the site.
         * 
         * @param array $twitter_data_feed - Array of Tweets grabbed from Twitter JSON.
         * @param int $cache_expiration - Set the soft cache to expire every so many hours.
         * @return array
         */
        function set_up_soft_cache( $twitter_data_feed, $cache_expiration = 12 ) {
            $user_expiration = 60 * 60 * $cache_expiration;
            
            // If the transient doesn't already exist, then set it up.
            if ( !get_transient( 'twitter_data_key' ) ) {
                set_transient( 'twitter_data_key', $twitter_data_feed, $user_expiration );
            }

            // Return the Twitter feed contained in the transient.
            $twitter_data = get_transient( 'twitter_data_key' );
            return $twitter_data;
        }
        
        $twitter_data = set_up_soft_cache( $twitter_data_feed, $cache_expiration );
        
        /**
         * Replace @username with a link to that twitter user
         *
         * @param string $parsed_link - Tweet text to parse.
         * @return string - Tweet text with @replies linked
         */
        function link_twitter_users( $parsed_link ) {
            $parsed_link = preg_replace_callback( "/(^|\s)@(\w+)/i", "link_twitter_users_cb", $parsed_link );
            return $parsed_link;
        }

        /**
         * Setup the @username matches to pass to a link building function.
         * 
         * @param string $matches - An array of all the @username matches.
         * @return string
         */
        function link_twitter_users_cb( $matches ) {
            $link_attributes = array(
                "href"	=> "http://twitter.com/" . urlencode( $matches[2] ),
                "class"	=> "twitter-user"
            );
            return " " . build_link( "@" . $matches[2], $link_attributes );
        }
        
        /**
        * Replace #hashtag with a link to twitter.com for that hashtag.
        *
        * @param string $text - Tweet text to parse.
        * @return string - Tweet text with #hashtags linked.
        */
        function make_hashtaggable( $parsed_link ) {
            $parsed_link = preg_replace_callback("/(^|\s)(#[\w\x{00C0}-\x{00D6}\x{00D8}-\x{00F6}\x{00F8}-\x{00FF}]+)/iu", "make_hashtaggable_cb", $parsed_link);
            return $parsed_link;
        }

        /**
         * Setup the hashtag matches to pass to a link building function.
         * 
         * @param array $matches - An array of all of the hashtag matches.
         * @return string - Tweet text with $hashtags linked.
         */
        function make_hashtaggable_cb( $matches ) {
            $link_attributes = array(
                "href"	=> "http://twitter.com/search?q=" . urlencode( $matches[2] ),
                "class"	=> "twitter-hashtag",
            );
            return " " . build_link( $matches[2], $link_attributes );
        }
        
        /**
         * Build links, from values in an assoc. array, with this generic helper function.
         * 
         * @param array $matches - An array of all of the hashtag matches.
         * @param array $link_attributes - An array of text needed for links to perform Twitter searches.
         * @return string - Tweet text with $hashtags linked.
         */
        function build_link( $matches, $link_attributes = array() ) {
            $link = "<a";
            foreach ( $link_attributes as $name => $value ) {
                $link .= " " . esc_attr( $name ) . "=\"" . esc_attr( $value ) . "\"";
            }
            $link .= ">" . esc_html( $matches ) . "</a>";
            return $link;
        }
        
        // The actual Tweet output.
        $twitter_output = "<ul>";
        if ($twitter_data['errors'][0]['message'] == 'Could not authenticate you') {
            $twitter_output .= "<li>There was an issue authenticating you with Twitter. Did you properly enter your oAuth information?</li>";
        } elseif ($twitter_data[0]['id_str']) { // Check if @username has any tweets.
            $i = 0;
            foreach ($twitter_data as $tweet) {
                if ($i < $tweets_to_display) {
                    if ($replies == 0 && $tweet['in_reply_to_screen_name'] !== NULL) { // If particular tweet is directed @username, then skip it.
                        continue;
                    }
                    
                    //
                    $twitter_output .= "<li>";

                        $parsed_link = $tweet['text'];

                        if ( !empty( $tweet['entities']['user_mentions'] ) ) { $parsed_link = link_twitter_users( $parsed_link ); }
                        if ( !empty( $tweet['entities']['hashtags'] ) ) { $parsed_link = make_hashtaggable( $parsed_link ); }
                        if ( !empty( $tweet['entities']['urls'] ) ) { $parsed_link = make_clickable( $parsed_link ); }
                        $twitter_output .= $parsed_link;

                        // Output a human readable time stamp.
                        if ($tweet['created_at']) {
                            $twitter_output .= "<span class='time-meta'>";
                                $time_diff = human_time_diff( strtotime( $tweet['created_at'] ) ) . ' ago';
                                $tweet_id_str = $tweet['id_str'];
                                $twitter_output .= "<a href=\"https://twitter.com/$twitter_username/status/$tweet_id_str\">" . $time_diff . "</a>";
                            $twitter_output .= "</span>";
                        }

                    $twitter_output .= "</li>";
                }
                $i++;
            }
        } else { // If tweets don't exist, or if they cannot be retrieved, then display an error message.
            $twitter_output .= "<li>There was an error: Either Twitter is down, or you have no tweets.</li>";
        }
        $twitter_output .= "</ul>";
        
        echo $twitter_output;