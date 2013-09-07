<!-- This file is used to markup the administration form of the widget. -->

		<p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo "Title:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php echo "Username:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'tweets_to_display' ); ?>"><?php echo "Number of Tweets to show: "; ?></label><input id="<?php echo $this->get_field_id( 'tweets_to_display' ); ?>" name="<?php echo $this->get_field_name( 'tweets_to_display' ); ?>" type="text" value="<?php echo $tweets_to_display; ?>" size="1"/>
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'cache_expiration' ); ?>"><?php echo "Refresh feed every "; ?></label><input id="<?php echo $this->get_field_id( 'cache_expiration' ); ?>" name="<?php echo $this->get_field_name( 'cache_expiration' ); ?>" type="text" value="<?php echo $cache_expiration; ?>" size="3" /><label for="<?php echo $this->get_field_id( 'cache_expiration' ); ?>"> hours.</label>
		</p>
        <p>
            <?php echo "Display @replies and mentions:"; ?><br>
            <label for="<?php echo $this->get_field_id('replies'); ?>">
                <?php echo "Yes: "; ?>
                <input class="radio" id="<?php echo $this->get_field_id('replies'); ?>" name="<?php echo $this->get_field_name('replies'); ?>" type="radio" value="1" <?php checked( $replies, 1 ); ?> />
            </label>
            &nbsp; &nbsp;
            <label for="<?php echo $this->get_field_id('replies'); ?>">
                <?php echo " No: "; ?>
                <input class="radio" id="<?php echo $this->get_field_id('replies'); ?>" name="<?php echo $this->get_field_name('replies'); ?>" type="radio" value="0" <?php checked( $replies, 0 ); ?> />
            </label>                
        </p>
		<p>
            <label for="<?php echo $this->get_field_id( 'oauth_access_token' ); ?>"><?php echo "oAuth Access Token:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'oauth_access_token' ); ?>" name="<?php echo $this->get_field_name( 'oauth_access_token' ); ?>" type="text" value="<?php echo $oauth_access_token; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'oauth_access_token_secret' ); ?>"><?php echo "oAuth Access Token Secret:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'oauth_access_token_secret' ); ?>" name="<?php echo $this->get_field_name( 'oauth_access_token_secret' ); ?>" type="text" value="<?php echo $oauth_access_token_secret; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>"><?php echo "Consumer Key:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" type="text" value="<?php echo $consumer_key; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>"><?php echo "Consumer Secret:"; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" type="text" value="<?php echo $consumer_secret; ?>" />
		</p>