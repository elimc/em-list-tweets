**EM List Tweets**
==================

**EM List Tweets** is a WordPress widget that is designed to work with the latest version of Twitter's API 1.1.

About
-----

**EM List Tweets** is a simple way to display Tweets on your WordPress Web site.

You **will** need to create a Twitter Developer Account. This is how things work with Twitter API 1.1.

Screenshots
-----------

![displayes tweets example](https://raw.github.com/elimc/em-list-tweets/master/images/front_end.PNG "Displayed Tweets")

FAQ
---

__The display of the Tweet feed is ugly__

The widget has no styles applied to it. This gives you the freedom to integrate it into your custom theme with your custom styles.

__Why did a lot of the Twitter widgets for WordPress recently break?__

Twitter is now using oAuth to validate users. You can no longer grab a simple JSON stream from any user and display its contents.

__Why did they do that?__

I can only assume that they have an evil corporate agenda ([the real reason . . .](https://dev.twitter.com/docs/application-permission-model))

__What's wrong with Jetpack?__

It doesn't let me do things on my development server without signing up for WordPress.com

__Can I fork this, improve it, and send it back to you?__

YES! The more eyeballs that are refactoring my code to make it more secure, terse, and speedy, the better.

__Can I request feture updates for this Twitter?__

Sure. I am attempting to keep this widget as simple as possible, but if the change will increase utility more than it will increase complexity, I would consider updating the code. Alternatively, you can fork it and submit a pull request.

Requirements
------------

* Twitter oAuth tokens that come with Twitter Developer Account
* WordPress 3.3+

Installing Plugin
-----------------

The first thing to do is to download the widget and get it installed in your WordPress theme.

1) Download this widget as a .zip.

2) Upload .zip file to your plugins directory. You can find the file uploader under `Plugins => Add New` in the WordPress sidebar menu.

3) Click on the `Plugin` link in the WordPress sidebar. Find `EM List Tweets` in the list of plugins and click the activate link. Now your widget is activated!

Getting oAuth Tokens
--------------------

Now that your plugin in activated, you will have to set up a Twitter Developer Acount and generate some oAuth keys.

1) Sign up for a free ([developer account](https://dev.twitter.com/apps)) on Twitter.

2) Create a free app with your account. This is necessary to authenticate your to Twitter.

![create twitter app](https://raw.github.com/elimc/em-list-tweets/master/images/create_app.png "create twitter app")

3) Create oAuth access tokens. This will give you a consumer key, consumer secret, access token, and access token secret. Copy these, you will need them later!

![create oAuth access tokens](https://raw.github.com/elimc/em-list-tweets/master/images/create_access_token.png "create oAuth access tokens")

4) Change your requests from `read` to `read and write`. First you will select the `Settings` tab.

![settings tab](https://raw.github.com/elimc/em-list-tweets/master/images/change_access_levels.png "settings tab")

5) Change your application type to `Read and Write`.

![read and write seetings](https://raw.github.com/elimc/em-list-tweets/master/images/read_and_write.png "read and write seetings")

Final Step
----------

Put the oAuth keys into the widget in the widget admin section, along with Twitter username.

![widget options](https://raw.github.com/elimc/em-list-tweets/master/images/widget_screen.PNG "Widget options")

Changelog
---------

### 1.0.1 (September 7, 2013)
* Added @replies and mentions functionality.
* Updated documentation.
* Fixed some code documentation.

### 1.0 (August 29, 2013)
* Plugin fully functional.

### 0.9.1 (August 29, 2013)
* Updated documentation.
* Code refactoring.

### 0.9 (August 27, 2013)
* Plugin fully functional, but still in beta testing phase.

### 0.1 (August 23, 2013)
* Initial commit.

Author
------

Eli McMakin

Web site: www.elimcmakin.com

GitHub: https://github.com/elimc

**EM List Tweets** Repository: https://github.com/elimc/em-list-tweets